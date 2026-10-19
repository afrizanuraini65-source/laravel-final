<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashTransaction extends Model
{
    protected $guarded = [];

    public static function verifiedIncomeTotal(): float
    {
        $manualIncome = self::where('type', 'INCOME')->whereNull('payment_id')->sum('amount');
        $verifiedPayments = Payment::where('status', 'LUNAS')->sum('amount_paid');

        return (float) $manualIncome + (float) $verifiedPayments;
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FinancialCategory::class, 'category_id');
    }
}
