<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'description',
        'proof_path',
        'category_id',
        'period_id',
    ];
}
