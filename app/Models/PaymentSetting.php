<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'bank_name',
        'account_holder',
        'account_number',
        'wallet_provider',
        'wallet_account_holder',
        'wallet_account_number',
        'instructions',
    ];

    public static function current(): self
    {
        return self::unguarded(fn () => self::firstOrCreate(['id' => 1]));
    }

    public function hasBankAccount(): bool
    {
        return filled($this->bank_name) && filled($this->account_holder) && filled($this->account_number);
    }

    public function hasWalletAccount(): bool
    {
        return filled($this->wallet_provider)
            && filled($this->wallet_account_holder)
            && filled($this->wallet_account_number);
    }
}
