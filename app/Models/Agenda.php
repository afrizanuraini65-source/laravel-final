<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'title', 'date', 'start_time', 'end_time', 'location', 
        'description', 'category', 'division_id', 'created_by', 
        'status', 'reminder_enabled', 'reminder_minutes_before'
    ];

    protected $casts = [
        'date' => 'date',
        'reminder_enabled' => 'boolean',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
