<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function committees()
    {
        return $this->hasMany(EventCommittee::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
