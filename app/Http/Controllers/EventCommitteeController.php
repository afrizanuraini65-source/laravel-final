<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCommittee;
use App\Models\EventCommitteeMember;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\EventNotification;
use Illuminate\Support\Facades\Notification;

class EventCommitteeController extends Controller
{
    public function storeSection(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $event->committees()->create([
            'name' => $request->name
        ]);

        return redirect()->route('feature.event.show', $event->id)->with('success', 'Seksi/Divisi Panitia berhasil ditambahkan.');
    }

    public function destroySection(Event $event, EventCommittee $committee)
    {
        $committee->delete();
        return redirect()->route('feature.event.show', $event->id)->with('success', 'Seksi/Divisi Panitia berhasil dihapus.');
    }

    public function storeMember(Request $request, Event $event, EventCommittee $committee)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|max:255',
        ]);

        $member = $committee->members()->create([
            'user_id' => $request->user_id,
            'role' => $request->role,
        ]);

        $user = User::find($request->user_id);
        
        // Notify the user
        $user->notify(new EventNotification([
            'type' => 'committee',
            'message' => "Kamu ditunjuk sebagai {$request->role} di seksi {$committee->name}",
            'event' => $event->title,
            'url' => route('feature.event.show', $event->id)
        ]));

        return redirect()->route('feature.event.show', $event->id)->with('success', 'Anggota panitia berhasil ditambahkan.');
    }

    public function destroyMember(Event $event, EventCommitteeMember $member)
    {
        $member->delete();
        return redirect()->route('feature.event.show', $event->id)->with('success', 'Anggota panitia berhasil dihapus.');
    }
}
