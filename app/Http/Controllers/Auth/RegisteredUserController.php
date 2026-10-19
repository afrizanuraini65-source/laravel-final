<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Models\MasterMember;
use App\Models\Member;
use App\Models\MemberApplication;
use App\Models\RegistrationSetting;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        $settings = RegistrationSetting::current();
        if (! $settings->isAccountOpen()) {
            return view('auth.registration-closed', compact('settings'));
        }

        $kelasList = [
            'X AKL 1', 'X AKL 2', 'X AKL 3', 'X AKL 4', 'X AKL 5',
            'X DKV 1', 'X DKV 2',
            'X MPLB 1', 'X MPLB 2', 'X MPLB 3',
            'X PM 1', 'X PM 2', 'X PM 3',
            'X PPLG 1', 'X PPLG 2', 'X PPLG 3',
            'X TF 1', 'X TF 2', 'X TJKT 1', 'X TJKT 2',
            'XI AKL 1', 'XI AKL 2', 'XI AKL 3', 'XI AKL 4',
            'XI DKV 1', 'XI DKV 2',
            'XI MPLB 1', 'XI MPLB 2', 'XI MPLB 3',
            'XI PM 1', 'XI PM 2', 'XI PM 3',
            'XI PPLG 1', 'XI PPLG 2', 'XI PPLG 3',
            'XI TF 1', 'XI TF 2', 'XI TJKT 1', 'XI TJKT 2',
            'XII AKL 1', 'XII AKL 2', 'XII AKL 3', 'XII AKL 4',
            'XII DKV 1', 'XII DKV 2',
            'XII MPLB 1', 'XII MPLB 2', 'XII MPLB 3',
            'XII PM 1', 'XII PM 2', 'XII PM 3',
            'XII PPLG 1', 'XII PPLG 2', 'XII PPLG 3',
            'XII TF 1', 'XII TF 2', 'XII TJKT 1', 'XII TJKT 2',
        ];

        return view('auth.register', compact('kelasList', 'settings'));
    }

    /**
     * Send email verification code (OTP).
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $settings = RegistrationSetting::current();
        if (! $settings->isAccountOpen()) {
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran akun baru saat ini sedang ditutup oleh pengurus.',
            ], 403);
        }

        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'name' => 'nullable|string|max:255',
        ], [
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
        ]);

        $code = (string) rand(100000, 999999);
        $name = $request->name ?: 'Calon Anggota';

        // Save to session
        session([
            'reg_otp' => $code,
            'reg_otp_email' => $request->email,
            'reg_otp_expires_at' => now()->addMinutes(15),
        ]);

        try {
            Mail::to($request->email)->send(new VerificationCodeMail($code, $name));
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email OTP verifikasi: '.$e->getMessage());
        }

        $responseData = [
            'success' => true,
            'message' => 'Kode verifikasi 6 digit telah dikirim ke inbox email '.$request->email.'. Silakan periksa kotak masuk atau folder spam email Anda.',
        ];

        if (app()->environment('local')) {
            $responseData['dev_code'] = $code;
            $responseData['message'] .= " (Kode Demo Presentasi: {$code})";
        }

        return response()->json($responseData);
    }

    /**
     * Handle an incoming registration request with automated 5-condition matching.
     */
    public function store(Request $request): RedirectResponse
    {
        $settings = RegistrationSetting::current();
        if (! $settings->isAccountOpen()) {
            return redirect()->route('register')->withErrors([
                'email' => 'Pendaftaran akun saat ini sedang ditutup oleh pengurus.',
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'kelas' => 'required|string|max:50',
            'nisn' => 'nullable|string|max:20',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'phone' => 'required|string|max:20',
            'verification_code' => 'required|string|size:6',
        ], [
            'verification_code.required' => 'Kode verifikasi email wajib diisi.',
            'verification_code.size' => 'Kode verifikasi harus terdiri dari 6 digit.',
            'birth_place.required' => 'Tempat lahir wajib diisi.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'kelas.required' => 'Kelas wajib dipilih.',
            'phone.required' => 'Nomor WhatsApp / HP wajib diisi.',
        ]);

        // Cek OTP verifikasi
        $savedOtp = session('reg_otp');
        $savedEmail = session('reg_otp_email');
        $expiresAt = session('reg_otp_expires_at');

        if (! $savedOtp || $savedOtp !== $request->verification_code || $savedEmail !== $request->email) {
            return back()->withInput()->withErrors([
                'verification_code' => 'Kode verifikasi salah atau belum diminta untuk email ini.',
            ]);
        }

        if ($expiresAt && now()->greaterThan($expiresAt)) {
            return back()->withInput()->withErrors([
                'verification_code' => 'Kode verifikasi telah kadaluarsa. Silakan minta kode baru.',
            ]);
        }

        // Format data siswa
        $formattedName = ucwords(strtolower(trim($request->name)));
        $formattedBirthPlace = ucwords(strtolower(trim($request->birth_place)));
        $formattedKelas = strtoupper(trim($request->kelas));
        $formattedPhone = trim($request->phone);
        $formattedEmail = strtolower(trim($request->email));
        $cleanNisn = $request->nisn ? trim($request->nisn) : null;
        $cleanName = strtolower(trim($request->name));

        // 5-KONDISI PENCOCOKAN OTOMATIS BACKEND (Bagian 10.D)
        $matchStatus = 'TIDAK_DITEMUKAN';
        $membershipStatus = 'TIDAK_DITEMUKAN';
        $matchedName = null;
        $matchedKelas = null;
        $matchedNisn = null;
        $matchNotes = null;
        $matchedAppId = null;
        $matchedMasterId = null;

        // 1. Cek di MemberApplication (Formulir Pendaftaran Anggota Baru)
        $appCandidates = collect();

        if ($cleanNisn) {
            $appCandidates = MemberApplication::where('nisn', $cleanNisn)->get();
        }

        if ($appCandidates->isEmpty()) {
            $appCandidates = MemberApplication::where('email', $formattedEmail)->get();
        }

        if ($appCandidates->isEmpty()) {
            $appCandidates = MemberApplication::whereRaw('LOWER(TRIM(name)) = ?', [$cleanName])
                ->whereRaw('UPPER(TRIM(kelas)) = ?', [$formattedKelas])
                ->get();
        }

        if ($appCandidates->count() > 1) {
            // Kondisi 4: Data ambigu atau ganda
            $matchStatus = 'AMBIGU';
            $membershipStatus = 'PERLU_PEMERIKSAAN';
            $matchNotes = 'Ditemukan lebih dari 1 data pendaftaran anggota yang berpotensi cocok. Ditandai untuk pemeriksaan manual admin.';
        } elseif ($appCandidates->count() === 1) {
            $app = $appCandidates->first();

            // Kondisi 5: Data keanggotaan sudah terhubung dengan akun lain
            if ($app->user_id && User::where('id', $app->user_id)->exists()) {
                $matchStatus = 'SUDAH_TERHUBUNG';
                $membershipStatus = 'SUDAH_TERHUBUNG';
                $matchedName = $app->name;
                $matchedKelas = $app->kelas;
                $matchedNisn = $app->nisn;
                $matchNotes = "Data pendaftaran anggota {$app->name} sudah pernah dihubungkan ke akun user lain (User #{$app->user_id}).";
            } else {
                $matchedAppId = $app->id;
                $matchedName = $app->name;
                $matchedKelas = $app->kelas;
                $matchedNisn = $app->nisn;

                if ($app->status === 'DISETUJUI') {
                    // Kondisi 1: Data cocok dan keanggotaan sudah disetujui
                    $matchStatus = 'TERVERIFIKASI';
                    $membershipStatus = 'TERVERIFIKASI';
                    $matchNotes = "Data siswa terverifikasi secara otomatis dengan formulir pendaftaran anggota yang telah disetujui (#{$app->id}).";
                } elseif (in_array($app->status, ['MENUNGGU_PERSETUJUAN', 'MENUNGGU_VALIDASI', 'PERLU_PEMERIKSAAN'])) {
                    // Kondisi 3: Data pendaftaran masih menunggu verifikasi
                    $matchStatus = 'MENUNGGU_VERIFIKASI_ANGGOTA';
                    $membershipStatus = 'MENUNGGU';
                    $matchNotes = 'Siswa sudah mengajukan pendaftaran anggota, namun status keanggotaan masih menunggu persetujuan pengurus.';
                } elseif ($app->status === 'DITOLAK') {
                    $matchStatus = 'DITOLAK_ANGGOTA';
                    $membershipStatus = 'DITOLAK';
                    $matchNotes = 'Pendaftaran anggota siswa berstatus Ditolak / Perlu Revisi oleh pengurus.';
                }
            }
        }

        // 2. Jika belum ditemukan di MemberApplication, cari di MasterMember (Buku Induk)
        if ($matchStatus === 'TIDAK_DITEMUKAN') {
            $masterCandidates = collect();

            if ($cleanNisn) {
                $masterCandidates = MasterMember::where('nisn', $cleanNisn)->get();
            }

            if ($masterCandidates->isEmpty()) {
                $masterCandidates = MasterMember::whereRaw('LOWER(TRIM(name)) = ?', [$cleanName])
                    ->whereRaw('UPPER(TRIM(kelas)) = ?', [$formattedKelas])
                    ->get();
            }

            if ($masterCandidates->count() > 1) {
                // Kondisi 4: Data ambigu atau ganda
                $matchStatus = 'AMBIGU';
                $membershipStatus = 'PERLU_PEMERIKSAAN';
                $matchNotes = 'Ditemukan lebih dari 1 catatan siswa dengan identitas serupa di Buku Induk. Perlu pemeriksaan manual admin.';
            } elseif ($masterCandidates->count() === 1) {
                $master = $masterCandidates->first();

                if ($master->user_id && User::where('id', $master->user_id)->exists()) {
                    // Kondisi 5: Sudah terhubung
                    $matchStatus = 'SUDAH_TERHUBUNG';
                    $membershipStatus = 'SUDAH_TERHUBUNG';
                    $matchedName = $master->name;
                    $matchedKelas = $master->kelas;
                    $matchedNisn = $master->nisn;
                    $matchNotes = "Data Buku Induk {$master->name} ({$master->kelas}) sudah terhubung dengan akun lain (User #{$master->user_id}).";
                } else {
                    // Kondisi 1: Cocok dan terverifikasi di Buku Induk
                    $matchStatus = 'TERVERIFIKASI';
                    $membershipStatus = 'TERVERIFIKASI';
                    $matchedMasterId = $master->id;
                    $matchedName = $master->name;
                    $matchedKelas = $master->kelas;
                    $matchedNisn = $master->nisn;
                    $matchNotes = "Data siswa terverifikasi secara otomatis dari Buku Induk resmi ({$master->name} - {$master->kelas}).";
                }
            } else {
                // Kondisi 2: Data tidak ditemukan
                $matchStatus = 'TIDAK_DITEMUKAN';
                $membershipStatus = 'TIDAK_DITEMUKAN';
                $matchNotes = 'Data pendaftaran anggota belum ditemukan atau belum memenuhi syarat dalam sistem. Akun tidak memperoleh akses anggota.';
            }
        }

        // Buat user dengan status akun PENDING (Tahap 3 menunggu approval admin)
        $user = User::create([
            'name' => $formattedName,
            'email' => $formattedEmail,
            'password' => Hash::make($request->password),
            'kelas' => $formattedKelas,
            'nisn' => $cleanNisn,
            'birth_place' => $formattedBirthPlace,
            'birth_date' => $request->birth_date,
            'phone' => $formattedPhone,
            'role' => 'USER',
            'status_verifikasi' => 'PENDING',
            'match_status' => $matchStatus,
            'matched_name' => $matchedName,
            'matched_kelas' => $matchedKelas,
            'matched_nisn' => $matchedNisn,
            'membership_status' => $membershipStatus,
            'match_notes' => $matchNotes,
            'matched_application_id' => $matchedAppId,
            'matched_master_id' => $matchedMasterId,
        ]);

        $user->assignRole('USER');

        // Hubungkan foreign key bila ada match
        if ($matchedAppId) {
            MemberApplication::where('id', $matchedAppId)->update(['user_id' => $user->id]);
        }
        if ($matchedMasterId) {
            MasterMember::where('id', $matchedMasterId)->update(['user_id' => $user->id]);
        }

        // Hubungkan data Member bila siswa sudah terdaftar sebelumnya (misal lewat calon anggota yang disetujui)
        $existingMember = Member::findExisting(null, $cleanNisn, $formattedName, $formattedKelas);
        if ($existingMember && ! $existingMember->user_id) {
            $existingMember->update(['user_id' => $user->id]);
        }

        // Bersihkan session OTP
        session()->forget(['reg_otp', 'reg_otp_email', 'reg_otp_expires_at']);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.pending');
    }
}
