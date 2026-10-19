<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMember extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_registered' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Find existing MasterMember record by NISN, user_id, or Name (+ Kelas).
     */
    public static function findExisting(
        ?string $nisn = null,
        ?string $name = null,
        ?string $kelas = null,
        ?int $userId = null
    ): ?self {
        // 1. By user_id
        if ($userId) {
            $master = static::where('user_id', $userId)->first();
            if ($master) {
                return $master;
            }
        }

        // 2. By NISN
        $cleanNisn = $nisn ? trim($nisn) : null;
        if ($cleanNisn) {
            $master = static::where('nisn', $cleanNisn)->first();
            if ($master) {
                return $master;
            }
        }

        // 3. By Name + Kelas
        $cleanName = $name ? strtolower(trim($name)) : null;
        $cleanKelas = $kelas ? strtoupper(trim($kelas)) : null;

        if ($cleanName && $cleanKelas) {
            $master = static::whereRaw('LOWER(TRIM(name)) = ?', [$cleanName])
                ->whereRaw('UPPER(TRIM(kelas)) = ?', [$cleanKelas])
                ->first();
            if ($master) {
                return $master;
            }
        }

        // 4. By exact Name alone
        if ($cleanName) {
            $master = static::whereRaw('LOWER(TRIM(name)) = ?', [$cleanName])->first();
            if ($master) {
                return $master;
            }
        }

        return null;
    }

    /**
     * Sync or create MasterMember record ensuring no duplicates exist.
     *
     * @param  array<string, mixed>  $attributes
     */
    public static function syncOrCreate(array $attributes): self
    {
        $nisn = $attributes['nisn'] ?? null;
        $name = $attributes['name'] ?? null;
        $kelas = $attributes['kelas'] ?? null;
        $userId = $attributes['user_id'] ?? null;

        $existing = static::findExisting(
            $nisn ? (string) $nisn : null,
            $name ? (string) $name : null,
            $kelas ? (string) $kelas : null,
            $userId ? (int) $userId : null
        );

        if ($existing) {
            $updateData = [];

            if (! empty($userId) && (! $existing->user_id || $existing->user_id !== (int) $userId)) {
                $updateData['user_id'] = $userId;
                $updateData['is_registered'] = true;
            }
            if (! empty($name) && empty($existing->name)) {
                $updateData['name'] = $name;
            }
            if (! empty($nisn) && empty($existing->nisn)) {
                $updateData['nisn'] = $nisn;
            }
            if (! empty($kelas) && empty($existing->kelas)) {
                $updateData['kelas'] = $kelas;
            }
            if (! empty($attributes['no_hp']) && empty($existing->no_hp)) {
                $updateData['no_hp'] = $attributes['no_hp'];
            }
            if (! empty($attributes['tempat_lahir']) && empty($existing->tempat_lahir)) {
                $updateData['tempat_lahir'] = $attributes['tempat_lahir'];
            }
            if (! empty($attributes['tanggal_lahir']) && empty($existing->tanggal_lahir)) {
                $updateData['tanggal_lahir'] = $attributes['tanggal_lahir'];
            }
            if (isset($attributes['is_registered']) && ! $existing->is_registered) {
                $updateData['is_registered'] = $attributes['is_registered'];
            }

            if (! empty($updateData)) {
                $existing->update($updateData);
            }

            return $existing;
        }

        return static::create($attributes);
    }
}
