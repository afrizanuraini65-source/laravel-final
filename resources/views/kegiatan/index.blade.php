@extends('layouts.admin')

@section('title', 'Kegiatan & Forum - PIK-R SKAKMATT')

@section('header', 'Kegiatan & Forum')

@section('content')
@php
$months = [
    'Januari 2026' => [
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 1',
            'date_display' => '7 Januari 2026',
            'badge' => 'Forum Rutin',
            'month' => 'JAN',
            'day' => '07',
            'location' => 'Aula',
            'time' => '16:00 - 17:00',
            'image' => 'aula1.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Skakmatt Anniversary',
            'date_display' => '9 Januari 2026',
            'badge' => 'Anniversary',
            'month' => 'JAN',
            'day' => '09',
            'location' => 'SMKN 1 Purwokerto',
            'time' => '08:00 - Selesai',
            'image' => 'skakmatt anniversary.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/15bjcnu4CEvXNFGXm3LgWdsgklDCJ2XD0?usp=drive_link',
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 2',
            'date_display' => '14 Januari 2026',
            'badge' => 'Forum Rutin',
            'month' => 'JAN',
            'day' => '14',
            'location' => 'Masjid',
            'time' => '16:00 - 17:00',
            'image' => 'masjid1.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Skakmattcreatif',
            'date_display' => '14 Januari 2026',
            'badge' => 'Kreativitas',
            'month' => 'JAN',
            'day' => '14',
            'location' => 'Aula',
            'time' => '08:00 - Selesai',
            'image' => 'skakmattcreatif.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/11f0F8dGjgYnBkkmiVb4wOuJ_DKhg3256?usp=drive_link',
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 3',
            'date_display' => '21 Januari 2026',
            'badge' => 'Forum Rutin',
            'month' => 'JAN',
            'day' => '21',
            'location' => 'Workshop',
            'time' => '16:00 - 17:00',
            'image' => 'wks1.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 4',
            'date_display' => '28 Januari 2026',
            'badge' => 'Forum Rutin',
            'month' => 'JAN',
            'day' => '28',
            'location' => 'Aula',
            'time' => '16:00 - 17:00',
            'image' => 'aula2.jpeg',
            'docs_link' => null,
        ],
    ],
    'Februari 2026' => [
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 1',
            'date_display' => '4 Februari 2026',
            'badge' => 'Forum Rutin',
            'month' => 'FEB',
            'day' => '04',
            'location' => 'Masjid',
            'time' => '16:00 - 17:00',
            'image' => 'masjid2.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 2',
            'date_display' => '11 Februari 2026',
            'badge' => 'Forum Rutin',
            'month' => 'FEB',
            'day' => '11',
            'location' => 'Workshop',
            'time' => '16:00 - 17:00',
            'image' => 'wks2.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Anjangsini Anjangsana SMKN 1 Purwokerto x Skaga',
            'date_display' => '12 Februari 2026',
            'badge' => 'Studi Banding',
            'month' => 'FEB',
            'day' => '12',
            'location' => 'Workshop',
            'time' => '08:00 - Selesai',
            'image' => 'anjangsini anjangsana smecone x skaga.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1wDVZYRVZPkOHxGpL0wGlnj1tYpaxzABb?usp=drive_link',
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 3',
            'date_display' => '18 Februari 2026',
            'badge' => 'Forum Rutin',
            'month' => 'FEB',
            'day' => '18',
            'location' => 'Aula',
            'time' => '16:00 - 17:00',
            'image' => 'aula3.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 4',
            'date_display' => '25 Februari 2026',
            'badge' => 'Forum Rutin',
            'month' => 'FEB',
            'day' => '25',
            'location' => 'Masjid',
            'time' => '16:00 - 17:00',
            'image' => 'masjid3.jpg',
            'docs_link' => null,
        ],
    ],
    'Maret 2026' => [
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 1',
            'date_display' => '4 Maret 2026',
            'badge' => 'Forum Rutin',
            'month' => 'MAR',
            'day' => '04',
            'location' => 'Workshop',
            'time' => '16:00 - 17:00',
            'image' => 'wks3.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Buka bersama PIK-R SKAKMATT',
            'date_display' => '5 Maret 2026',
            'badge' => 'Kebersamaan',
            'month' => 'MAR',
            'day' => '05',
            'location' => 'Samara Cafe',
            'time' => '16:00 - Selesai',
            'image' => 'bukber.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/12zZFe4hZC0nT1ojjduUvXLFZjYBLE6vd?usp=drive_link',
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 2',
            'date_display' => '11 Maret 2026',
            'badge' => 'Forum Rutin',
            'month' => 'MAR',
            'day' => '11',
            'location' => 'Aula',
            'time' => '16:00 - 17:00',
            'image' => 'aula4.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 3',
            'date_display' => '18 Maret 2026',
            'badge' => 'Forum Rutin',
            'month' => 'MAR',
            'day' => '18',
            'location' => 'Masjid',
            'time' => '16:00 - 17:00',
            'image' => 'masjid4.jpg',
            'docs_link' => null,
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 4',
            'date_display' => '25 Maret 2026',
            'badge' => 'Forum Rutin',
            'month' => 'MAR',
            'day' => '25',
            'location' => 'Workshop',
            'time' => '16:00 - 17:00',
            'image' => 'wks4.jpeg',
            'docs_link' => null,
        ],
    ],
    'April 2026' => [
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 1',
            'date_display' => '1 April 2026',
            'badge' => 'Forum Rutin',
            'month' => 'APR',
            'day' => '01',
            'location' => 'Aula',
            'time' => '16:00 - 17:00',
            'image' => 'aula5.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 2',
            'date_display' => '8 April 2026',
            'badge' => 'Forum Rutin',
            'month' => 'APR',
            'day' => '08',
            'location' => 'Masjid',
            'time' => '16:00 - 17:00',
            'image' => 'masjid5.jpg',
            'docs_link' => null,
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 3',
            'date_display' => '15 April 2026',
            'badge' => 'Forum Rutin',
            'month' => 'APR',
            'day' => '15',
            'location' => 'Workshop',
            'time' => '16:00 - 17:00',
            'image' => 'wks5.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Technical Meeting Pemilihan duta genre',
            'date_display' => '20 April 2026',
            'badge' => 'Technical Meeting',
            'month' => 'APR',
            'day' => '20',
            'location' => 'Aula',
            'time' => '13:00 - Selesai',
            'image' => 'tm pildugen.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1LTYgzh6aLOz71iFIessXnK4l-BLo8Eii?usp=drive_link',
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 4',
            'date_display' => '22 April 2026',
            'badge' => 'Forum Rutin',
            'month' => 'APR',
            'day' => '22',
            'location' => 'Aula',
            'time' => '16:00 - 17:00',
            'image' => 'aula6.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 5',
            'date_display' => '29 April 2026',
            'badge' => 'Forum Rutin',
            'month' => 'APR',
            'day' => '29',
            'location' => 'Masjid',
            'time' => '16:00 - 17:00',
            'image' => 'masjid6.jpg',
            'docs_link' => null,
        ],
    ],
    'Mei 2026' => [
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 1',
            'date_display' => '6 Mei 2026',
            'badge' => 'Forum Rutin',
            'month' => 'MEI',
            'day' => '06',
            'location' => 'Workshop',
            'time' => '16:00 - 17:00',
            'image' => 'wks6.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Pembekalan pemilihan duta genre dengan Kak Lilia',
            'date_display' => '7 Mei 2026',
            'badge' => 'Pembekalan',
            'month' => 'MEI',
            'day' => '07',
            'location' => 'Workshop',
            'time' => '13:00 - Selesai',
            'image' => 'pembekalan pildugen dengan kak lilia.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/15pkk7B0Je0jOulr18kTcSS-33UoUnL1X?usp=drive_link',
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 2',
            'date_display' => '13 Mei 2026',
            'badge' => 'Forum Rutin',
            'month' => 'MEI',
            'day' => '13',
            'location' => 'Aula',
            'time' => '16:00 - 17:00',
            'image' => 'aula7.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Seleksi Pemilihan duta genre',
            'date_display' => '13 Mei 2026',
            'badge' => 'Seleksi',
            'month' => 'MEI',
            'day' => '13',
            'location' => 'Aula',
            'time' => '08:00 - Selesai',
            'image' => 'seleksi pildugen.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1dGDGBv1DYUyFr50crHf1ESH3bYhurMA4?usp=drive_link',
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Seleksi Penyuluhan',
            'date_display' => '18 Mei 2026',
            'badge' => 'Seleksi',
            'month' => 'MEI',
            'day' => '18',
            'location' => 'Aula',
            'time' => '08:00 - Selesai',
            'image' => 'seleksi penyuluhan.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1puYpLyZECGQkiWpJsb7j9knLUBe6FE0-?usp=drive_link',
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 3',
            'date_display' => '20 Mei 2026',
            'badge' => 'Forum Rutin',
            'month' => 'MEI',
            'day' => '20',
            'location' => 'Masjid',
            'time' => '16:00 - 17:00',
            'image' => 'masjid7.jpg',
            'docs_link' => null,
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 4',
            'date_display' => '27 Mei 2026',
            'badge' => 'Forum Rutin',
            'month' => 'MEI',
            'day' => '27',
            'location' => 'Workshop',
            'time' => '16:00 - 17:00',
            'image' => 'wks1.jpeg',
            'docs_link' => null,
        ],
    ],
    'Juni 2026' => [
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 1',
            'date_display' => '3 Juni 2026',
            'badge' => 'Forum Rutin',
            'month' => 'JUN',
            'day' => '03',
            'location' => 'Aula',
            'time' => '16:00 - 17:00',
            'image' => 'aula8.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 2',
            'date_display' => '10 Juni 2026',
            'badge' => 'Forum Rutin',
            'month' => 'JUN',
            'day' => '10',
            'location' => 'Masjid',
            'time' => '16:00 - 17:00',
            'image' => 'masjid8.jpg',
            'docs_link' => null,
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Pembekalan Pemilihan duta genre dari Mba Dista',
            'date_display' => '11 Juni 2026',
            'badge' => 'Pembekalan',
            'month' => 'JUN',
            'day' => '11',
            'location' => 'Aula',
            'time' => '13:00 - Selesai',
            'image' => 'pembekalan pildugen dari mba dista.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1WU8d1_-SlnHeYi21h1ORlcslv2AqYKUB?usp=drive_link',
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 3',
            'date_display' => '17 Juni 2026',
            'badge' => 'Forum Rutin',
            'month' => 'JUN',
            'day' => '17',
            'location' => 'Workshop',
            'time' => '16:00 - 17:00',
            'image' => 'wks2.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Pemilihan duta genre',
            'date_display' => '17 Juni 2026',
            'badge' => 'Pemilihan Duta',
            'month' => 'JUN',
            'day' => '17',
            'location' => 'Workshop',
            'time' => '08:00 - Selesai',
            'image' => 'pildugen.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1TppstzSEojbIA-dfLkQxsCbpbI3dERkK?usp=drive_link',
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 4',
            'date_display' => '24 Juni 2026',
            'badge' => 'Forum Rutin',
            'month' => 'JUN',
            'day' => '24',
            'location' => 'Aula',
            'time' => '16:00 - 17:00',
            'image' => 'aula9.jpeg',
            'docs_link' => null,
        ],
    ],
    'Juli 2026' => [
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 1',
            'date_display' => '1 Juli 2026',
            'badge' => 'Forum Rutin',
            'month' => 'JUL',
            'day' => '01',
            'location' => 'Masjid',
            'time' => '16:00 - 17:00',
            'image' => 'masjid1.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 2',
            'date_display' => '8 Juli 2026',
            'badge' => 'Forum Rutin',
            'month' => 'JUL',
            'day' => '08',
            'location' => 'Workshop',
            'time' => '16:00 - 17:00',
            'image' => 'wks3.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 3',
            'date_display' => '15 Juli 2026',
            'badge' => 'Forum Rutin',
            'month' => 'JUL',
            'day' => '15',
            'location' => 'Aula',
            'time' => '16:00 - 17:00',
            'image' => 'aula10.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 4',
            'date_display' => '22 Juli 2026',
            'badge' => 'Forum Rutin',
            'month' => 'JUL',
            'day' => '22',
            'location' => 'Masjid',
            'time' => '16:00 - 17:00',
            'image' => 'masjid2.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 5',
            'date_display' => '29 Juli 2026',
            'badge' => 'Forum Rutin',
            'month' => 'JUL',
            'day' => '29',
            'location' => 'Workshop',
            'time' => '16:00 - 17:00',
            'image' => 'wks4.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Forum Perdana',
            'date_display' => '29 Juli 2026',
            'badge' => 'Forum Diskusi',
            'month' => 'JUL',
            'day' => '29',
            'location' => 'Masjid',
            'time' => '16:00 - 17:00',
            'image' => 'forum perdana.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1aj0xMdVb-TigPADwO_RNf6IlL2fBScND',
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Skakmatt Goes to SMPN 9 Purwokerto',
            'date_display' => '31 Juli 2026',
            'badge' => 'Sosialisasi',
            'month' => 'JUL',
            'day' => '31',
            'location' => 'SMPN 9 Purwokerto',
            'time' => '08:00 - Selesai',
            'image' => 'skakmatt goes to SMPN 9 purwokerto.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1KWQPDT682542Uk_ZosdOKvRc8Cb2lW5W?usp=drive_link',
        ],
    ],
    'Agustus 2026' => [
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 1',
            'date_display' => '5 Agustus 2026',
            'badge' => 'Forum Rutin',
            'month' => 'AGU',
            'day' => '05',
            'location' => 'Aula',
            'time' => '16:00 - 17:00',
            'image' => 'aula11.jpg',
            'docs_link' => null,
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Skakmatt Discussion Time',
            'date_display' => '8 Agustus 2026',
            'badge' => 'Discussion Time',
            'month' => 'AGU',
            'day' => '08',
            'location' => 'Buken',
            'time' => '09:00 - Selesai',
            'image' => 'sdt buken.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1bk0NXfF9OOnbRuF5GhDlsFC8ZKTy6Ea2?usp=drive_link',
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 2',
            'date_display' => '12 Agustus 2026',
            'badge' => 'Forum Rutin',
            'month' => 'AGU',
            'day' => '12',
            'location' => 'Masjid',
            'time' => '16:00 - 17:00',
            'image' => 'masjid3.jpg',
            'docs_link' => null,
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Perkenalan dengan Kaka Percabangan',
            'date_display' => '12 Agustus 2026',
            'badge' => 'Perkenalan',
            'month' => 'AGU',
            'day' => '12',
            'location' => 'SMKN 1 Purwokerto',
            'time' => '13:00 - 15:00',
            'image' => 'perkenalan dengan kaka percabangan.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1mZrJ-_LcmIyAsyFgCPyOHMj8DH1VnKpZ?usp=drive_link',
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 3',
            'date_display' => '19 Agustus 2026',
            'badge' => 'Forum Rutin',
            'month' => 'AGU',
            'day' => '19',
            'location' => 'Workshop',
            'time' => '16:00 - 17:00',
            'image' => 'wks5.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 4',
            'date_display' => '26 Agustus 2026',
            'badge' => 'Forum Rutin',
            'month' => 'AGU',
            'day' => '26',
            'location' => 'Aula',
            'time' => '16:00 - 17:00',
            'image' => 'aula12.jpg',
            'docs_link' => null,
        ],
    ],
    'September 2026' => [
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 1',
            'date_display' => '2 September 2026',
            'badge' => 'Forum Rutin',
            'month' => 'SEP',
            'day' => '02',
            'location' => 'Masjid',
            'time' => '16:00 - 17:00',
            'image' => 'masjid4.jpg',
            'docs_link' => null,
        ],
        [
            'type' => 'Kegiatan',
            'title' => 'Reorganisasi PIK-R SKAKMATT 2026',
            'date_display' => '5 September 2026',
            'badge' => 'Reorganisasi',
            'month' => 'SEP',
            'day' => '05',
            'location' => 'Lapangan SMKN 1 Purwokerto',
            'time' => '07:00 - Selesai',
            'image' => 'reor pikr 2026.jpeg',
            'docs_link' => 'https://drive.google.com/drive/folders/1tclsu0qimi39mUnUNwvcNMsEq3WoReo6?usp=drive_link',
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 2',
            'date_display' => '9 September 2026',
            'badge' => 'Forum Rutin',
            'month' => 'SEP',
            'day' => '09',
            'location' => 'Workshop',
            'time' => '16:00 - 17:00',
            'image' => 'wks6.jpeg',
            'docs_link' => null,
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 3',
            'date_display' => '16 September 2026',
            'badge' => 'Forum Rutin',
            'month' => 'SEP',
            'day' => '16',
            'location' => 'Aula',
            'time' => '16:00 - 17:00',
            'image' => 'aula13.jpg',
            'docs_link' => null,
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 4',
            'date_display' => '23 September 2026',
            'badge' => 'Forum Rutin',
            'month' => 'SEP',
            'day' => '23',
            'location' => 'Masjid',
            'time' => '16:00 - 17:00',
            'image' => 'masjid5.jpg',
            'docs_link' => null,
        ],
        [
            'type' => 'Forum',
            'title' => 'Forum Rutin Minggu 5',
            'date_display' => '30 September 2026',
            'badge' => 'Forum Rutin',
            'month' => 'SEP',
            'day' => '30',
            'location' => 'Workshop',
            'time' => '16:00 - 17:00',
            'image' => 'wks1.jpeg',
            'docs_link' => null,
        ],
    ],
];
@endphp

<div class="px-6 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10">
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Kegiatan <span class="text-teal-400">&</span> Forum</h1>
            <p class="text-slate-400 mt-2">Daftar kegiatan, event besar, dan jadwal forum rutin PIK-R SKAKMATT.</p>
        </div>

        @foreach($months as $monthName => $items)
        <div class="mb-12">
            <!-- Header Bulan -->
            <div class="flex items-center gap-4 mb-6">
                <h2 class="text-2xl font-bold text-white">{{ $monthName }}</h2>
                <div class="h-px bg-slate-700/50 flex-1"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($items as $item)
                <!-- Card (Mengadopsi gaya dari navbar public) -->
                <div class="group bg-slate-800/80 backdrop-blur-md rounded-3xl overflow-hidden border border-slate-700 shadow-xl flex flex-col transition-all duration-300 hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-emerald-900/20 hover:border-emerald-500/40">
                    
                    <!-- Image & Date Badge -->
                    <div class="relative h-44 w-full overflow-hidden bg-slate-900">
                        <img src="{{ asset('images/' . $item['image']) }}" 
                             alt="{{ $item['title'] }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                             onerror="this.src='https://placehold.co/600x400/1e293b/334155?text=Forum+Rutin'">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-black/20 opacity-80 group-hover:opacity-60 transition-opacity duration-300"></div>

                        <!-- Date Badge Overlay -->
                        <div class="absolute top-3 left-3 bg-slate-900/90 backdrop-blur-md border border-white/10 rounded-2xl px-3 py-1.5 flex flex-col items-center justify-center text-center shadow-lg">
                            <span class="text-[9px] font-extrabold uppercase tracking-widest text-teal-400">{{ $item['month'] }}</span>
                            <span class="text-base font-black leading-tight text-white">{{ $item['day'] }}</span>
                        </div>

                        <!-- Category Badge Overlay -->
                        <div class="absolute top-3 right-3 bg-emerald-500/90 backdrop-blur-md text-white text-[9px] font-bold px-2 py-1 rounded-full uppercase tracking-wider border border-white/20 shadow-lg">
                            {{ $item['badge'] }}
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-5 flex flex-col flex-grow justify-between">
                        <div>
                            <h3 class="text-base font-bold text-white mb-3 leading-snug group-hover:text-teal-400 transition-colors line-clamp-2">
                                {{ $item['title'] }}
                            </h3>

                            <div class="text-xs text-slate-300 space-y-2 mt-auto">
                                <!-- Location -->
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded bg-slate-700/50 flex items-center justify-center text-teal-400 shrink-0 border border-slate-600">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <span class="font-medium truncate">{{ $item['location'] }}</span>
                                </div>
                                
                                <!-- Time -->
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded bg-slate-700/50 flex items-center justify-center text-teal-400 shrink-0 border border-slate-600">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <span class="font-medium truncate">{{ $item['time'] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Link Dokumentasi (Untuk Forum) -->
                        @if($item['docs_link'])
                            <div class="mt-4 pt-4 border-t border-slate-700/50">
                                <a href="{{ $item['docs_link'] }}" target="_blank" class="w-full flex items-center justify-center gap-2 py-2 px-4 rounded-xl bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500 hover:text-white border border-indigo-500/20 transition-all text-xs font-bold shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                                    Akses Link Dokumentasi
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

    </div>
</div>
@endsection
