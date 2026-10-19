<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function period()
    {
        return $this->belongsTo(OrganizationPeriod::class, 'organization_period_id');
    }

    /**
     * Get member name, falling back to linked user if offline name is null.
     */
    public function getNameAttribute(): string
    {
        return $this->attributes['name'] ?? $this->user?->name ?? 'Tanpa Nama';
    }

    /**
     * Get member NISN, falling back to linked user.
     */
    public function getNisnAttribute(): ?string
    {
        return $this->attributes['nisn'] ?? $this->user?->nisn ?? null;
    }

    /**
     * Get member phone, falling back to linked user.
     */
    public function getPhoneAttribute(): ?string
    {
        return $this->attributes['phone'] ?? $this->user?->phone ?? null;
    }

    /**
     * Find existing Member record by user ID, NISN, or Name (+ Kelas).
     */
    public static function findExisting(
        ?int $userId = null,
        ?string $nisn = null,
        ?string $name = null,
        ?string $kelas = null
    ): ?self {
        // 1. By user_id
        if ($userId) {
            $member = static::where('user_id', $userId)->first();
            if ($member) {
                return $member;
            }
        }

        // 2. By NISN
        $cleanNisn = $nisn ? trim($nisn) : null;
        if ($cleanNisn) {
            $member = static::where('nisn', $cleanNisn)
                ->orWhereHas('user', function ($q) use ($cleanNisn) {
                    $q->where('nisn', $cleanNisn);
                })
                ->first();
            if ($member) {
                return $member;
            }
        }

        // 3. By Name + Kelas
        $cleanName = $name ? strtolower(trim($name)) : null;
        $cleanKelas = $kelas ? strtoupper(trim($kelas)) : null;

        if ($cleanName && $cleanKelas) {
            $member = static::where(function ($q) use ($cleanName) {
                $q->whereRaw('LOWER(TRIM(name)) = ?', [$cleanName])
                    ->orWhereHas('user', function ($uq) use ($cleanName) {
                        $uq->whereRaw('LOWER(TRIM(name)) = ?', [$cleanName]);
                    });
            })->where(function ($q) use ($cleanKelas) {
                $q->whereRaw('UPPER(TRIM(kelas)) = ?', [$cleanKelas])
                    ->orWhereHas('user', function ($uq) use ($cleanKelas) {
                        $uq->whereRaw('UPPER(TRIM(kelas)) = ?', [$cleanKelas]);
                    });
            })->first();

            if ($member) {
                return $member;
            }
        }

        // 4. By exact Name alone
        if ($cleanName) {
            $member = static::whereRaw('LOWER(TRIM(name)) = ?', [$cleanName])
                ->orWhereHas('user', function ($uq) use ($cleanName) {
                    $uq->whereRaw('LOWER(TRIM(name)) = ?', [$cleanName]);
                })
                ->first();
            if ($member) {
                return $member;
            }
        }

        return null;
    }

    /**
     * Sync or create Member record ensuring no duplicates exist.
     *
     * @param  array<string, mixed>  $attributes
     */
    public static function syncOrCreate(array $attributes): self
    {
        $userId = $attributes['user_id'] ?? null;
        $nisn = $attributes['nisn'] ?? null;
        $name = $attributes['name'] ?? null;
        $kelas = $attributes['kelas'] ?? null;

        $existing = static::findExisting(
            $userId ? (int) $userId : null,
            $nisn ? (string) $nisn : null,
            $name ? (string) $name : null,
            $kelas ? (string) $kelas : null
        );

        if ($existing) {
            $updateData = [];

            if (! empty($userId) && (! $existing->user_id || $existing->user_id !== (int) $userId)) {
                $updateData['user_id'] = $userId;
            }
            if (! empty($name) && (empty($existing->attributes['name']) || $existing->attributes['name'] === 'Tanpa Nama')) {
                $updateData['name'] = $name;
            }
            if (! empty($nisn) && empty($existing->attributes['nisn'])) {
                $updateData['nisn'] = $nisn;
            }
            if (! empty($kelas) && empty($existing->attributes['kelas'])) {
                $updateData['kelas'] = $kelas;
            }
            if (! empty($attributes['phone']) && empty($existing->attributes['phone'])) {
                $updateData['phone'] = $attributes['phone'];
            }
            if (! empty($attributes['status'])) {
                $updateData['status'] = $attributes['status'];
            }
            if (! empty($attributes['organization_period_id']) && empty($existing->organization_period_id)) {
                $updateData['organization_period_id'] = $attributes['organization_period_id'];
            }

            if (! empty($updateData)) {
                $existing->update($updateData);
            }

            return $existing;
        }

        return static::create($attributes);
    }
}
