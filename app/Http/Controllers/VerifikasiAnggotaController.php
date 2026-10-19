<?php

namespace App\Http\Controllers;

use App\Models\MasterMember;
use App\Models\Member;
use App\Models\MemberApplication;
use App\Models\OrganizationPeriod;
use App\Models\User;
use App\Services\ExcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikasiAnggotaController extends Controller
{
    /**
     * Check authorization (ADMIN, KETUA, SEKRETARIS).
     */
    protected function authorizeAccess(): void
    {
        if (! Auth::user()->hasRole(['ADMIN', 'KETUA', 'SEKRETARIS'])) {
            abort(403, 'Akses khusus Ketua, Sekretaris, dan Admin.');
        }
    }

    /**
     * Display verification dashboard.
     */
    public function index(Request $request)
    {
        $this->authorizeAccess();

        // 1. Query Pengajuan Keanggotaan Baru (Member Applications)
        $appQuery = MemberApplication::with(['user', 'reviewer']);
        if ($request->filled('app_search')) {
            $appQuery->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->app_search.'%')
                    ->orWhere('kelas', 'like', '%'.$request->app_search.'%')
                    ->orWhere('nisn', 'like', '%'.$request->app_search.'%');
            });
        }
        if ($request->filled('app_status')) {
            $appQuery->where('status', $request->app_status);
        }

        $applications = $appQuery->orderByRaw("FIELD(status, 'MENUNGGU_VALIDASI', 'PERLU_PEMERIKSAAN', 'MENUNGGU_PERSETUJUAN', 'DISETUJUI', 'DITOLAK')")
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'app_page')
            ->withQueryString();

        $totalAppPending = MemberApplication::whereIn('status', ['MENUNGGU_VALIDASI', 'MENUNGGU_PERSETUJUAN'])->count();
        $totalAppReview = MemberApplication::where('status', 'PERLU_PEMERIKSAAN')->count();
        $totalAppApproved = MemberApplication::where('status', 'DISETUJUI')->count();
        $totalAppRejected = MemberApplication::where('status', 'DITOLAK')->count();

        // 2. Query Pendaftar Akun Pengguna (Legacy User Accounts)
        $pendaftarQuery = User::whereIn('status_verifikasi', ['PENDING', 'APPROVED', 'REJECTED'])
            ->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['ADMIN', 'PEMBINA', 'PELATIH']);
            });

        if ($request->filled('search')) {
            $pendaftarQuery->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('kelas', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $pendaftarQuery->where('status_verifikasi', $request->status);
        }

        $pendaftar = $pendaftarQuery->orderByRaw("FIELD(status_verifikasi, 'PENDING', 'APPROVED', 'REJECTED')")
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'pendaftar_page')
            ->withQueryString();

        $totalPending = User::where('status_verifikasi', 'PENDING')->count();
        $totalApproved = User::where('status_verifikasi', 'APPROVED')->count();
        $totalRejected = User::where('status_verifikasi', 'REJECTED')->count();

        // 3. Query Master Siswa PIK-R (Buku Induk)
        $masterQuery = MasterMember::with('user');
        if ($request->filled('master_search')) {
            $masterQuery->where('name', 'like', '%'.$request->master_search.'%')
                ->orWhere('kelas', 'like', '%'.$request->master_search.'%')
                ->orWhere('nisn', 'like', '%'.$request->master_search.'%');
        }
        $masterMembers = $masterQuery->orderBy('is_registered', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(15, ['*'], 'master_page')
            ->withQueryString();

        $totalMaster = MasterMember::count();
        $masterRegistered = MasterMember::where('is_registered', true)->count();
        $masterUnregistered = MasterMember::where('is_registered', false)->count();

        return view('verifikasi.index', compact(
            'applications',
            'totalAppPending',
            'totalAppReview',
            'totalAppApproved',
            'totalAppRejected',
            'pendaftar',
            'totalPending',
            'totalApproved',
            'totalRejected',
            'masterMembers',
            'totalMaster',
            'masterRegistered',
            'masterUnregistered'
        ));
    }

    /**
     * Approve member application and activate Member record.
     */
    public function approveApplication(MemberApplication $application)
    {
        $this->authorizeAccess();

        $period = OrganizationPeriod::where('is_active', true)->first();
        if (! $period) {
            $period = OrganizationPeriod::firstOrCreate(
                ['name' => '2025/2026'],
                ['start_date' => '2025-07-01', 'end_date' => '2026-06-30', 'is_active' => true]
            );
        }

        // Tautkan user jika belum terhubung tetapi akun user sudah pernah didaftarkan
        $userId = $application->user_id;
        if (! $userId) {
            $user = null;
            if ($application->email) {
                $user = User::where('email', strtolower(trim($application->email)))->first();
            }
            if (! $user && $application->nisn) {
                $user = User::where('nisn', trim($application->nisn))->first();
            }
            if (! $user) {
                $cleanName = strtolower(trim($application->name));
                $user = User::whereRaw('LOWER(TRIM(name)) = ?', [$cleanName])->first();
            }
            if ($user) {
                $userId = $user->id;
            }
        }

        // Otomatis masukkan ke Data Anggota (Member) tanpa duplikasi
        Member::syncOrCreate([
            'user_id' => $userId,
            'name' => $application->name,
            'nisn' => $application->nisn,
            'kelas' => $application->kelas,
            'phone' => $application->phone,
            'organization_period_id' => $period->id,
            'status' => 'AKTIF',
        ]);

        // Update application
        $application->update([
            'status' => 'DISETUJUI',
            'user_id' => $userId,
            'rejection_reason' => null,
            'reviewed_by_user_id' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Otomatis masukkan / sinkronkan ke Buku Induk (Master Data Siswa)
        // Jika belum memiliki akun login, otomatis berstatus "Belum Akun" (is_registered = false)
        // Jika sudah memiliki akun user, otomatis berstatus "Terdaftar" (is_registered = true)
        MasterMember::syncOrCreate([
            'nisn' => $application->nisn,
            'name' => $application->name,
            'kelas' => $application->kelas,
            'no_hp' => $application->phone,
            'user_id' => $userId,
            'is_registered' => ! empty($userId),
        ]);

        return redirect()->back()->with('success', "Permohonan keanggotaan {$application->name} ({$application->kelas}) berhasil disetujui, masuk ke Data Anggota resmi & Buku Induk!");
    }

    /**
     * Reject member application with reason.
     */
    public function rejectApplication(Request $request, MemberApplication $application)
    {
        $this->authorizeAccess();

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $application->update([
            'status' => 'DITOLAK',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by_user_id' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->back()->with('success', "Permohonan keanggotaan {$application->name} telah ditolak.");
    }

    /**
     * Request data revision for member application.
     */
    public function requestRevisionApplication(Request $request, MemberApplication $application)
    {
        $this->authorizeAccess();

        $request->validate([
            'notes' => 'required|string|max:500',
        ]);

        $application->update([
            'status' => 'PERLU_PEMERIKSAAN',
            'validation_notes' => $request->notes,
            'reviewed_by_user_id' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->back()->with('success', "Catatan pemeriksaan manual berhasil dikirim kepada {$application->name}.");
    }

    /**
     * Approve user account registration (Tahap 3).
     */
    public function approve(User $user)
    {
        $this->authorizeAccess();

        $period = OrganizationPeriod::where('is_active', true)->first();
        if (! $period) {
            $period = OrganizationPeriod::firstOrCreate(
                ['name' => '2025/2026'],
                ['start_date' => '2025-07-01', 'end_date' => '2026-06-30', 'is_active' => true]
            );
        }

        $user->update([
            'status_verifikasi' => 'APPROVED',
            'rejection_reason' => null,
        ]);

        $name = $user->matched_name ?: $user->name;
        $nisn = $user->matched_nisn ?: $user->nisn;
        $kelas = $user->matched_kelas ?: $user->kelas;

        if ($user->matched_application_id) {
            $app = MemberApplication::find($user->matched_application_id);
            if ($app) {
                $name = $name ?: $app->name;
                $nisn = $nisn ?: $app->nisn;
                $kelas = $kelas ?: $app->kelas;
            }
            MemberApplication::where('id', $user->matched_application_id)->update([
                'user_id' => $user->id,
                'status' => 'DISETUJUI',
            ]);
        }

        // Sinkronkan ke Buku Induk: tandai status menjadi Terdaftar (is_registered = true)
        MasterMember::syncOrCreate([
            'nisn' => $nisn,
            'name' => $name,
            'kelas' => $kelas,
            'no_hp' => $user->phone,
            'tempat_lahir' => $user->birth_place,
            'tanggal_lahir' => $user->birth_date,
            'user_id' => $user->id,
            'is_registered' => true,
        ]);

        // Sinkronkan ke data Member - cegah duplikasi jika siswa sudah pernah terdaftar lewat pendaftaran calon anggota
        Member::syncOrCreate([
            'user_id' => $user->id,
            'name' => $name,
            'nisn' => $nisn,
            'kelas' => $kelas,
            'phone' => $user->phone,
            'organization_period_id' => $period->id,
            'status' => 'AKTIF',
        ]);

        return redirect()->back()->with('success', "Akun siswa {$user->name} berhasil disetujui & resmi memperoleh akses anggota!");
    }

    /**
     * Reject user account registration (legacy support).
     */
    public function reject(Request $request, User $user)
    {
        $this->authorizeAccess();

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $user->update([
            'status_verifikasi' => 'REJECTED',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->back()->with('success', "Pendaftaran akun {$user->name} telah ditolak.");
    }

    /**
     * Add new student candidate to Master Data.
     */
    public function storeMaster(Request $request)
    {
        $this->authorizeAccess();

        $request->validate([
            'name' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'nisn' => 'nullable|string|max:20|unique:master_members,nisn',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'no_hp' => 'nullable|string|max:20',
            'catatan' => 'nullable|string|max:255',
        ]);

        $formattedName = ucwords(strtolower(trim($request->name)));
        $formattedBirthPlace = $request->tempat_lahir ? ucwords(strtolower(trim($request->tempat_lahir))) : null;
        $formattedKelas = strtoupper(trim($request->kelas));

        MasterMember::create([
            'name' => $formattedName,
            'kelas' => $formattedKelas,
            'nisn' => $request->nisn ? trim($request->nisn) : null,
            'tempat_lahir' => $formattedBirthPlace,
            'tanggal_lahir' => $request->tanggal_lahir,
            'no_hp' => $request->no_hp ? trim($request->no_hp) : null,
            'catatan' => $request->catatan,
            'is_registered' => false,
        ]);

        return redirect()->back()->with('success', "Calon siswa {$formattedName} ({$formattedKelas}) berhasil ditambahkan ke Buku Induk!");
    }

    /**
     * Delete master student record.
     */
    public function destroyMaster(MasterMember $masterMember)
    {
        $this->authorizeAccess();

        $masterMember->delete();

        return redirect()->back()->with('success', 'Data siswa berhasil dihapus dari Buku Induk.');
    }

    /**
     * Download Excel template for Master Data (Buku Induk).
     */
    public function templateMasterExcel()
    {
        $this->authorizeAccess();

        $headers = ['Nama Lengkap', 'NISN', 'Kelas', 'Tempat Lahir', 'Tanggal Lahir (YYYY-MM-DD)', 'No HP / WA', 'Catatan'];
        $sampleRows = [
            ['Aura Sabrina Maylani', '0081234567', 'X AKL 1', 'Banyumas', '2008-03-12', '081234567890', 'Buku Induk 2025'],
            ['Khusnun Khamida', '0081234568', 'X AKL 1', 'Purwokerto', '2008-07-21', '081234567891', 'Buku Induk 2025'],
        ];

        return ExcelService::downloadXlsx('template_buku_induk_siswa.xlsx', $headers, $sampleRows);
    }

    /**
     * Export Master Data (Buku Induk) to Excel.
     */
    public function exportMasterExcel(Request $request)
    {
        $this->authorizeAccess();

        $query = MasterMember::query();
        if ($request->filled('master_search')) {
            $query->where('name', 'like', '%'.$request->master_search.'%')
                ->orWhere('kelas', 'like', '%'.$request->master_search.'%')
                ->orWhere('nisn', 'like', '%'.$request->master_search.'%');
        }

        $items = $query->orderBy('name')->get();
        $headers = ['No', 'Nama Lengkap', 'NISN', 'Kelas', 'Tempat Lahir', 'Tanggal Lahir', 'No HP', 'Status Registrasi Akun'];
        $rows = [];
        $no = 1;
        foreach ($items as $item) {
            $rows[] = [
                $no++,
                $item->name,
                $item->nisn ?: '-',
                $item->kelas,
                $item->tempat_lahir ?: '-',
                $item->tanggal_lahir ? $item->tanggal_lahir->format('d/m/Y') : '-',
                $item->no_hp ?: '-',
                $item->is_registered ? 'Terdaftar' : 'Belum Terdaftar',
            ];
        }

        return ExcelService::downloadXlsx('buku_induk_siswa_'.date('Ymd_His').'.xlsx', $headers, $rows);
    }

    /**
     * Export Master Data (Buku Induk) to printable PDF view.
     */
    public function exportMasterPdf(Request $request)
    {
        $this->authorizeAccess();

        $query = MasterMember::query();
        if ($request->filled('master_search')) {
            $query->where('name', 'like', '%'.$request->master_search.'%')
                ->orWhere('kelas', 'like', '%'.$request->master_search.'%')
                ->orWhere('nisn', 'like', '%'.$request->master_search.'%');
        }

        $items = $query->orderBy('kelas')->orderBy('name')->get();
        $metaTanggal = date('d F Y');
        $metaTahunAjaran = '2025/2026';

        return view('verifikasi.master-pdf', compact('items', 'metaTanggal', 'metaTahunAjaran'));
    }

    /**
     * Import Master Data (Buku Induk) from Excel.
     */
    public function importMasterExcel(Request $request)
    {
        $this->authorizeAccess();

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx|max:5120',
            'duplicate_mode' => 'required|in:skip,update',
        ]);

        try {
            $rows = ExcelService::readXlsx($request->file('excel_file')->getRealPath());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membaca file Excel: '.$e->getMessage());
        }

        if (empty($rows)) {
            return redirect()->back()->with('error', 'File Excel kosong atau tidak memuat baris data valid.');
        }

        $inserted = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            // Find key by normalization
            $name = $row['nama_lengkap'] ?? $row['nama'] ?? '';
            $kelas = $row['kelas'] ?? '';
            $nisn = $row['nisn'] ?? null;
            $tempatLahir = $row['tempat_lahir'] ?? null;
            $tanggalLahir = $row['tanggal_lahir_yyyy_mm_dd_'] ?? $row['tanggal_lahir'] ?? null;
            $noHp = $row['no_hp___wa'] ?? $row['no_hp'] ?? null;
            $catatan = $row['catatan'] ?? null;

            if (empty(trim($name)) || empty(trim($kelas))) {
                $skipped++;

                continue;
            }

            $formattedName = ucwords(strtolower(trim($name)));
            $formattedKelas = strtoupper(trim($kelas));
            $cleanNisn = $nisn ? trim($nisn) : null;

            $existing = null;
            if ($cleanNisn) {
                $existing = MasterMember::where('nisn', $cleanNisn)->first();
            }
            if (! $existing) {
                $cleanName = strtolower(trim($name));
                $existing = MasterMember::whereRaw('LOWER(TRIM(name)) = ?', [$cleanName])
                    ->where('kelas', $formattedKelas)
                    ->first();
            }

            if ($existing) {
                if ($request->duplicate_mode === 'update') {
                    $existing->update([
                        'name' => $formattedName,
                        'kelas' => $formattedKelas,
                        'nisn' => $cleanNisn ?: $existing->nisn,
                        'tempat_lahir' => $tempatLahir ?: $existing->tempat_lahir,
                        'tanggal_lahir' => $tanggalLahir ?: $existing->tanggal_lahir,
                        'no_hp' => $noHp ?: $existing->no_hp,
                        'catatan' => $catatan ?: $existing->catatan,
                    ]);
                    $updated++;
                } else {
                    $skipped++;
                }
            } else {
                MasterMember::create([
                    'name' => $formattedName,
                    'kelas' => $formattedKelas,
                    'nisn' => $cleanNisn,
                    'tempat_lahir' => $tempatLahir,
                    'tanggal_lahir' => $tanggalLahir,
                    'no_hp' => $noHp,
                    'catatan' => $catatan,
                    'is_registered' => false,
                ]);
                $inserted++;
            }
        }

        return redirect()->back()->with('success', "Impor Buku Induk selesai: {$inserted} data baru ditambahkan, {$updated} diperbarui, {$skipped} dilewati.");
    }
}
