<?php

use App\Data\ArticleData;
use App\Data\DutaGenreData;
use App\Http\Controllers\Admin\ContentManagementController;
use App\Http\Controllers\Admin\PaymentSettingController;
use App\Http\Controllers\Admin\RegistrationSettingController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\DaftarUlangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\DueController;
use App\Http\Controllers\EventCommitteeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\KalenderController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\KepengurusanController;
use App\Http\Controllers\MemberRegistrationController;
use App\Http\Controllers\P2ksScheduleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PendaftaranEventController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratDokumenController;
use App\Http\Controllers\TaskBoardController;
use App\Http\Controllers\VerifikasiAnggotaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/informasi', function () {
    $articles = ArticleData::getAll();

    return view('informasi', compact('articles'));
});

Route::get('/artikel', function () {
    $articles = ArticleData::getAll();

    return view('informasi', compact('articles'));
});

Route::get('/informasi/{slug}', function ($slug) {
    $article = ArticleData::findBySlug($slug);
    if (! $article) {
        abort(404);
    }
    $otherArticles = array_filter(ArticleData::getAll(), fn ($item) => $item['slug'] !== $slug);

    return view('informasi-detail', compact('article', 'otherArticles'));
});

Route::get('/artikel/{slug}', function ($slug) {
    $article = ArticleData::findBySlug($slug);
    if (! $article) {
        abort(404);
    }
    $otherArticles = array_filter(ArticleData::getAll(), fn ($item) => $item['slug'] !== $slug);

    return view('informasi-detail', compact('article', 'otherArticles'));
});

Route::get('/kegiatan', function () {
    return view('kegiatan');
});

Route::get('/kepengurusan', function () {
    return view('kepengurusan');
});

Route::get('/ruang-sebaya', function () {
    return view('ruang-sebaya');
});

// Duta GenRe Public Profiles
Route::get('/duta-genre', function () {
    $dutagenres = DutaGenreData::getAll();

    return view('duta-genre', compact('dutagenres'));
});

Route::get('/duta-genre/{slug}', function ($slug) {
    $duta = DutaGenreData::findBySlug($slug);
    if (! $duta) {
        abort(404);
    }

    return view('duta-genre-detail', compact('duta'));
});

// Member Application Form (Guest & Authenticated)
Route::get('/pendaftaran-anggota', [MemberRegistrationController::class, 'create'])->name('pendaftaran-anggota.create');
Route::post('/pendaftaran-anggota', [MemberRegistrationController::class, 'store'])->name('pendaftaran-anggota.store');

/*
|--------------------------------------------------------------------------
| Authenticated & Verified Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/presensi', [DashboardController::class, 'storePresensi'])->name('dashboard.presensi');

    // Kas & Keuangan (User & Bendahara)
    Route::get('/kas', [DueController::class, 'index'])->name('kas.index');
    Route::post('/kas/bayar', [PaymentController::class, 'store'])->name('payments.store');

    // Khusus Bendahara & Ketua
    Route::middleware('can:manage_finance')->group(function () {
        Route::get('/admin/settings/payment', [PaymentSettingController::class, 'index'])->name('admin.settings.payment.index');
        Route::put('/admin/settings/payment', [PaymentSettingController::class, 'update'])->name('admin.settings.payment.update');
        Route::post('/kas', [DueController::class, 'store'])->name('kas.store');
        Route::put('/kas/{due}', [DueController::class, 'update'])->name('kas.update');
        Route::delete('/kas/{due}', [DueController::class, 'destroy'])->name('kas.destroy');
        Route::get('/kas/export', [DueController::class, 'export'])->name('kas.export');
        Route::get('/kas/verifikasi', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('/kas/verifikasi/{payment}', [PaymentController::class, 'verify'])->name('payments.verify');
        Route::post('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify.alt');
    });

    // Pendaftaran Event & Kegiatan (Personal Anggota)
    Route::get('/pendaftaran-saya', [PendaftaranEventController::class, 'index'])->name('pendaftaran.index');
    Route::post('/pendaftaran-saya/{event}', [PendaftaranEventController::class, 'store'])->name('pendaftaran.store');
    Route::delete('/pendaftaran-saya/{registration}', [PendaftaranEventController::class, 'cancel'])->name('pendaftaran.cancel');

    // Anggota Directory & Actions
    Route::get('/feature/Anggota/export', [AnggotaController::class, 'exportExcel'])->name('anggota.export');
    Route::get('/feature/Anggota/pdf', [AnggotaController::class, 'pdfView'])->name('anggota.pdf');
    Route::get('/feature/Anggota/template', [AnggotaController::class, 'templateExcel'])->name('anggota.template');
    Route::post('/feature/Anggota/import', [AnggotaController::class, 'importExcel'])->name('anggota.import');
    Route::get('/feature/Anggota', [AnggotaController::class, 'index'])->name('anggota.index');
    Route::get('/feature/anggota-alias', [AnggotaController::class, 'index'])->name('feature.anggota');
    Route::post('/feature/Anggota', [AnggotaController::class, 'store'])->name('anggota.store');
    Route::put('/feature/Anggota/{member}', [AnggotaController::class, 'update'])->name('anggota.update');
    Route::delete('/feature/Anggota/{member}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');
    Route::put('/admin/anggota/{member}', [AnggotaController::class, 'update']);
    Route::delete('/admin/anggota/{member}', [AnggotaController::class, 'destroy']);

    // Registration Period Settings (Admin, Ketua, Sekretaris)
    Route::get('/admin/settings/registration', [RegistrationSettingController::class, 'index'])->name('admin.settings.registration.index');
    Route::post('/admin/settings/registration', [RegistrationSettingController::class, 'update'])->name('admin.settings.registration.update');
    Route::post('/admin/settings/registration/toggle-account', [RegistrationSettingController::class, 'toggleAccount'])->name('admin.settings.registration.toggle-account');
    Route::post('/admin/settings/registration/toggle-member', [RegistrationSettingController::class, 'toggleMember'])->name('admin.settings.registration.toggle-member');

    // Kepengurusan Directory (Admin Panel)
    Route::get('/feature/Kepengurusan', [KepengurusanController::class, 'index'])->name('feature.kepengurusan');

    // Divisi Module (Admin Panel)
    Route::get('/feature/Divisi', [DivisiController::class, 'index'])->name('feature.divisi');
    Route::get('/feature/Divisi/{slug}', [DivisiController::class, 'show'])->name('divisi.show');

    // Surat & Dokumen Resmi (Daftar Hadir, Dispensasi, Izin Ortu)
    Route::get('/feature/surat-dokumen', [SuratDokumenController::class, 'index'])->name('feature.surat_dokumen');

    // Kegiatan & Kalender
    Route::get('/feature/Kegiatan', [KegiatanController::class, 'index'])->name('feature.kegiatan');

    // P2KS
    Route::get('/feature/P2KS', [P2ksScheduleController::class, 'index'])->name('p2ks.index');
    Route::post('/feature/P2KS/spin', [P2ksScheduleController::class, 'spin'])->name('p2ks.spin');
    Route::put('/feature/P2KS/{schedule}/status', [P2ksScheduleController::class, 'updateStatus'])->name('p2ks.status');
    Route::post('/feature/P2KS/notified', function () {
        session(['p2ks_notified' => true]);

        return response()->json(['success' => true]);
    })->name('p2ks.notified');

    Route::get('/feature/Kalender', [KalenderController::class, 'index'])->name('feature.kalender');
    Route::post('/feature/Kalender', [KalenderController::class, 'store'])->name('kalender.store');
    Route::put('/feature/Kalender/{agenda}', [KalenderController::class, 'update'])->name('kalender.update');
    Route::delete('/feature/Kalender/{agenda}', [KalenderController::class, 'destroy'])->name('kalender.destroy');

    // Event Module
    Route::resource('feature/Event', EventController::class)->names('feature.event');

    // Event Committees
    Route::post('/feature/Event/{event}/committee', [EventCommitteeController::class, 'storeSection'])->name('event.committee.store');
    Route::delete('/feature/Event/{event}/committee/{committee}', [EventCommitteeController::class, 'destroySection'])->name('event.committee.destroy');
    Route::post('/feature/Event/{event}/committee/{committee}/member', [EventCommitteeController::class, 'storeMember'])->name('event.committee.member.store');
    Route::delete('/feature/Event/{event}/committee-member/{member}', [EventCommitteeController::class, 'destroyMember'])->name('event.committee.member.destroy');

    // Event Tasks
    Route::post('/feature/Event/{event}/task', [EventController::class, 'storeTask'])->name('event.task.store');

    // Peserta & Presensi Module
    Route::get('/feature/Peserta', [PesertaController::class, 'index'])->name('peserta.index');
    Route::get('/feature/Peserta/{event}', [PesertaController::class, 'show'])->name('peserta.show');
    Route::post('/feature/Peserta/{event}', [PesertaController::class, 'store'])->name('peserta.store');
    Route::put('/feature/Peserta/{event}/{attendance}', [PesertaController::class, 'update'])->name('peserta.update');
    Route::delete('/feature/Peserta/{event}/{attendance}', [PesertaController::class, 'destroy'])->name('peserta.destroy');

    // Daftar Ulang Module
    Route::get('/feature/Daftar-Ulang', [DaftarUlangController::class, 'index'])->name('feature.daftar_ulang');
    Route::post('/feature/Daftar-Ulang', [DaftarUlangController::class, 'store'])->name('daftar_ulang.store');
    Route::put('/feature/Daftar-Ulang/{reregistration}/status', [DaftarUlangController::class, 'updateStatus'])->name('daftar_ulang.update_status');

    // Verifikasi Anggota Baru & Buku Induk (Khusus Admin, Ketua, & Sekretaris)
    Route::get('/feature/Verifikasi-Anggota', [VerifikasiAnggotaController::class, 'index'])->name('feature.verifikasi_anggota');
    Route::post('/feature/Verifikasi-Anggota/{user}/approve', [VerifikasiAnggotaController::class, 'approve'])->name('verifikasi.approve');
    Route::post('/feature/Verifikasi-Anggota/{user}/reject', [VerifikasiAnggotaController::class, 'reject'])->name('verifikasi.reject');
    Route::post('/feature/Verifikasi-Anggota/application/{application}/approve', [VerifikasiAnggotaController::class, 'approveApplication'])->name('verifikasi.application.approve');
    Route::post('/feature/Verifikasi-Anggota/application/{application}/reject', [VerifikasiAnggotaController::class, 'rejectApplication'])->name('verifikasi.application.reject');
    Route::post('/feature/Verifikasi-Anggota/application/{application}/revision', [VerifikasiAnggotaController::class, 'requestRevisionApplication'])->name('verifikasi.application.revision');
    Route::get('/feature/Verifikasi-Anggota/master/template', [VerifikasiAnggotaController::class, 'templateMasterExcel'])->name('verifikasi.master.template');
    Route::get('/feature/Verifikasi-Anggota/master/export', [VerifikasiAnggotaController::class, 'exportMasterExcel'])->name('verifikasi.master.export');
    Route::get('/feature/Verifikasi-Anggota/master/export-pdf', [VerifikasiAnggotaController::class, 'exportMasterPdf'])->name('verifikasi.master.export_pdf');
    Route::post('/feature/Verifikasi-Anggota/master/import', [VerifikasiAnggotaController::class, 'importMasterExcel'])->name('verifikasi.master.import');
    Route::post('/feature/Verifikasi-Anggota/master', [VerifikasiAnggotaController::class, 'storeMaster'])->name('verifikasi.master.store');
    Route::delete('/feature/Verifikasi-Anggota/master/{masterMember}', [VerifikasiAnggotaController::class, 'destroyMaster'])->name('verifikasi.master.destroy');

    // Task Board Module
    Route::get('/feature/Task-Board', [TaskBoardController::class, 'index'])->name('feature.task_board');
    Route::post('/feature/Task-Board', [TaskBoardController::class, 'store'])->name('task_board.store');
    Route::put('/feature/Task-Board/{task}', [TaskBoardController::class, 'update'])->name('task_board.update');
    Route::put('/feature/Task-Board/{task}/progress', [TaskBoardController::class, 'updateProgress'])->name('task_board.update_progress');
    Route::delete('/feature/Task-Board/{task}', [TaskBoardController::class, 'destroy'])->name('task_board.destroy');

    // Content Management System (Articles, Duta GenRe, Kegiatan)
    Route::get('/admin/konten/artikel', [ContentManagementController::class, 'articlesIndex'])->name('admin.articles.index');
    Route::get('/admin/konten/artikel/create', [ContentManagementController::class, 'articleCreate'])->name('admin.articles.create');
    Route::post('/admin/konten/artikel', [ContentManagementController::class, 'articleStore'])->name('admin.articles.store');
    Route::get('/admin/konten/artikel/{id}/edit', [ContentManagementController::class, 'articleEdit'])->name('admin.articles.edit');
    Route::put('/admin/konten/artikel/{id}', [ContentManagementController::class, 'articleUpdate'])->name('admin.articles.update');
    Route::post('/admin/konten/artikel/{id}/toggle-status', [ContentManagementController::class, 'articleToggleStatus'])->name('admin.articles.toggle-status');
    Route::delete('/admin/konten/artikel/{id}', [ContentManagementController::class, 'articleDestroy'])->name('admin.articles.destroy');

    Route::get('/admin/konten/duta-genre', [ContentManagementController::class, 'dutaIndex'])->name('admin.duta-genre.index');
    Route::get('/admin/konten/duta-genre/{slug}/edit', [ContentManagementController::class, 'dutaEdit'])->name('admin.duta-genre.edit');
    Route::post('/admin/konten/duta-genre/{slug}', [ContentManagementController::class, 'dutaUpdate'])->name('admin.duta-genre.update');

    Route::get('/admin/konten/kegiatan', [ContentManagementController::class, 'kegiatanIndex'])->name('admin.kegiatan.index');
    Route::get('/admin/konten/kegiatan/create', [ContentManagementController::class, 'kegiatanCreate'])->name('admin.kegiatan.create');
    Route::post('/admin/konten/kegiatan', [ContentManagementController::class, 'kegiatanStore'])->name('admin.kegiatan.store');
    Route::get('/admin/konten/kegiatan/{id}/edit', [ContentManagementController::class, 'kegiatanEdit'])->name('admin.kegiatan.edit');
    Route::put('/admin/konten/kegiatan/{id}', [ContentManagementController::class, 'kegiatanUpdate'])->name('admin.kegiatan.update');
    Route::delete('/admin/konten/kegiatan/{id}', [ContentManagementController::class, 'kegiatanDestroy'])->name('admin.kegiatan.destroy');

    Route::get('/feature/{name}', function ($name) {
        $viewName = 'feature.'.Str::slug($name);
        if (view()->exists($viewName)) {
            return view($viewName, compact('name'));
        }

        return view('under-construction');
    })->name('feature.pending');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
