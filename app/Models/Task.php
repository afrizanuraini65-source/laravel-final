<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function updates()
    {
        return $this->hasMany(TaskUpdate::class)->orderBy('created_at', 'desc');
    }
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function eventCommittee()
    {
        return $this->belongsTo(EventCommittee::class, 'event_committee_id');
    }
}
