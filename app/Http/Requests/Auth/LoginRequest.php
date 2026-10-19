<?php

namespace App\Http\Requests\Auth;

use App\Models\Division;
use App\Models\Member;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $originalEmail = $this->input('email');
        $email = Str::lower(trim($originalEmail));
        $password = $this->input('password');

        // Check if user exists (case-insensitive for SQLite)
        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        // 4. Tidak ada di keduanya -> buat data anggota baru dengan role "anggota"
        if (! $user) {
            $user = User::create([
                'name' => explode('@', $email)[0],
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'USER',
            ]);

            $user->assignRole('USER');

            Member::syncOrCreate([
                'user_id' => $user->id,
                'name' => $user->name,
                'status' => 'AKTIF',
            ]);
        } else {
            // Update email to ensure it's normalized in DB if necessary
            if ($user->email !== $email) {
                $user->email = $email;
                $user->save();
            }
        }

        // 1. Semua akun Gmail bisa login
        if (! Auth::attempt(['email' => $email, 'password' => $password], $this->boolean('remember'))) {
            // Jika login gagal (password salah untuk akun yang sudah ada), mungkin kita perlu update password
            // karena "Semua akun Gmail bisa login" dan "buat data anggota baru" mungkin bermaksud
            // SSO-like experience (tanpa validasi password ketat jika hanya simulasi).
            // Tapi karena ini form standar, kita tetap reject jika password salah.
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $loggedInUser = Auth::user();
        $emailPrefix = explode('@', $email)[0];
        $isPengurus = false;

        // Admin
        if ($loggedInUser->hasRole('ADMIN') || $loggedInUser->role === 'ADMIN' || str_contains($emailPrefix, 'admin')) {
            $loggedInUser->syncRoles(['ADMIN']);
            $isPengurus = true;
        } elseif ($email === 'pembina@gmail.com' || str_ends_with($emailPrefix, '_pembina')) {
            $loggedInUser->syncRoles(['PEMBINA']);
            $isPengurus = true;
        } elseif ($email === 'pelatih@gmail.com' || str_ends_with($emailPrefix, '_pelatih')) {
            $loggedInUser->syncRoles(['PELATIH']);
            $isPengurus = true;
        } else {
            // Pengurus: cocokkan email untuk menentukan divisinya
            // Check for _jabatan or _divisi in email
            $parts = explode('_', $emailPrefix);
            if (count($parts) > 1) {
                $jabatan = end($parts);
                $roleMap = [
                    'ketua' => 'KETUA',
                    'wakil' => 'WAKIL_KETUA',
                    'sekretaris' => 'SEKRETARIS',
                    'sekre' => 'SEKRETARIS',
                    'bendahara' => 'BENDAHARA',
                    'pdd' => 'PDD',
                    'kegiatan' => 'KEGIATAN',
                    'pkwu' => 'PKWU',
                    'konselor' => 'KONSELOR_SEBAYA',
                    'humas' => 'HUMAS',
                    'pembina' => 'PEMBINA',
                    'pelatih' => 'PELATIH',
                    'admin' => 'ADMIN',
                    'skakmatt' => 'ADMIN',
                    'anggota' => 'USER',
                    'user' => 'USER',
                ];

                if (isset($roleMap[$jabatan])) {
                    $loggedInUser->syncRoles([$roleMap[$jabatan]]);
                    $isPengurus = true;

                    // Assign division if applicable
                    $divMap = [
                        'pdd' => 'Publikasi, Dekorasi & Dokumentasi (PDD)',
                        'kegiatan' => 'Kegiatan',
                        'pkwu' => 'Prakarya & Wirausaha (PKWU)',
                        'konselor' => 'Konselor Sebaya',
                        'humas' => 'Hubungan Masyarakat',
                    ];

                    if (isset($divMap[$jabatan])) {
                        $div = Division::where('name', $divMap[$jabatan])->first();
                        if ($div) {
                            $member = Member::syncOrCreate([
                                'user_id' => $loggedInUser->id,
                                'name' => $loggedInUser->name,
                                'nisn' => $loggedInUser->nisn,
                                'kelas' => $loggedInUser->kelas,
                            ]);
                            $member->division_id = $div->id;
                            $member->save();
                        }
                    }
                }
            }
        }

        // Anggota biasa
        if (! $isPengurus && ! $loggedInUser->hasAnyRole(['KETUA', 'WAKIL_KETUA', 'SEKRETARIS', 'BENDAHARA', 'PDD', 'KEGIATAN', 'PKWU', 'KONSELOR_SEBAYA', 'HUMAS', 'PEMBINA', 'PELATIH', 'ADMIN'])) {
            $loggedInUser->syncRoles(['USER']);
            Member::syncOrCreate([
                'user_id' => $loggedInUser->id,
                'name' => $loggedInUser->name,
                'nisn' => $loggedInUser->nisn,
                'kelas' => $loggedInUser->kelas,
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
