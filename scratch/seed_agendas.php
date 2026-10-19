<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Agenda;

// Truncate
Agenda::truncate();

$monthsData = [
    1 => ['name' => 'Januari 2026', 'events' => [['title' => 'Skakmatt Anniversary', 'date' => '2026-01-09', 'badge' => 'Event', 'image' => 'skakmatt anniversary.jpeg', 'location' => 'SMKN 1 Purwokerto', 'time' => '08:00 - Selesai'], ['title' => 'Skakmattcreatif', 'date' => '2026-01-14', 'badge' => 'Kegiatan', 'image' => 'skakmattcreatif.jpeg', 'location' => 'Aula', 'time' => '08:00 - Selesai']]],
    2 => ['name' => 'Februari 2026', 'events' => [['title' => 'Anjangsini Anjangsana SMKN 1 Purwokerto x Skaga', 'date' => '2026-02-12', 'badge' => 'Kegiatan', 'image' => 'anjangsini anjangsana smecone x skaga.jpeg', 'location' => 'Workshop', 'time' => '08:00 - Selesai']]],
    3 => ['name' => 'Maret 2026', 'events' => [['title' => 'Buka bersama PIK-R SKAKMATT', 'date' => '2026-03-05', 'badge' => 'Kegiatan', 'image' => 'bukber.jpeg', 'location' => 'Samara Cafe', 'time' => '16:00 - Selesai']]],
    4 => ['name' => 'April 2026', 'events' => [['title' => 'Technical Meeting Pemilihan duta genre', 'date' => '2026-04-20', 'badge' => 'Rapat', 'image' => 'tm pildugen.jpeg', 'location' => 'Aula', 'time' => '13:00 - Selesai']]],
    5 => ['name' => 'Mei 2026', 'events' => [['title' => 'Pembekalan pemilihan duta genre dengan Kak Lilia', 'date' => '2026-05-07', 'badge' => 'Kegiatan', 'image' => 'pembekalan pildugen dengan kak lilia.jpeg', 'location' => 'Workshop', 'time' => '13:00 - Selesai'], ['title' => 'Seleksi Pemilihan duta genre', 'date' => '2026-05-13', 'badge' => 'Event', 'image' => 'seleksi pildugen.jpeg', 'location' => 'Aula', 'time' => '08:00 - Selesai'], ['title' => 'Seleksi Penyuluhan', 'date' => '2026-05-18', 'badge' => 'Event', 'image' => 'seleksi penyuluhan.jpeg', 'location' => 'Aula', 'time' => '08:00 - Selesai']]],
    6 => ['name' => 'Juni 2026', 'events' => [['title' => 'Pembekalan Pemilihan duta genre dari Mba Dista', 'date' => '2026-06-11', 'badge' => 'Kegiatan', 'image' => 'pembekalan pildugen dari mba dista.jpeg', 'location' => 'Aula', 'time' => '13:00 - Selesai'], ['title' => 'Pemilihan duta genre', 'date' => '2026-06-17', 'badge' => 'Event', 'image' => 'pildugen.jpeg', 'location' => 'Workshop', 'time' => '08:00 - Selesai']]],
    7 => ['name' => 'Juli 2026', 'events' => [['title' => 'Forum Perdana', 'date' => '2026-07-29', 'badge' => 'Forum Rutin', 'image' => 'forum perdana.jpeg', 'location' => 'Masjid', 'time' => '16:00 - 17:00'], ['title' => 'Skakmatt Goes to SMPN 9 Purwokerto', 'date' => '2026-07-31', 'badge' => 'Kegiatan', 'image' => 'skakmatt goes to SMPN 9 purwokerto.jpeg', 'location' => 'SMPN 9 Purwokerto', 'time' => '08:00 - Selesai']]],
    8 => ['name' => 'Agustus 2026', 'events' => [['title' => 'Skakmatt Discussion Time', 'date' => '2026-08-08', 'badge' => 'Kegiatan', 'image' => 'sdt buken.jpeg', 'location' => 'Buken', 'time' => '09:00 - Selesai'], ['title' => 'Perkenalan dengan Kaka Percabangan', 'date' => '2026-08-12', 'badge' => 'Kegiatan', 'image' => 'perkenalan dengan kaka percabangan.jpeg', 'location' => 'SMKN 1 Purwokerto', 'time' => '13:00 - 15:00']]],
    9 => ['name' => 'September 2026', 'events' => [['title' => 'Reorganisasi PIK-R SKAKMATT 2026', 'date' => '2026-09-05', 'badge' => 'Program Kerja', 'image' => 'reor pikr 2026.jpeg', 'location' => 'Lapangan SMKN 1 Purwokerto', 'time' => '07:00 - Selesai']]]
];

$locations = ['Aula', 'Masjid', 'Workshop'];
$locIndex = 0;

for ($m = 1; $m <= 12; $m++) {
    $start = new DateTime("2026-".str_pad($m, 2, '0', STR_PAD_LEFT)."-01");
    $end = clone $start;
    $end->modify('last day of this month');

    $interval = new DateInterval('P1D');
    $daterange = new DatePeriod($start, $interval, $end->modify('+1 day'));

    $forumCount = 1;
    foreach($daterange as $date){
        if ($date->format('N') == 3) { // Wednesday
            $loc = $locations[$locIndex % 3];
            $locIndex++;

            Agenda::create([
                'title' => 'Forum Rutin Minggu ' . $forumCount,
                'date' => $date->format('Y-m-d'),
                'start_time' => '16:00:00',
                'end_time' => '17:00:00',
                'location' => $loc,
                'category' => 'Forum Rutin',
                'status' => 'Terjadwal'
            ]);
            $forumCount++;
        }
    }

    if (isset($monthsData[$m])) {
        foreach ($monthsData[$m]['events'] as $ev) {
            $timeParts = explode(' - ', $ev['time']);
            $startTime = $timeParts[0] . ':00';
            Agenda::create([
                'title' => $ev['title'],
                'date' => $ev['date'],
                'start_time' => $startTime,
                'location' => $ev['location'],
                'category' => $ev['badge'],
                'status' => 'Terjadwal'
            ]);
        }
    }
}

echo "Seeded Agendas successfully!";
