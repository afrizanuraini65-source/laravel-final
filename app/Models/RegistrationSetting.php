<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_account_registration_open' => 'boolean',
        'account_registration_start' => 'datetime',
        'account_registration_end' => 'datetime',
        'is_member_registration_open' => 'boolean',
        'member_registration_start' => 'datetime',
        'member_registration_end' => 'datetime',
    ];

    /**
     * Get or initialize active singleton settings.
     */
    public static function current(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'is_account_registration_open' => true,
                'account_registration_start' => null,
                'account_registration_end' => null,
                'is_member_registration_open' => true,
                'member_registration_start' => null,
                'member_registration_end' => null,
                'announcement_title' => 'Penerimaan Anggota Baru PIK-R SKAKMATT Tahun Ajaran 2025/2026',
                'announcement_description' => 'Mari bergabung bersama wadah tumbuh generasi berencana SMK Negeri 1 Purwokerto! Pelajari konseling sebaya, kependudukan, kepemimpinan, dan kesehatan reproduksi remaja.',
                'requirements' => "1. Siswa aktif SMK Negeri 1 Purwokerto kelas X atau XI.\n2. Berkomitmen aktif dalam kegiatan edukasi dan konseling sebaya.\n3. Memiliki semangat berkarya, belajar, dan berorganisasi.",
            ]
        );
    }

    /**
     * Check if account registration is currently open in Asia/Jakarta timezone.
     */
    public function isAccountOpen(): bool
    {
        if (! $this->is_account_registration_open) {
            return false;
        }

        $now = Carbon::now('Asia/Jakarta');

        if ($this->account_registration_start && $now->lessThan($this->account_registration_start->timezone('Asia/Jakarta'))) {
            return false;
        }

        if ($this->account_registration_end && $now->greaterThan($this->account_registration_end->timezone('Asia/Jakarta'))) {
            return false;
        }

        return true;
    }

    /**
     * Check if member acceptance is currently open in Asia/Jakarta timezone.
     */
    public function isMemberOpen(): bool
    {
        if (! $this->is_member_registration_open) {
            return false;
        }

        $now = Carbon::now('Asia/Jakarta');

        if ($this->member_registration_start && $now->lessThan($this->member_registration_start->timezone('Asia/Jakarta'))) {
            return false;
        }

        if ($this->member_registration_end && $now->greaterThan($this->member_registration_end->timezone('Asia/Jakarta'))) {
            return false;
        }

        return true;
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }
}
