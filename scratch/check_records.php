<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Attendance;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Contracts\Console\Kernel;

echo "========================================\n";
echo "       DATA PRESENSI & EVENT PIK-R       \n";
echo "========================================\n\n";

echo "=== DAFTAR EVENT ===\n";
$events = Event::withCount('attendances')->get();
foreach ($events as $e) {
    echo "ID: {$e->id} | {$e->title} | Tanggal: {$e->date} | Status: {$e->status} | Peserta Tercatat: {$e->attendances_count}\n";
}

echo "\n=== DATA PRESENSI TERBARU ===\n";
$attendances = Attendance::with(['user', 'event'])->latest('id')->take(10)->get();
echo 'Total Record Presensi: '.Attendance::count()."\n";
if ($attendances->isEmpty()) {
    echo "Belum ada presensi yang tercatat.\n";
} else {
    foreach ($attendances as $a) {
        $nama = $a->user ? $a->user->name : 'User #'.$a->user_id;
        $eventTitle = $a->event ? $a->event->title : 'Event #'.$a->event_id;
        $waktu = $a->timestamp ? Carbon::parse($a->timestamp)->format('d M Y H:i') : '-';
        echo "- [{$a->status}] {$nama} | Kegiatan: {$eventTitle} | Waktu: {$waktu}\n";
    }
}

echo "\n========================================\n";
