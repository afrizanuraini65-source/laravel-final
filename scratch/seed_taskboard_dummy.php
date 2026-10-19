<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Division;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskUpdate;
use App\Models\Event;
use Carbon\Carbon;

// Let's delete any partial tasks from previous run where id > 5
Task::where('id', '>', 5)->delete();

$divisions = Division::all()->keyBy('id');
$usersByDiv = [];
foreach (Division::all() as $div) {
    $usersByDiv[$div->id] = User::whereHas('member', function($q) use ($div) {
        $q->where('division_id', $div->id);
    })->pluck('id')->toArray();
}

$allMemberUsers = User::whereHas('member')->pluck('id')->toArray();
$adminUser = User::role('ADMIN')->first() ?? User::first();
$events = Event::pluck('id')->toArray();

// Task definitions per division and status to make it realistic and well populated
$dummyTasks = [
    // 1. DIVISI KEGIATAN (div_id: 1)
    [
        'title' => 'Penyusunan Rundown & Jadwal Pelatihan Peer Counselor',
        'description' => 'Menyusun alur kegiatan, timeline per sesi pemateri, serta penugasan MC dan timekeeper untuk workshop konseling sebaya.',
        'division_id' => 1,
        'priority' => 'Tinggi',
        'status' => 'Sedang Dikerjakan',
        'progress' => 65,
        'deadline' => Carbon::now()->addDays(3),
    ],
    [
        'title' => 'Briefing Petugas Piket P2KS Mingguan',
        'description' => 'Mengoordinasikan jadwal rolling piket ruang P2KS dan checklist kelengkapan inventaris ruang konseling.',
        'division_id' => 1,
        'priority' => 'Sedang',
        'status' => 'Belum Mulai',
        'progress' => 0,
        'deadline' => Carbon::now()->addDays(5),
    ],
    [
        'title' => 'Evaluasi & LPJ Kegiatan Sosialisasi Remaja Sehat',
        'description' => 'Rekapitulasi absensi kehadiran peserta, catatan kendala teknis lapangan, dan draft bab pelaksanaan kegiatan.',
        'division_id' => 1,
        'priority' => 'Tinggi',
        'status' => 'Perlu Revisi',
        'progress' => 80,
        'deadline' => Carbon::now()->addDays(2),
    ],
    [
        'title' => 'Gladi Bersih Pelantikan Pengurus Baru SKAKMATT',
        'description' => 'Simulasi upacara serah terima jabatan dan pengucapan janji bakti PIK-R bersama Pembina.',
        'division_id' => 1,
        'priority' => 'Tinggi',
        'status' => 'Selesai',
        'progress' => 100,
        'deadline' => Carbon::now()->subDays(2),
    ],

    // 2. DIVISI HUBUNGAN MASYARAKAT (div_id: 2)
    [
        'title' => 'Surat Pengantar Audiensi ke BKKBN & Puskesmas',
        'description' => 'Finalisasi surat permohonan narasumber kesehatan reproduksi dan follow-up konfirmasi kehadiran dokter pembina.',
        'division_id' => 2,
        'priority' => 'Tinggi',
        'status' => 'Sedang Dikerjakan',
        'progress' => 70,
        'deadline' => Carbon::now()->addDays(4),
    ],
    [
        'title' => 'Distribusi Undangan Kolaborasi Lintas Ekskul',
        'description' => 'Menyampaikan surat undangan technical meeting ke OSIS, PMR, Pramuka, dan Rohis untuk event kolaborasi gabungan.',
        'division_id' => 2,
        'priority' => 'Sedang',
        'status' => 'Belum Mulai',
        'progress' => 0,
        'deadline' => Carbon::now()->addDays(7),
    ],
    [
        'title' => 'Draft Siaran Pers & Liputan Sosialisasi GenRe',
        'description' => 'Pembuatan rilis berita kegiatan di media mading sekolah dan koordinasi dengan perwakilan kelas.',
        'division_id' => 2,
        'priority' => 'Rendah',
        'status' => 'Selesai',
        'progress' => 100,
        'deadline' => Carbon::now()->subDays(1),
    ],
    [
        'title' => 'Pembaruan Buku Kontak Mitra Instansi & Jejaring Alumni',
        'description' => 'Verifikasi nomor kontak aktif pembina eksternal, fasilitator forum anak, dan database alumni PIK-R.',
        'division_id' => 2,
        'priority' => 'Sedang',
        'status' => 'Perlu Revisi',
        'progress' => 50,
        'deadline' => Carbon::now()->addDays(1),
    ],

    // 3. DIVISI PDD / PUBLIKASI DEKORASI DOKUMENTASI (div_id: 3)
    [
        'title' => 'Desain Feed Instagram Edukasi Mental Health Remaja',
        'description' => 'Membuat 5 slide carousel infografis pencegahan stress akademik dan tips relaksasi sebelum ujian.',
        'division_id' => 3,
        'priority' => 'Tinggi',
        'status' => 'Sedang Dikerjakan',
        'progress' => 55,
        'deadline' => Carbon::now()->addDays(2),
    ],
    [
        'title' => 'Pembuatan Video Teaser Event Peringatan Hari Remaja',
        'description' => 'Shooting cuplikan kegiatan konseling, editing reels durasi 60 detik dengan audio trending dan color grading.',
        'division_id' => 3,
        'priority' => 'Tinggi',
        'status' => 'Perlu Revisi',
        'progress' => 75,
        'deadline' => Carbon::now()->addDays(3),
    ],
    [
        'title' => 'Desain Banner & Backdrop Panggung Utama',
        'description' => 'Cetak banner ukuran 4x2 meter untuk panggung seminar GenRe Ceria di aula serbaguna.',
        'division_id' => 3,
        'priority' => 'Sedang',
        'status' => 'Selesai',
        'progress' => 100,
        'deadline' => Carbon::now()->subDays(3),
    ],
    [
        'title' => 'Arsip Dokumentasi Foto & Google Drive Event',
        'description' => 'Sortir foto resolusi tinggi dari kamera DSLR, watermark logo SKAKMATT, dan unggah ke cloud drive divisi.',
        'division_id' => 3,
        'priority' => 'Rendah',
        'status' => 'Belum Mulai',
        'progress' => 0,
        'deadline' => Carbon::now()->addDays(6),
    ],

    // 4. DIVISI PKWU / KEWIRAUSAHAAN (div_id: 4)
    [
        'title' => 'Pencatatan Arus Kas Penjualan Merchandise PIK-R',
        'description' => 'Rekap modal awal, stok totebag & gantungan kunci, serta margin keuntungan penjualan stand bazaar.',
        'division_id' => 4,
        'priority' => 'Sedang',
        'status' => 'Sedang Dikerjakan',
        'progress' => 45,
        'deadline' => Carbon::now()->addDays(4),
    ],
    [
        'title' => 'Pengadaan Bahan Baku Produk Minuman Sehat Herbal',
        'description' => 'Membeli bahan baku ramuan rempah sinom dan kemasan botol eco-friendly untuk program dana usaha mandiri.',
        'division_id' => 4,
        'priority' => 'Tinggi',
        'status' => 'Belum Mulai',
        'progress' => 0,
        'deadline' => Carbon::now()->addDays(5),
    ],
    [
        'title' => 'Laporan Pertanggungjawaban Stan Bazar Hari Kemerdekaan',
        'description' => 'Kompilasi nota belanja, kwitansi sewa tempat, dan profit bersih yang diserahkan ke Bendahara Umum.',
        'division_id' => 4,
        'priority' => 'Sedang',
        'status' => 'Selesai',
        'progress' => 100,
        'deadline' => Carbon::now()->subDays(5),
    ],
    [
        'title' => 'Revisi Proposal Kerjasama Sponsor Usaha Kuliner',
        'description' => 'Menyesuaikan benefit paket sponsorship bronze dan silver sesuai catatan dari Bendahara.',
        'division_id' => 4,
        'priority' => 'Tinggi',
        'status' => 'Perlu Revisi',
        'progress' => 60,
        'deadline' => Carbon::now()->addDays(2),
    ],

    // 5. DIVISI KONSELOR SEBAYA (div_id: 5)
    [
        'title' => 'Rekap Laporan Kasus Konseling Anonim Periode Ini',
        'description' => 'Kompilasi statistik curhat anonim siswa melalui kotak konseling & direct message akun PIK-R.',
        'division_id' => 5,
        'priority' => 'Tinggi',
        'status' => 'Sedang Dikerjakan',
        'progress' => 85,
        'deadline' => Carbon::now()->addDays(2),
    ],
    [
        'title' => 'Simulasi Roleplay Studi Kasus Bullying & Resolusi Konflik',
        'description' => 'Latihan penanganan konseling sebaya dengan bimbingan Guru BK untuk para anggota baru.',
        'division_id' => 5,
        'priority' => 'Sedang',
        'status' => 'Belum Mulai',
        'progress' => 0,
        'deadline' => Carbon::now()->addDays(8),
    ],
    [
        'title' => 'Modul Panduan Self-Harm Prevention & Emergency Help',
        'description' => 'Penyusunan booklet saku penanganan krisis mental siswa untuk dibagikan ke wali kelas.',
        'division_id' => 5,
        'priority' => 'Tinggi',
        'status' => 'Perlu Revisi',
        'progress' => 70,
        'deadline' => Carbon::now()->addDays(1),
    ],
    [
        'title' => 'Screening Kesehatan Mental Kuesioner SRQ-20 Siswa Baru',
        'description' => 'Tabulasi respon kuesioner Self Reporting Questionnaire dari 250 peserta didik baru.',
        'division_id' => 5,
        'priority' => 'Tinggi',
        'status' => 'Selesai',
        'progress' => 100,
        'deadline' => Carbon::now()->subDays(4),
    ],
];

// Ensure all division IDs are valid
$validDivIds = Division::pluck('id')->toArray();

$insertedCount = 0;
foreach ($dummyTasks as $taskData) {
    $divId = in_array($taskData['division_id'], $validDivIds) ? $taskData['division_id'] : $validDivIds[0];
    
    // Pick assignee from division members if available, otherwise any member user
    $assigneeId = null;
    if (!empty($usersByDiv[$divId])) {
        $assigneeId = $usersByDiv[$divId][array_rand($usersByDiv[$divId])];
    } elseif (!empty($allMemberUsers)) {
        $assigneeId = $allMemberUsers[array_rand($allMemberUsers)];
    }

    $event_id = !empty($events) ? $events[array_rand($events)] : null;

    $task = Task::create([
        'title' => $taskData['title'],
        'description' => $taskData['description'],
        'division_id' => $divId,
        'assigned_to_user_id' => $assigneeId,
        'deadline' => $taskData['deadline'],
        'priority' => $taskData['priority'],
        'status' => $taskData['status'],
        'progress' => $taskData['progress'],
        'event_id' => $event_id,
    ]);

    // Create a task update record for realistic history
    if ($taskData['status'] === 'Perlu Revisi') {
        TaskUpdate::create([
            'task_id' => $task->id,
            'user_id' => $adminUser->id ?? 1,
            'status' => 'Perlu Revisi',
            'catatan' => 'Mohon sesuaikan format lampiran dan lengkapi tanda tangan ketua sebelum diajukan kembali.',
            'kendala' => 'Format file sebelumnya belum sesuai standar pedoman SKAKMATT.',
            'progress' => $taskData['progress'],
        ]);
    } elseif ($taskData['status'] === 'Sedang Dikerjakan') {
        TaskUpdate::create([
            'task_id' => $task->id,
            'user_id' => $assigneeId ?? ($adminUser->id ?? 1),
            'status' => 'Sedang Dikerjakan',
            'catatan' => 'Progres pengerjaan berjalan lancar, draft sudah mencapai lebih dari 50%.',
            'kendala' => null,
            'progress' => $taskData['progress'],
        ]);
    } elseif ($taskData['status'] === 'Selesai') {
        TaskUpdate::create([
            'task_id' => $task->id,
            'user_id' => $assigneeId ?? ($adminUser->id ?? 1),
            'status' => 'Selesai',
            'catatan' => 'Tugas telah selesai dikerjakan dan diverifikasi oleh koordinator divisi.',
            'kendala' => null,
            'progress' => 100,
        ]);
    }

    $insertedCount++;
}

echo "SUCCESS! Berhasil menambahkan {$insertedCount} dummy tasks!\n";
echo "Total tasks sekarang: " . Task::count() . "\n";
foreach (['Belum Mulai', 'Sedang Dikerjakan', 'Perlu Revisi', 'Selesai'] as $st) {
    echo "- {$st}: " . Task::where('status', $st)->count() . "\n";
}
