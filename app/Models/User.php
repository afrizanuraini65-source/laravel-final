<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'name', 'email', 'password', 'phone', 'nisn', 'avatar', 'role',
    'birth_place', 'birth_date', 'kelas', 'status_verifikasi',
    'match_status', 'matched_name', 'matched_kelas', 'matched_nisn',
    'membership_status', 'match_notes', 'matched_application_id', 'matched_master_id',
    'rejection_reason', 'email_verification_code', 'email_verification_expires_at',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function reregistration()
    {
        return $this->hasOne(Reregistration::class);
    }

    public function masterMember()
    {
        return $this->hasOne(MasterMember::class);
    }

    public function memberApplication()
    {
        return $this->hasOne(MemberApplication::class)->latestOfMany();
    }

    public function memberApplications()
    {
        return $this->hasMany(MemberApplication::class);
    }

    public function matchedApplication()
    {
        return $this->belongsTo(MemberApplication::class, 'matched_application_id');
    }

    public function matchedMaster()
    {
        return $this->belongsTo(MasterMember::class, 'matched_master_id');
    }
}
