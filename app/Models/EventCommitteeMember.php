<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCommitteeMember extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function committee()
    {
        return $this->belongsTo(EventCommittee::class, 'event_committee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
