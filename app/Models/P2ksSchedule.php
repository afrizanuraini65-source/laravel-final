<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'scheduled_date', 'status'])]
class P2ksSchedule extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
