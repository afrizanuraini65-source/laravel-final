<?php

namespace App\Http\Controllers;

use App\Models\MasterMember;
use App\Models\Member;
use App\Models\MemberApplication;
use App\Models\RegistrationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberRegistrationController extends Controller
{
    /**
     * Show member application form or status.
     */
    public function create()
    {
        $settings = RegistrationSetting::current();
        $isMemberOpen = $settings->isMemberOpen();
        $isAccountOpen = $settings->isAccountOpen();

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

        $jurusanList = [
            'Akuntansi dan Keuangan Lembaga (AKL)',
            'Desain Komunikasi Visual (DKV)',
            'Manajemen Perkantoran dan Layanan Bisnis (MPLB)',
            'Pemasaran (PM)',
            'Pengembangan Perangkat Lunak dan Gim (PPLG)',
            'Teknik Furnitur (TF)',
            'Teknik Jaringan Komputer dan Telekomunikasi (TJKT)',
        ];

        $user = Auth::user();
        $existingMember = $user ? Member::where('user_id', $user->id)->first() : null;

        $sessionAppId = session('submitted_application_id');
        $existingApplication = null;
        if ($user) {
            $existingApplication = MemberApplication::where('user_id', $user->id)->latest()->first();
        } elseif ($sessionAppId) {
            $existingApplication = MemberApplication::find($sessionAppId);
        }

        return view('pendaftaran.anggota', compact(
            'settings',
            'isMemberOpen',
            'isAccountOpen',
            'kelasList',
            'jurusanList',
            'user',
            'existingMember',
            'existingApplication'
        ));
    }

    /**
     * Submit new member application (Guests & Logged in users).
     */
    public function store(Request $request)
    {
        $settings = RegistrationSetting::current();
        if (! $settings->isMemberOpen()) {
            return redirect()->route('pendaftaran-anggota.create')
                ->with('error', 'Penerimaan anggota baru saat ini sedang ditutup oleh pengurus.');
        }

        $user = Auth::user();

        // Prevent duplicate if already active member
        if ($user && Member::where('user_id', $user->id)->where('status', 'AKTIF')->exists()) {
            return redirect()->route('pendaftaran-anggota.create')
                ->with('error', 'Anda sudah tercatat sebagai anggota resmi aktif PIK-R SKAKMATT.');
        }

        if ($user) {
            $request->merge([
                'phone' => $request->filled('phone') ? $request->phone : ($user->phone ?: '081234567890'),
                'email' => $request->filled('email') ? $request->email : $user->email,
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'jurusan' => 'nullable|string|max:100',
            'nisn' => 'nullable|string|max:20',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'reason' => 'required|string|min:15|max:1000',
            'data_consent' => 'accepted',
        ], [
            'name.required' => 'Nama lengkap siswa wajib diisi.',
            'kelas.required' => 'Kelas wajib dipilih.',
            'phone.required' => 'Nomor HP / WhatsApp wajib diisi.',
            'email.required' => 'Alamat email aktif wajib diisi.',
            'reason.required' => 'Alasan ingin bergabung wajib diisi.',
            'reason.min' => 'Mohon jelaskan alasan Anda secara singkat dan jelas (minimal 15 karakter).',
            'data_consent.accepted' => 'Anda harus menyetujui penggunaan data untuk proses administrasi keanggotaan.',
        ]);

        $formattedName = ucwords(strtolower(trim($request->name)));
        $formattedKelas = strtoupper(trim($request->kelas));
        $cleanNisn = $request->nisn ? trim($request->nisn) : null;
        $cleanPhone = trim($request->phone);
        $cleanEmail = strtolower(trim($request->email));
        $cleanJurusan = $request->jurusan ? trim($request->jurusan) : null;

        // Perform student identity verification against MasterMember (Buku Induk)
        $masterCandidate = null;
        $validationStatus = 'PERLU_PEMERIKSAAN';
        $applicationStatus = 'MENUNGGU_PERSETUJUAN';
        $validationNotes = '';

        if ($cleanNisn) {
            $masterCandidate = MasterMember::where('nisn', $cleanNisn)->first();
        }

        if (! $masterCandidate) {
            $cleanName = strtolower(trim($request->name));
            $masterCandidate = MasterMember::whereRaw('LOWER(TRIM(name)) = ?', [$cleanName])
                ->orWhere('name', 'LIKE', '%'.trim($request->name).'%')
                ->first();
        }

        if ($masterCandidate) {
            if ($masterCandidate->nisn && $cleanNisn && $masterCandidate->nisn === $cleanNisn) {
                $validationStatus = 'VALID_AUTOMATIS';
                $applicationStatus = 'MENUNGGU_PERSETUJUAN';
                $validationNotes = "Identitas valid! Data cocok dengan Buku Induk siswa ({$masterCandidate->name} - NISN: {$masterCandidate->nisn}).";
            } elseif (strtoupper($masterCandidate->kelas) === $formattedKelas) {
                $validationStatus = 'VALID_AUTOMATIS';
                $applicationStatus = 'MENUNGGU_PERSETUJUAN';
                $validationNotes = "Identitas valid! Nama dan kelas cocok dengan Buku Induk ({$masterCandidate->name} - {$masterCandidate->kelas}).";
            } else {
                $validationStatus = 'PERLU_PEMERIKSAAN';
                $applicationStatus = 'PERLU_PEMERIKSAAN';
                $validationNotes = "Nama siswa ditemukan di Buku Induk, namun kelas berbeda (Buku Induk: {$masterCandidate->kelas}, Diajukan: {$formattedKelas}). Perlu konfirmasi pengurus.";
            }
        } else {
            $validationStatus = 'TIDAK_DITEMUKAN';
            $applicationStatus = 'PERLU_PEMERIKSAAN';
            $validationNotes = 'Data siswa belum tercantum dalam Buku Induk awal. Pengajuan ditandai untuk pemeriksaan manual oleh Sekretaris/Ketua.';
        }

        // Create or update member application record
        $application = MemberApplication::create([
            'user_id' => $user ? $user->id : null,
            'name' => $formattedName,
            'kelas' => $formattedKelas,
            'jurusan' => $cleanJurusan,
            'nisn' => $cleanNisn,
            'phone' => $cleanPhone,
            'email' => $cleanEmail,
            'reason' => trim($request->reason),
            'data_consent' => true,
            'validation_status' => $validationStatus,
            'validation_notes' => $validationNotes,
            'status' => $applicationStatus,
            'rejection_reason' => null,
        ]);

        session(['submitted_application_id' => $application->id]);

        if ($user) {
            $user->update([
                'name' => $formattedName,
                'kelas' => $formattedKelas,
                'phone' => $cleanPhone ?: $user->phone,
                'nisn' => $cleanNisn ?: $user->nisn,
            ]);
        }

        return redirect()->route('pendaftaran-anggota.create')
            ->with('success', "Permohonan pendaftaran anggota atas nama {$formattedName} ({$formattedKelas}) berhasil diajukan! Silakan tunggu verifikasi pengurus sebelum membuat akun siswa.");
    }
}
