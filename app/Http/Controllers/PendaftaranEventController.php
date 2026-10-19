<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PendaftaranEventController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil ID event yang sudah didaftari oleh user ini
        $myRegistrations = EventRegistration::with('event')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $registeredEventIds = $myRegistrations->pluck('event_id')->toArray();

        // Event yang aktif dan terbuka untuk pendaftaran (atau semua event aktif)
        $availableEvents = Event::where('status', 'OPEN')
            ->orWhereIn('phase', ['PERSIAPAN', 'SIAP DILAKSANAKAN', 'BERLANGSUNG'])
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($event) use ($registeredEventIds) {
                $registeredCount = EventRegistration::where('event_id', $event->id)->count();
                $isFull = $event->quota !== null && $registeredCount >= $event->quota;
                $isRegistered = in_array($event->id, $registeredEventIds);

                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'category' => $event->category ?? 'Kegiatan',
                    'date' => $event->date,
                    'time' => $event->time,
                    'location' => $event->location,
                    'description' => $event->description,
                    'quota' => $event->quota,
                    'registered_count' => $registeredCount,
                    'is_full' => $isFull,
                    'is_registered' => $isRegistered,
                    'banner_image' => $event->banner_image,
                    'status' => $event->status,
                    'phase' => $event->phase,
                ];
            });

        return view('pendaftaran.index', [
            'availableEvents' => $availableEvents,
            'myRegistrations' => $myRegistrations,
        ]);
    }

    public function store(Request $request, Event $event)
    {
        $user = Auth::user();

        if ($event->status !== 'OPEN') {
            return back()->with('error', 'Pendaftaran untuk kegiatan ini saat ini belum atau sudah tidak dibuka.');
        }

        $now = now();
        if ($event->registration_start && $now->lt($event->registration_start)) {
            return back()->with('error', 'Pendaftaran kegiatan ini baru akan dibuka pada '.$event->registration_start->translatedFormat('d F Y H:i'));
        }
        if ($event->registration_end && $now->gt($event->registration_end)) {
            return back()->with('error', 'Pendaftaran kegiatan ini telah ditutup.');
        }

        return DB::transaction(function () use ($event, $user) {
            $lockedEvent = Event::where('id', $event->id)->lockForUpdate()->first();

            $existing = EventRegistration::where('event_id', $lockedEvent->id)
                ->where('user_id', $user->id)
                ->first();

            if ($existing) {
                return back()->with('error', 'Anda sudah terdaftar dalam kegiatan ini.');
            }

            if ($lockedEvent->quota !== null) {
                $count = EventRegistration::where('event_id', $lockedEvent->id)->count();
                if ($count >= $lockedEvent->quota) {
                    return back()->with('error', 'Mohon maaf, kuota peserta untuk kegiatan ini telah penuh.');
                }
            }

            $year = now()->format('Y');
            $code = 'SKM-EVT-'.$year.'-'.strtoupper(Str::random(4)).'-'.$user->id;

            EventRegistration::create([
                'event_id' => $lockedEvent->id,
                'user_id' => $user->id,
                'reg_code' => $code,
                'status' => 'REGISTERED',
                'registered_at' => now(),
            ]);

            return back()->with('success', 'Selamat! Anda berhasil mendaftar ke kegiatan '.$lockedEvent->title.'. Kode tiket Anda: '.$code);
        });
    }

    public function cancel(EventRegistration $registration)
    {
        if ($registration->user_id !== Auth::id()) {
            abort(403);
        }

        $registration->delete();

        return back()->with('success', 'Pendaftaran kegiatan berhasil dibatalkan.');
    }
}
