<?php

$monthsData = [
    1 => ['name' => 'Januari 2026', 'events' => [['title' => 'Skakmatt Anniversary', 'date' => '2026-01-09', 'badge' => 'Anniversary', 'image' => 'skakmatt anniversary.jpeg', 'location' => 'SMKN 1 Purwokerto', 'time' => '08:00 - Selesai'], ['title' => 'Skakmattcreatif', 'date' => '2026-01-14', 'badge' => 'Kreativitas', 'image' => 'skakmattcreatif.jpeg', 'location' => 'Aula', 'time' => '08:00 - Selesai']]],
    2 => ['name' => 'Februari 2026', 'events' => [['title' => 'Anjangsini Anjangsana SMKN 1 Purwokerto x Skaga', 'date' => '2026-02-12', 'badge' => 'Studi Banding', 'image' => 'anjangsini anjangsana smecone x skaga.jpeg', 'location' => 'Workshop', 'time' => '08:00 - Selesai']]],
    3 => ['name' => 'Maret 2026', 'events' => [['title' => 'Buka bersama PIK-R SKAKMATT', 'date' => '2026-03-05', 'badge' => 'Kebersamaan', 'image' => 'bukber.jpeg', 'location' => 'Samara Cafe', 'time' => '16:00 - Selesai']]],
    4 => ['name' => 'April 2026', 'events' => [['title' => 'Technical Meeting Pemilihan duta genre', 'date' => '2026-04-20', 'badge' => 'Technical Meeting', 'image' => 'tm pildugen.jpeg', 'location' => 'Aula', 'time' => '13:00 - Selesai']]],
    5 => ['name' => 'Mei 2026', 'events' => [['title' => 'Pembekalan pemilihan duta genre dengan Kak Lilia', 'date' => '2026-05-07', 'badge' => 'Pembekalan', 'image' => 'pembekalan pildugen dengan kak lilia.jpeg', 'location' => 'Workshop', 'time' => '13:00 - Selesai'], ['title' => 'Seleksi Pemilihan duta genre', 'date' => '2026-05-13', 'badge' => 'Seleksi', 'image' => 'seleksi pildugen.jpeg', 'location' => 'Aula', 'time' => '08:00 - Selesai'], ['title' => 'Seleksi Penyuluhan', 'date' => '2026-05-18', 'badge' => 'Seleksi', 'image' => 'seleksi penyuluhan.jpeg', 'location' => 'Aula', 'time' => '08:00 - Selesai']]],
    6 => ['name' => 'Juni 2026', 'events' => [['title' => 'Pembekalan Pemilihan duta genre dari Mba Dista', 'date' => '2026-06-11', 'badge' => 'Pembekalan', 'image' => 'pembekalan pildugen dari mba dista.jpeg', 'location' => 'Aula', 'time' => '13:00 - Selesai'], ['title' => 'Pemilihan duta genre', 'date' => '2026-06-17', 'badge' => 'Pemilihan Duta', 'image' => 'pildugen.jpeg', 'location' => 'Workshop', 'time' => '08:00 - Selesai']]],
    7 => ['name' => 'Juli 2026', 'events' => [['title' => 'Forum Perdana', 'date' => '2026-07-29', 'badge' => 'Forum Diskusi', 'image' => 'forum perdana.jpeg', 'location' => 'Masjid', 'time' => '16:00 - 17:00'], ['title' => 'Skakmatt Goes to SMPN 9 Purwokerto', 'date' => '2026-07-31', 'badge' => 'Sosialisasi', 'image' => 'skakmatt goes to SMPN 9 purwokerto.jpeg', 'location' => 'SMPN 9 Purwokerto', 'time' => '08:00 - Selesai']]],
    8 => ['name' => 'Agustus 2026', 'events' => [['title' => 'Skakmatt Discussion Time', 'date' => '2026-08-08', 'badge' => 'Discussion Time', 'image' => 'sdt buken.jpeg', 'location' => 'Buken', 'time' => '09:00 - Selesai'], ['title' => 'Perkenalan dengan Kaka Percabangan', 'date' => '2026-08-12', 'badge' => 'Perkenalan', 'image' => 'perkenalan dengan kaka percabangan.jpeg', 'location' => 'SMKN 1 Purwokerto', 'time' => '13:00 - 15:00']]],
    9 => ['name' => 'September 2026', 'events' => [['title' => 'Reorganisasi PIK-R SKAKMATT 2026', 'date' => '2026-09-05', 'badge' => 'Reorganisasi', 'image' => 'reor pikr 2026.jpeg', 'location' => 'Lapangan SMKN 1 Purwokerto', 'time' => '07:00 - Selesai']]]
];

$monthLabels = [1 => 'JAN', 2 => 'FEB', 3 => 'MAR', 4 => 'APR', 5 => 'MEI', 6 => 'JUN', 7 => 'JUL', 8 => 'AGU', 9 => 'SEP'];
$locations = ['Aula', 'Masjid', 'Workshop'];
$locIndex = 0;

$finalData = [];

for ($m = 1; $m <= 9; $m++) {
    $monthKey = $monthsData[$m]['name'];
    $finalData[$monthKey] = [];

    // Add Wednesday forums
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

            $finalData[$monthKey][] = [
                'type' => 'Forum',
                'title' => 'Forum Rutin Minggu ' . $forumCount,
                'date_display' => $date->format('j') . ' ' . explode(' ', $monthKey)[0] . ' 2026',
                'badge' => 'Forum Rutin',
                'month' => $monthLabels[$m],
                'day' => $date->format('d'),
                'location' => $loc,
                'time' => '16:00 - 17:00',
                'image' => 'placeholder.png',
                'docs_link' => 'https://drive.google.com/drive/folders/1aj0xMdVb-TigPADwO_RNf6IlL2fBScND'
            ];
            $forumCount++;
        }
    }

    // Add specific events
    foreach ($monthsData[$m]['events'] as $ev) {
        $dateObj = new DateTime($ev['date']);
        $finalData[$monthKey][] = [
            'type' => 'Kegiatan',
            'title' => $ev['title'],
            'date_display' => $dateObj->format('j') . ' ' . explode(' ', $monthKey)[0] . ' 2026',
            'badge' => $ev['badge'],
            'month' => $monthLabels[$m],
            'day' => $dateObj->format('d'),
            'location' => $ev['location'],
            'time' => $ev['time'],
            'image' => $ev['image'],
            'docs_link' => null
        ];
    }

    // Sort by day
    usort($finalData[$monthKey], function($a, $b) {
        return (int)$a['day'] - (int)$b['day'];
    });
}

// Format as PHP array string for blade
$output = "<?php\n\$months = [\n";
foreach ($finalData as $month => $items) {
    $output .= "    '$month' => [\n";
    foreach ($items as $item) {
        $output .= "        [\n";
        foreach ($item as $k => $v) {
            $val = $v === null ? "null" : "'".addslashes($v)."'";
            $output .= "            '$k' => $val,\n";
        }
        $output .= "        ],\n";
    }
    $output .= "    ],\n";
}
$output .= "];\n?>";
file_put_contents('scratch/output_kegiatan.php', $output);
echo "Done";
