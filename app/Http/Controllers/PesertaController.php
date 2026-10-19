<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    public function index()
    {
        $events = Event::withCount('attendances')->orderBy('date', 'desc')->get();
        return view('peserta.index', compact('events'));
    }

    public function show(Request $request, Event $event)
    {
        $query = Attendance::with(['user', 'user.member'])->where('event_id', $event->id);

        if ($request->filled('kelas')) {
            $query->whereHas('user.member', function($q) use ($request) {
                $q->where('kelas', 'like', '%' . $request->kelas . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $attendances = $query->get();
        
        $stats = [
            'total' => $event->attendances()->count(),
            'hadir' => $event->attendances()->where('status', 'Hadir')->count(),
            'izin' => $event->attendances()->where('status', 'Izin')->count(),
            'sakit' => $event->attendances()->where('status', 'Sakit')->count(),
            'alpa' => $event->attendances()->where('status', 'Alpa')->count(),
        ];

        $users = User::whereDoesntHave('member')->orWhereHas('member')->get(); // Just get all users to select from for adding participant, preferably those who aren't in this event yet.
        $availableUsers = User::whereNotIn('id', $event->attendances()->pluck('user_id'))->get();

        return view('peserta.show', ['Event' => $event, 'attendances' => $attendances, 'stats' => $stats, 'availableUsers' => $availableUsers]);
    }

    public function store(Request $request, Event $event)
    {
        if (!auth()->user()->hasRole(['ADMIN', 'KETUA'])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string',
        ]);

        Attendance::updateOrCreate(
            ['event_id' => $event->id, 'user_id' => $request->user_id],
            ['status' => $request->status, 'recorded_by' => auth()->id()]
        );

        return redirect()->route('peserta.show', $event->id)->with('success', 'Peserta berhasil ditambahkan.');
    }

    public function update(Request $request, Event $event, Attendance $attendance)
    {
        if (!auth()->user()->hasRole(['ADMIN', 'KETUA'])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'status' => 'required|string',
        ]);

        $attendance->update([
            'status' => $request->status,
            'recorded_by' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Status presensi berhasil diupdate.');
    }

    public function destroy(Event $event, Attendance $attendance)
    {
        if (!auth()->user()->hasRole(['ADMIN', 'KETUA'])) {
            abort(403, 'Unauthorized');
        }

        $attendance->delete();

        return redirect()->back()->with('success', 'Peserta berhasil dihapus dari event.');
    }
}
