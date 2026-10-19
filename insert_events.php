<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Event;
use Illuminate\Support\Str;

$months = [
    'Januari 2026' => [
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 1',
            'date' => '2026-01-07',
            'category' => 'Edukasi',
            'location' => 'Aula',
            'time' => '16:00',
            'image' => 'aula1.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Skakmatt Anniversary',
            'date' => '2026-01-09',
            'category' => 'Kegiatan',
            'location' => 'SMKN 1 Purwokerto',
            'time' => '08:00',
            'image' => 'skakmatt anniversary.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/15bjcnu4CEvXNFGXm3LgWdsgklDCJ2XD0?usp=drive_link',
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 2',
            'date' => '2026-01-14',
            'category' => 'Edukasi',
            'location' => 'Masjid',
            'time' => '16:00',
            'image' => 'masjid1.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Skakmattcreatif',
            'date' => '2026-01-14',
            'category' => 'Kegiatan',
            'location' => 'Aula',
            'time' => '08:00',
            'image' => 'skakmattcreatif.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/11f0F8dGjgYnBkkmiVb4wOuJ_DKhg3256?usp=drive_link',
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 3',
            'date' => '2026-01-21',
            'category' => 'Edukasi',
            'location' => 'Workshop',
            'time' => '16:00',
            'image' => 'wks1.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 4',
            'date' => '2026-01-28',
            'category' => 'Edukasi',
            'location' => 'Aula',
            'time' => '16:00',
            'image' => 'aula2.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
    ],
    'Februari 2026' => [
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 1',
            'date' => '2026-02-04',
            'category' => 'Edukasi',
            'location' => 'Masjid',
            'time' => '16:00',
            'image' => 'masjid2.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 2',
            'date' => '2026-02-11',
            'category' => 'Edukasi',
            'location' => 'Workshop',
            'time' => '16:00',
            'image' => 'wks2.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Anjangsini Anjangsana SMKN 1 Purwokerto x Skaga',
            'date' => '2026-02-12',
            'category' => 'Kegiatan',
            'location' => 'Workshop',
            'time' => '08:00',
            'image' => 'anjangsini anjangsana smecone x skaga.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1wDVZYRVZPkOHxGpL0wGlnj1tYpaxzABb?usp=drive_link',
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 3',
            'date' => '2026-02-18',
            'category' => 'Edukasi',
            'location' => 'Aula',
            'time' => '16:00',
            'image' => 'aula3.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 4',
            'date' => '2026-02-25',
            'category' => 'Edukasi',
            'location' => 'Masjid',
            'time' => '16:00',
            'image' => 'masjid3.jpg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
    ],
    'Maret 2026' => [
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 1',
            'date' => '2026-03-04',
            'category' => 'Edukasi',
            'location' => 'Workshop',
            'time' => '16:00',
            'image' => 'wks3.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Buka bersama PIK-R SKAKMATT',
            'date' => '2026-03-05',
            'category' => 'Kegiatan',
            'location' => 'Samara Cafe',
            'time' => '16:00',
            'image' => 'bukber.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/12zZFe4hZC0nT1ojjduUvXLFZjYBLE6vd?usp=drive_link',
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 2',
            'date' => '2026-03-11',
            'category' => 'Edukasi',
            'location' => 'Aula',
            'time' => '16:00',
            'image' => 'aula4.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 3',
            'date' => '2026-03-18',
            'category' => 'Edukasi',
            'location' => 'Masjid',
            'time' => '16:00',
            'image' => 'masjid4.jpg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 4',
            'date' => '2026-03-25',
            'category' => 'Edukasi',
            'location' => 'Workshop',
            'time' => '16:00',
            'image' => 'wks4.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
    ],
    'April 2026' => [
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 1',
            'date' => '2026-04-01',
            'category' => 'Edukasi',
            'location' => 'Aula',
            'time' => '16:00',
            'image' => 'aula5.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 2',
            'date' => '2026-04-08',
            'category' => 'Edukasi',
            'location' => 'Masjid',
            'time' => '16:00',
            'image' => 'masjid5.jpg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 3',
            'date' => '2026-04-15',
            'category' => 'Edukasi',
            'location' => 'Workshop',
            'time' => '16:00',
            'image' => 'wks5.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Technical Meeting Pemilihan duta genre',
            'date' => '2026-04-20',
            'category' => 'Kegiatan',
            'location' => 'Aula',
            'time' => '13:00',
            'image' => 'tm pildugen.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1LTYgzh6aLOz71iFIessXnK4l-BLo8Eii?usp=drive_link',
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 4',
            'date' => '2026-04-22',
            'category' => 'Edukasi',
            'location' => 'Aula',
            'time' => '16:00',
            'image' => 'aula6.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 5',
            'date' => '2026-04-29',
            'category' => 'Edukasi',
            'location' => 'Masjid',
            'time' => '16:00',
            'image' => 'masjid6.jpg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
    ],
    'Mei 2026' => [
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 1',
            'date' => '2026-05-06',
            'category' => 'Edukasi',
            'location' => 'Workshop',
            'time' => '16:00',
            'image' => 'wks6.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Pembekalan pemilihan duta genre dengan Kak Lilia',
            'date' => '2026-05-07',
            'category' => 'Edukasi',
            'location' => 'Workshop',
            'time' => '13:00',
            'image' => 'pembekalan pildugen dengan kak lilia.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/15pkk7B0Je0jOulr18kTcSS-33UoUnL1X?usp=drive_link',
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 2',
            'date' => '2026-05-13',
            'category' => 'Edukasi',
            'location' => 'Aula',
            'time' => '16:00',
            'image' => 'aula7.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Seleksi Pemilihan duta genre',
            'date' => '2026-05-13',
            'category' => 'Kegiatan',
            'location' => 'Aula',
            'time' => '08:00',
            'image' => 'seleksi pildugen.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1dGDGBv1DYUyFr50crHf1ESH3bYhurMA4?usp=drive_link',
            'status' => 'Selesai'
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Seleksi Penyuluhan',
            'date' => '2026-05-18',
            'category' => 'Kegiatan',
            'location' => 'Aula',
            'time' => '08:00',
            'image' => 'seleksi penyuluhan.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1puYpLyZECGQkiWpJsb7j9knLUBe6FE0-?usp=drive_link',
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 3',
            'date' => '2026-05-20',
            'category' => 'Edukasi',
            'location' => 'Masjid',
            'time' => '16:00',
            'image' => 'masjid7.jpg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 4',
            'date' => '2026-05-27',
            'category' => 'Edukasi',
            'location' => 'Workshop',
            'time' => '16:00',
            'image' => 'wks1.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
    ],
    'Juni 2026' => [
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 1',
            'date' => '2026-06-03',
            'category' => 'Edukasi',
            'location' => 'Aula',
            'time' => '16:00',
            'image' => 'aula8.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 2',
            'date' => '2026-06-10',
            'category' => 'Edukasi',
            'location' => 'Masjid',
            'time' => '16:00',
            'image' => 'masjid8.jpg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Pembekalan Pemilihan duta genre dari Mba Dista',
            'date' => '2026-06-11',
            'category' => 'Edukasi',
            'location' => 'Aula',
            'time' => '13:00',
            'image' => 'pembekalan pildugen dari mba dista.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1WU8d1_-SlnHeYi21h1ORlcslv2AqYKUB?usp=drive_link',
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 3',
            'date' => '2026-06-17',
            'category' => 'Edukasi',
            'location' => 'Workshop',
            'time' => '16:00',
            'image' => 'wks2.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Pemilihan duta genre',
            'date' => '2026-06-17',
            'category' => 'Kegiatan',
            'location' => 'Workshop',
            'time' => '08:00',
            'image' => 'pildugen.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1TppstzSEojbIA-dfLkQxsCbpbI3dERkK?usp=drive_link',
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 4',
            'date' => '2026-06-24',
            'category' => 'Edukasi',
            'location' => 'Aula',
            'time' => '16:00',
            'image' => 'aula9.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
    ],
    'Juli 2026' => [
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 1',
            'date' => '2026-07-01',
            'category' => 'Edukasi',
            'location' => 'Masjid',
            'time' => '16:00',
            'image' => 'masjid1.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 2',
            'date' => '2026-07-08',
            'category' => 'Edukasi',
            'location' => 'Workshop',
            'time' => '16:00',
            'image' => 'wks3.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 3',
            'date' => '2026-07-15',
            'category' => 'Edukasi',
            'location' => 'Aula',
            'time' => '16:00',
            'image' => 'aula10.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 4',
            'date' => '2026-07-22',
            'category' => 'Edukasi',
            'location' => 'Masjid',
            'time' => '16:00',
            'image' => 'masjid2.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 5',
            'date' => '2026-07-29',
            'category' => 'Edukasi',
            'location' => 'Workshop',
            'time' => '16:00',
            'image' => 'wks4.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Forum Perdana',
            'date' => '2026-07-29',
            'category' => 'Kegiatan',
            'location' => 'Masjid',
            'time' => '16:00',
            'image' => 'forum perdana.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1aj0xMdVb-TigPADwO_RNf6IlL2fBScND',
            'status' => 'Selesai'
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Skakmatt Goes to SMPN 9 Purwokerto',
            'date' => '2026-07-31',
            'category' => 'Kampanye',
            'location' => 'SMPN 9 Purwokerto',
            'time' => '08:00',
            'image' => 'skakmatt goes to SMPN 9 purwokerto.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1KWQPDT682542Uk_ZosdOKvRc8Cb2lW5W?usp=drive_link',
            'status' => 'Selesai'
        ],
    ],
    'Agustus 2026' => [
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 1',
            'date' => '2026-08-05',
            'category' => 'Edukasi',
            'location' => 'Aula',
            'time' => '16:00',
            'image' => 'aula11.jpg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Skakmatt Discussion Time',
            'date' => '2026-08-08',
            'category' => 'Edukasi',
            'location' => 'Buken',
            'time' => '09:00',
            'image' => 'sdt buken.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1bk0NXfF9OOnbRuF5GhDlsFC8ZKTy6Ea2?usp=drive_link',
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 2',
            'date' => '2026-08-12',
            'category' => 'Edukasi',
            'location' => 'Masjid',
            'time' => '16:00',
            'image' => 'masjid3.jpg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Perkenalan dengan Kaka Percabangan',
            'date' => '2026-08-12',
            'category' => 'Kegiatan',
            'location' => 'SMKN 1 Purwokerto',
            'time' => '13:00',
            'image' => 'perkenalan dengan kaka percabangan.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1mZrJ-_LcmIyAsyFgCPyOHMj8DH1VnKpZ?usp=drive_link',
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 3',
            'date' => '2026-08-19',
            'category' => 'Edukasi',
            'location' => 'Workshop',
            'time' => '16:00',
            'image' => 'wks5.jpeg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 4',
            'date' => '2026-08-26',
            'category' => 'Edukasi',
            'location' => 'Aula',
            'time' => '16:00',
            'image' => 'aula12.jpg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
    ],
    'September 2026' => [
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 1',
            'date' => '2026-09-02',
            'category' => 'Edukasi',
            'location' => 'Masjid',
            'time' => '16:00',
            'image' => 'masjid4.jpg',
            'docs_link' => null,
            'status' => 'Selesai'
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Reorganisasi PIK-R SKAKMATT 2026',
            'date' => '2026-09-05',
            'category' => 'Kegiatan',
            'location' => 'Lapangan SMKN 1 Purwokerto',
            'time' => '07:00',
            'image' => 'reor pikr 2026.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1tclsu0qimi39mUnUNwvcNMsEq3WoReo6?usp=drive_link',
            'status' => 'Akan Datang'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 2',
            'date' => '2026-09-09',
            'category' => 'Edukasi',
            'location' => 'Workshop',
            'time' => '16:00',
            'image' => 'wks6.jpeg',
            'docs_link' => null,
            'status' => 'Akan Datang'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 3',
            'date' => '2026-09-16',
            'category' => 'Edukasi',
            'location' => 'Aula',
            'time' => '16:00',
            'image' => 'aula13.jpg',
            'docs_link' => null,
            'status' => 'Akan Datang'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 4',
            'date' => '2026-09-23',
            'category' => 'Edukasi',
            'location' => 'Masjid',
            'time' => '16:00',
            'image' => 'masjid5.jpg',
            'docs_link' => null,
            'status' => 'Akan Datang'
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 5',
            'date' => '2026-09-30',
            'category' => 'Edukasi',
            'location' => 'Workshop',
            'time' => '16:00',
            'image' => 'wks1.jpeg',
            'docs_link' => null,
            'status' => 'Akan Datang'
        ],
    ],
];

use Illuminate\Support\Facades\DB;
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
DB::table('event_registrations')->truncate();
Event::truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

foreach ($months as $month => $events) {
    foreach ($events as $event) {
        // Set all to Selesai except those in future
        $status = 'Selesai';
        if (strtotime($event['date']) > time()) {
            $status = 'Akan Datang';
        }

        Event::create([
            'title' => $event['title'],
            'slug' => Str::slug($event['title'] . '-' . $event['date']),
            'description' => 'Kegiatan ' . $event['title'] . ' yang diadakan pada tanggal ' . $event['date'] . '.',
            'category' => $event['category'],
            'date' => $event['date'],
            'time' => $event['time'],
            'location' => $event['location'],
            'banner_image' => $event['image'],
            'docs_link' => $event['docs_link'],
            'status' => $status,
        ]);
    }
}

echo "Events populated successfully!";
