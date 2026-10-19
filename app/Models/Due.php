<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Due extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function period(): BelongsTo
    {
        return $this->belongsTo(OrganizationPeriod::class, 'organization_period_id');
    }

    public function targetDivision(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'target_division_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function appliesTo(User $user): bool
    {
        $member = $user->member;

        return $member !== null && $this->targetsMember($member);
    }

    public function targetsMember(Member $member): bool
    {
        if ($member->status !== 'AKTIF') {
            return false;
        }

        if ($this->target_type === 'DIVISI') {
            return $member->division_id !== null
                && (int) $this->target_division_id === (int) $member->division_id;
        }

        if ($this->target_type === 'INTI') {
            return $member->user?->hasRole(['KETUA', 'WAKIL_KETUA', 'SEKRETARIS', 'BENDAHARA']) ?? false;
        }

        return true;
    }
}
