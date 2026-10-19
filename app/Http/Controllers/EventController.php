<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('date', 'desc')->get();
        
        $stats = [
            'total' => $events->count(),
            'draft' => $events->where('phase', 'DRAFT')->count(),
            'persiapan' => $events->whereIn('phase', ['PERANCANGAN', 'PEMBENTUKAN PANITIA', 'PERSIAPAN'])->count(),
            'siap' => $events->where('phase', 'SIAP DILAKSANAKAN')->count(),
            'berlangsung' => $events->where('phase', 'BERLANGSUNG')->count(),
            'selesai' => $events->whereIn('phase', ['SELESAI', 'EVALUASI'])->count(),
        ];

        return view('events.index', compact('events', 'stats'));
    }

    public function create()
    {
        if (!auth()->user()->hasRole(['ADMIN', 'KETUA'])) {
            abort(403, 'Unauthorized action.');
        }
        return view('events.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole(['ADMIN', 'KETUA'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'date' => 'required|date',
            'time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'docs_link' => 'nullable|url',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . time();

        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('events', 'public');
            $validated['banner_image'] = $path;
        }

        Event::create($validated);

        if ($request->has('redirect') && $request->redirect == 'peserta') {
            return redirect()->route('peserta.index')->with('success', 'Event/Kegiatan berhasil ditambahkan!');
        }

        return redirect()->route('feature.event.index')->with('success', 'Event berhasil ditambahkan!');
    }

    public function show(Event $Event)
    {
        $Event->load([
            'committees.members.user',
            'tasks.assignee',
            'tasks.division',
            'tasks.eventCommittee',
            'attendances.user'
        ]);

        $users = \App\Models\User::orderBy('name')->get();
        $divisions = \App\Models\Division::all();

        return view('events.show', [
            'event' => $Event,
            'users' => $users,
            'divisions' => $divisions
        ]);
    }

    public function edit(Event $Event)
    {
        if (!auth()->user()->hasRole(['ADMIN', 'KETUA'])) {
            abort(403, 'Unauthorized action.');
        }
        return view('events.edit', ['event' => $Event]);
    }

    public function update(Request $request, Event $Event)
    {
        if (!auth()->user()->hasRole(['ADMIN', 'KETUA'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'date' => 'required|date',
            'time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'docs_link' => 'nullable|url',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('banner_image')) {
            if ($Event->banner_image) {
                Storage::disk('public')->delete($Event->banner_image);
            }
            $path = $request->file('banner_image')->store('events', 'public');
            $validated['banner_image'] = $path;
        }

        $Event->update($validated);

        return redirect()->route('feature.event.index')->with('success', 'Event berhasil diupdate!');
    }

    public function destroy(Event $Event)
    {
        if (!auth()->user()->hasRole(['ADMIN', 'KETUA'])) {
            abort(403, 'Unauthorized action.');
        }

        if ($Event->banner_image) {
            Storage::disk('public')->delete($Event->banner_image);
        }
        
        $Event->delete();

        return redirect()->route('feature.event.index')->with('success', 'Event berhasil dihapus!');
    }

    public function storeTask(Request $request, Event $event)
    {
        if (!auth()->user()->hasRole(['ADMIN', 'KETUA'])) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_committee_id' => 'nullable|exists:event_committees,id',
            'assigned_to_user_id' => 'required|exists:users,id',
            'deadline' => 'required|date',
            'priority' => 'required|in:Rendah,Sedang,Tinggi',
        ]);

        $validated['event_id'] = $event->id;
        $validated['status'] = 'To Do'; // Default for events
        $validated['progress'] = 0;

        $task = \App\Models\Task::create($validated);

        \App\Models\TaskUpdate::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'progress' => 0,
            'status' => 'To Do',
            'catatan' => 'Tugas baru dibuat untuk event.',
        ]);

        $user = \App\Models\User::find($request->assigned_to_user_id);
        
        if ($user) {
            $user->notify(new \App\Notifications\EventNotification([
                'type' => 'task',
                'message' => "Ada tugas baru untukmu: {$task->title}",
                'event' => $event->title,
                'url' => route('feature.event.show', $event->id)
            ]));
        }

        return redirect()->back()->with('success', 'Tugas berhasil ditambahkan.');
    }


    // Retained for backward compatibility
    public function register(Request $request, Event $event)
    {
        $user = Auth::user();

        if ($event->status !== 'OPEN') {
            return redirect()->back()->withErrors(['message' => 'Event ini tidak terbuka untuk pendaftaran.']);
        }

        $now = now();
        if ($event->registration_start && $now->lt($event->registration_start)) {
            return redirect()->back()->withErrors(['message' => 'Pendaftaran belum dibuka.']);
        }
        if ($event->registration_end && $now->gt($event->registration_end)) {
            return redirect()->back()->withErrors(['message' => 'Pendaftaran sudah ditutup.']);
        }

        return DB::transaction(function () use ($event, $user) {
            $lockedEvent = Event::where('id', $event->id)->lockForUpdate()->first();

            $existing = EventRegistration::where('event_id', $lockedEvent->id)
                ->where('user_id', $user->id)
                ->first();
                
            if ($existing) {
                return redirect()->back()->withErrors(['message' => 'Anda sudah terdaftar di event ini.']);
            }

            if ($lockedEvent->quota !== null) {
                $registeredCount = EventRegistration::where('event_id', $lockedEvent->id)->count();
                if ($registeredCount >= $lockedEvent->quota) {
                    return redirect()->back()->withErrors(['message' => 'Kuota pendaftaran telah penuh.']);
                }
            }

            $year = now()->format('Y');
            $randomString = strtoupper(Str::random(4));
            $regCode = "SKM-EVT-{$year}-{$randomString}-{$user->id}";

            EventRegistration::create([
                'event_id' => $lockedEvent->id,
                'user_id' => $user->id,
                'reg_code' => $regCode,
                'status' => 'REGISTERED'
            ]);

            return redirect()->back()->with('success', 'Berhasil mendaftar ke event dengan kode tiket: ' . $regCode);
        });
    }
}
