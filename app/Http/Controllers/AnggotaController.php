<?php

namespace App\Http\Controllers;

use App\Models\MasterMember;
use App\Models\Member;
use App\Models\OrganizationPeriod;
use App\Models\User;
use App\Services\ExcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnggotaController extends Controller
{
    /**
     * Check authorization: Sekretaris, Ketua, Admin, Pengurus, or Member.
     */
    protected function checkRolePrivileges(): array
    {
        $user = Auth::user();
        $canManage = $user && $user->hasRole(['ADMIN', 'SEKRETARIS']);
        $canView = $user && $user->hasRole(['ADMIN', 'KETUA', 'SEKRETARIS', 'PEMBINA', 'PELATIH', 'WAKIL_KETUA', 'USER']);

        if (! $canView) {
            abort(403, 'Akses terbatas untuk anggota & pengurus.');
        }

        return [$canManage, $user->hasRole('KETUA')];
    }

    /**
     * Display members list.
     */
    public function index(Request $request)
    {
        [$canManage, $isKetua] = $this->checkRolePrivileges();

        $totalAnggota = Member::count();
        $totalX = Member::where('kelas', 'LIKE', 'X %')->count();
        $totalXI = Member::where('kelas', 'LIKE', 'XI %')->count();
        $totalXII = Member::where('kelas', 'LIKE', 'XII %')->count();

        $query = Member::with(['user', 'position', 'division', 'period']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('nisn', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%')
                            ->orWhere('nisn', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($request->filled('level')) {
            $query->where('kelas', 'like', $request->level.' %');
        }

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $anggota = $query->orderBy('kelas')->orderBy('name')->paginate(25)->withQueryString();

        $kelasList = Member::select('kelas')->whereNotNull('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('anggota.index', compact(
            'anggota',
            'totalAnggota',
            'totalX',
            'totalXI',
            'totalXII',
            'kelasList',
            'canManage',
            'isKetua'
        ));
    }

    /**
     * Store new member manually (Sekretaris & Admin).
     */
    public function store(Request $request)
    {
        [$canManage] = $this->checkRolePrivileges();
        if (! $canManage) {
            abort(403, 'Hanya Sekretaris dan Admin yang berwenang menambah data anggota manual.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'nisn' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:AKTIF,ALUMNI,DEMISIONER,TIDAK_AKTIF',
        ]);

        $period = OrganizationPeriod::where('is_active', true)->first();

        $formattedName = ucwords(strtolower(trim($request->name)));
        $formattedKelas = strtoupper(trim($request->kelas));
        $cleanNisn = $request->nisn ? trim($request->nisn) : null;

        // Cegah data ganda: periksa apakah anggota sudah ada di sistem
        $existing = Member::findExisting(null, $cleanNisn, $formattedName, $formattedKelas);
        if ($existing) {
            return redirect()->back()->with('error', "Data anggota '{$existing->name}' ({$existing->kelas}) sudah terdaftar dalam sistem (ID #{$existing->id}). Tidak boleh ada data ganda.");
        }

        Member::create([
            'name' => $formattedName,
            'kelas' => $formattedKelas,
            'nisn' => $cleanNisn,
            'phone' => $request->phone ? trim($request->phone) : null,
            'status' => $request->status,
            'organization_period_id' => $period?->id,
            'user_id' => null, // Offline member
        ]);

        // Otomatis sinkronkan juga ke Buku Induk dengan status Belum Akun
        MasterMember::syncOrCreate([
            'name' => $formattedName,
            'kelas' => $formattedKelas,
            'nisn' => $cleanNisn,
            'no_hp' => $request->phone ? trim($request->phone) : null,
            'user_id' => null,
            'is_registered' => false,
        ]);

        return redirect()->back()->with('success', 'Anggota baru berhasil ditambahkan secara manual.');
    }

    /**
     * Update member data (Sekretaris & Admin).
     */
    public function update(Request $request, Member $member)
    {
        [$canManage] = $this->checkRolePrivileges();
        if (! $canManage) {
            abort(403, 'Hanya Sekretaris dan Admin yang berwenang mengedit data anggota.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'nisn' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:AKTIF,ALUMNI,DEMISIONER,TIDAK_AKTIF',
        ]);

        $formattedName = ucwords(strtolower(trim($request->name)));
        $formattedKelas = strtoupper(trim($request->kelas));
        $cleanNisn = $request->nisn ? trim($request->nisn) : null;
        $cleanPhone = $request->phone ? trim($request->phone) : null;

        $member->update([
            'name' => $formattedName,
            'kelas' => $formattedKelas,
            'nisn' => $cleanNisn,
            'phone' => $cleanPhone,
            'status' => $request->status,
        ]);

        // If connected to a user, sync user info as well
        if ($member->user) {
            $member->user->update([
                'name' => $formattedName,
                'kelas' => $formattedKelas,
                'nisn' => $cleanNisn ?: $member->user->nisn,
                'phone' => $cleanPhone ?: $member->user->phone,
            ]);
        }

        return redirect()->back()->with('success', "Data anggota {$formattedName} berhasil diperbarui.");
    }

    /**
     * Delete member record (Sekretaris & Admin).
     */
    public function destroy(Member $member)
    {
        [$canManage] = $this->checkRolePrivileges();
        if (! $canManage) {
            abort(403, 'Hanya Sekretaris dan Admin yang berwenang menghapus data anggota.');
        }

        $name = $member->name;
        $member->delete();

        return redirect()->back()->with('success', "Data anggota {$name} berhasil dihapus.");
    }

    /**
     * Export members list to XLSX.
     */
    public function exportExcel(Request $request)
    {
        $this->checkRolePrivileges();

        $query = Member::with(['user', 'position', 'division', 'period']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('nisn', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', '%'.$search.'%')
                            ->orWhere('nisn', 'like', '%'.$search.'%');
                    });
            });
        }
        if ($request->filled('level')) {
            $query->where('kelas', 'like', $request->level.' %');
        }
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderBy('kelas')->orderBy('name')->get();

        $headers = ['No', 'Nama Lengkap', 'NISN', 'Kelas', 'Status Keanggotaan', 'Jabatan / Kepengurusan', 'Divisi', 'Akun Email'];
        $rows = [];
        $no = 1;

        foreach ($items as $item) {
            $rows[] = [
                $no++,
                $item->name,
                $item->nisn ?: '-',
                $item->kelas ?: '-',
                $item->status ?: 'AKTIF',
                $item->position?->title ?: 'Anggota',
                $item->division?->name ?: '-',
                $item->user?->email ?: 'Offline',
            ];
        }

        return ExcelService::downloadXlsx('daftar_anggota_pikr_'.date('Ymd_His').'.xlsx', $headers, $rows);
    }

    /**
     * Download Excel template for Member import.
     */
    public function templateExcel()
    {
        [$canManage] = $this->checkRolePrivileges();
        if (! $canManage) {
            abort(403);
        }

        $headers = ['Nama Lengkap', 'NISN', 'Kelas', 'Status (AKTIF/ALUMNI)', 'Nomor HP'];
        $sampleRows = [
            ['Aura Sabrina Maylani', '0081234567', 'X AKL 1', 'AKTIF', '081234567890'],
            ['Khusnun Khamida', '0081234568', 'X AKL 1', 'AKTIF', '081234567891'],
        ];

        return ExcelService::downloadXlsx('template_data_anggota_pikr.xlsx', $headers, $sampleRows);
    }

    /**
     * Import members from XLSX file.
     */
    public function importExcel(Request $request)
    {
        [$canManage] = $this->checkRolePrivileges();
        if (! $canManage) {
            abort(403, 'Akses ditolak.');
        }

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
            return redirect()->back()->with('error', 'File Excel kosong atau format tidak sesuai.');
        }

        $period = OrganizationPeriod::where('is_active', true)->first();
        $inserted = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            $name = $row['nama_lengkap'] ?? $row['nama'] ?? '';
            $kelas = $row['kelas'] ?? '';
            $nisn = $row['nisn'] ?? null;
            $status = strtoupper(trim($row['status__aktif_alumni_'] ?? $row['status'] ?? 'AKTIF'));
            $phone = $row['nomor_hp'] ?? $row['no_hp'] ?? null;

            if (empty(trim($name)) || empty(trim($kelas))) {
                $skipped++;

                continue;
            }

            $formattedName = ucwords(strtolower(trim($name)));
            $formattedKelas = strtoupper(trim($kelas));
            $cleanNisn = $nisn ? trim($nisn) : null;
            $validStatus = in_array($status, ['AKTIF', 'ALUMNI', 'DEMISIONER', 'TIDAK_AKTIF']) ? $status : 'AKTIF';

            // Check existing member
            $existing = null;
            if ($cleanNisn) {
                $existing = Member::where('nisn', $cleanNisn)->first();
            }
            if (! $existing) {
                $cleanName = strtolower(trim($name));
                $existing = Member::where(function ($q) use ($cleanName) {
                    $q->whereRaw('LOWER(TRIM(name)) = ?', [$cleanName])
                        ->orWhereHas('user', function ($uq) use ($cleanName) {
                            $uq->whereRaw('LOWER(TRIM(name)) = ?', [$cleanName]);
                        });
                })->where('kelas', $formattedKelas)->first();
            }

            if ($existing) {
                if ($request->duplicate_mode === 'update') {
                    $existing->update([
                        'name' => $formattedName,
                        'kelas' => $formattedKelas,
                        'nisn' => $cleanNisn ?: $existing->nisn,
                        'phone' => $phone ?: $existing->phone,
                        'status' => $validStatus,
                    ]);
                    $updated++;
                } else {
                    $skipped++;
                }
            } else {
                Member::create([
                    'name' => $formattedName,
                    'kelas' => $formattedKelas,
                    'nisn' => $cleanNisn,
                    'phone' => $phone ? trim($phone) : null,
                    'status' => $validStatus,
                    'organization_period_id' => $period?->id,
                    'user_id' => null,
                ]);
                $inserted++;
            }
        }

        return redirect()->back()->with('success', "Impor data anggota selesai: {$inserted} ditambahkan, {$updated} diperbarui, {$skipped} dilewati.");
    }

    /**
     * PDF Document Generator & Preview.
     */
    public function pdfView(Request $request)
    {
        $this->checkRolePrivileges();

        $query = Member::with(['user', 'position', 'division']);

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }
        if ($request->filled('level')) {
            $query->where('kelas', 'like', $request->level.' %');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sort = $request->get('sort', 'kelas');
        if ($sort === 'name') {
            $query->orderBy('name');
        } else {
            $query->orderBy('kelas')->orderBy('name');
        }

        $members = $query->get();

        // Document Options
        $docTitle = $request->get('doc_title', 'DAFTAR HADIR KELAS ANGGOTA EKSTRAKURIKULER PIK-R SKAKMATT');
        $showKop = $request->boolean('show_kop', true);
        $showMetadata = $request->boolean('show_metadata', true);
        $showSignatureArea = $request->boolean('show_signature_area', true);

        // Column Flags
        $colNo = $request->boolean('col_no', true);
        $colName = $request->boolean('col_name', true);
        $colKelas = $request->boolean('col_kelas', true);
        $colNisn = $request->boolean('col_nisn', false);
        $colSignature = $request->boolean('col_signature', true);
        $colKet = $request->boolean('col_ket', true);

        // Activity Metadata
        $metaHari = $request->get('meta_hari', 'Rabu');
        $metaTanggal = $request->get('meta_tanggal', date('d F Y'));
        $metaTempat = $request->get('meta_tempat', 'Ruang C202 / Aula');
        $metaKegiatan = $request->get('meta_kegiatan', 'Forum Rutin PIK-R SKAKMATT');
        $metaTahunAjaran = $request->get('meta_tahun', '2025/2026');

        $kelasList = Member::select('kelas')->whereNotNull('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('anggota.pdf', compact(
            'members',
            'docTitle',
            'showKop',
            'showMetadata',
            'showSignatureArea',
            'colNo',
            'colName',
            'colKelas',
            'colNisn',
            'colSignature',
            'colKet',
            'metaHari',
            'metaTanggal',
            'metaTempat',
            'metaKegiatan',
            'metaTahunAjaran',
            'kelasList'
        ));
    }
}
