<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DivisiController extends Controller
{
    private function getDivisiData() {
        return array (
  'Kegiatan' => 
  array (
    0 => 
    array (
      'nama' => 'Salsa Friscila Putri R',
      'jabatan' => 'Koordinator',
      'kelas' => 'XII AKL 4',
    ),
    1 => 
    array (
      'nama' => 'Anggun Syaikhan Zalfa N',
      'jabatan' => 'Sekretaris',
      'kelas' => 'XII AKL 2',
    ),
    2 => 
    array (
      'nama' => 'Desi Mulyani',
      'jabatan' => 'Anggota',
      'kelas' => 'XII AKL 4',
    ),
    3 => 
    array (
      'nama' => 'Prima Kinarya Abdillah',
      'jabatan' => 'Anggota',
      'kelas' => 'XII AKL 4',
    ),
    4 => 
    array (
      'nama' => 'Nadissa Syifa Ramadhani',
      'jabatan' => 'Anggota',
      'kelas' => 'XII AKL 5',
    ),
    5 => 
    array (
      'nama' => 'Hasna\' Tsaniatul Nabila',
      'jabatan' => 'Anggota',
      'kelas' => 'XII DKV 1',
    ),
    6 => 
    array (
      'nama' => 'Salsabila',
      'jabatan' => 'Anggota',
      'kelas' => 'XI AKL 5',
    ),
    7 => 
    array (
      'nama' => 'Yusuf Hengki Fernando I',
      'jabatan' => 'Anggota',
      'kelas' => 'XII DKV 2',
    ),
    8 => 
    array (
      'nama' => 'Elin Suci Septiani',
      'jabatan' => 'Anggota',
      'kelas' => 'XII MPLB',
    ),
    9 => 
    array (
      'nama' => 'Alifah Devina',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PM 3',
    ),
    10 => 
    array (
      'nama' => 'Jenis Tri Andini',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    11 => 
    array (
      'nama' => 'Shamita Aisha Sabrina',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PM 3',
    ),
    12 => 
    array (
      'nama' => 'Nasywa Tsaqib Oxyana H.',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PPLG 2',
    ),
    13 => 
    array (
      'nama' => 'Naydhira Zafkha Juanesya',
      'jabatan' => 'Anggota',
      'kelas' => 'XII TF 1',
    ),
    14 => 
    array (
      'nama' => 'Queensha Vivin Arshela',
      'jabatan' => 'Anggota',
      'kelas' => 'XII TF 2',
    ),
    15 => 
    array (
      'nama' => 'Zazqia Aura Ramadhani',
      'jabatan' => 'Anggota',
      'kelas' => 'XII TJKT 1',
    ),
    16 => 
    array (
      'nama' => 'Livina',
      'jabatan' => 'Anggota',
      'kelas' => 'XII AKL 1',
    ),
    17 => 
    array (
      'nama' => 'Faiza Hidayati',
      'jabatan' => 'Anggota',
      'kelas' => 'XII MPLB 1',
    ),
    18 => 
    array (
      'nama' => 'Annaku Cykal Ramadhani',
      'jabatan' => 'Anggota',
      'kelas' => 'XII TF 1',
    ),
    19 => 
    array (
      'nama' => 'Fatwa Oktafiyani Putri',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PM 2',
    ),
    20 => 
    array (
      'nama' => 'Fatimah Zahra',
      'jabatan' => 'Anggota',
      'kelas' => 'X MPLB 3',
    ),
    21 => 
    array (
      'nama' => 'Dini Dwi Aulia',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    22 => 
    array (
      'nama' => 'Naysila Mulan Devita',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    23 => 
    array (
      'nama' => 'Luthfiana Hasna',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PM 3',
    ),
    24 => 
    array (
      'nama' => 'Nafiza Rimahayu',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PM 2',
    ),
    25 => 
    array (
      'nama' => 'Almira Nur Hana',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    26 => 
    array (
      'nama' => 'Clarrisa Surya Putri A.',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
  ),
  'Humas' => 
  array (
    0 => 
    array (
      'nama' => 'Sekarwangi Putri',
      'jabatan' => 'Koordinator',
      'kelas' => 'XII PPLG 2',
    ),
    1 => 
    array (
      'nama' => 'Fadila Atut Khosiah',
      'jabatan' => 'Sekretaris',
      'kelas' => 'XII AKL 4',
    ),
    2 => 
    array (
      'nama' => 'Risma Amelia',
      'jabatan' => 'Anggota',
      'kelas' => 'XII AKL 1',
    ),
    3 => 
    array (
      'nama' => 'Dwiana Shanti',
      'jabatan' => 'Anggota',
      'kelas' => 'XII AKL 2',
    ),
    4 => 
    array (
      'nama' => 'Rizka Rahmadhani',
      'jabatan' => 'Anggota',
      'kelas' => 'XII AKL 3',
    ),
    5 => 
    array (
      'nama' => 'Fiela Nur Rizkya',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    6 => 
    array (
      'nama' => 'Tri Asih Widyawati',
      'jabatan' => 'Anggota',
      'kelas' => 'XII AKL 5',
    ),
    7 => 
    array (
      'nama' => 'Putri Anom Tiara Sani',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    8 => 
    array (
      'nama' => 'Indah Meilani Putri',
      'jabatan' => 'Anggota',
      'kelas' => 'XII DKV 1',
    ),
    9 => 
    array (
      'nama' => 'Nathania Elysia Rahma',
      'jabatan' => 'Anggota',
      'kelas' => 'XII MPLB 2',
    ),
    10 => 
    array (
      'nama' => 'Alivia Apta Zena',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    11 => 
    array (
      'nama' => 'Hilda Nur Asiah',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PM 2',
    ),
    12 => 
    array (
      'nama' => 'Nadin Juliana Putri',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    13 => 
    array (
      'nama' => 'Hanindya Yumna Humaira',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    14 => 
    array (
      'nama' => 'Melia Berlianti',
      'jabatan' => 'Anggota',
      'kelas' => 'XII AKL 5',
    ),
    15 => 
    array (
      'nama' => 'Shafa Nuril \'Azmi',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PM 3',
    ),
    16 => 
    array (
      'nama' => 'Rifa Amaliana',
      'jabatan' => 'Anggota',
      'kelas' => 'XII MPLB 2',
    ),
    17 => 
    array (
      'nama' => 'Adelia Nur Ramadhan',
      'jabatan' => 'Anggota',
      'kelas' => 'XII TF 1',
    ),
    18 => 
    array (
      'nama' => 'Umnia Sari',
      'jabatan' => 'Anggota',
      'kelas' => 'XII TF 2',
    ),
    19 => 
    array (
      'nama' => 'Kayana Felicia Zuleika',
      'jabatan' => 'Anggota',
      'kelas' => 'XII AKL 1',
    ),
    20 => 
    array (
      'nama' => 'Shaema Syiara Tauhid',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PPLG 2',
    ),
    21 => 
    array (
      'nama' => 'Anndin Eno Dwi Yuliani',
      'jabatan' => 'Anggota',
      'kelas' => 'XII AKL 3',
    ),
  ),
  'PDD' => 
  array (
    0 => 
    array (
      'nama' => 'Hanumara Arya Pramadi',
      'jabatan' => 'Koordinator',
      'kelas' => 'XII DKV 2',
    ),
    1 => 
    array (
      'nama' => 'Aqiilah Mufiida Zahraa',
      'jabatan' => 'Sekretaris',
      'kelas' => 'XII TJKT 1',
    ),
    2 => 
    array (
      'nama' => 'Salsabila',
      'jabatan' => 'Anggota',
      'kelas' => 'XI AKL 5',
    ),
    3 => 
    array (
      'nama' => 'Haaniyah Dwi Rahmawati',
      'jabatan' => 'Anggota',
      'kelas' => 'XII DKV 1',
    ),
    4 => 
    array (
      'nama' => 'Sintia Pratiwi',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    5 => 
    array (
      'nama' => 'Fatimah Hidayanti',
      'jabatan' => 'Anggota',
      'kelas' => 'XII DKV 2',
    ),
    6 => 
    array (
      'nama' => 'Riezelda Keumalasari',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    7 => 
    array (
      'nama' => 'Bana Arvacelena Benggu',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PM 2',
    ),
    8 => 
    array (
      'nama' => 'Ri Zahrani Adila Sanjaya',
      'jabatan' => 'Anggota',
      'kelas' => 'XII MPLB 1',
    ),
    9 => 
    array (
      'nama' => 'Ria Nur Syafa',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PM 2',
    ),
    10 => 
    array (
      'nama' => 'Viony Rahmadhani',
      'jabatan' => 'Anggota',
      'kelas' => 'XII DKV 1',
    ),
    11 => 
    array (
      'nama' => 'Nur Devi Karomina',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    12 => 
    array (
      'nama' => 'Rofiif Agustina',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PPLG 1',
    ),
    13 => 
    array (
      'nama' => 'Safa Staqib Adzkiya',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PPLG 1',
    ),
    14 => 
    array (
      'nama' => 'Afriza Nur \'Aini',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PPLG 2',
    ),
    15 => 
    array (
      'nama' => 'Amira Syifa Rizki V',
      'jabatan' => 'Anggota',
      'kelas' => 'XII TJKT 1',
    ),
    16 => 
    array (
      'nama' => 'Bela Putri Maghfiroh',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    17 => 
    array (
      'nama' => 'Aulia Hanun Salwa Auriye',
      'jabatan' => 'Anggota',
      'kelas' => 'XII AKL 5',
    ),
    18 => 
    array (
      'nama' => 'Najwa Azizah',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    19 => 
    array (
      'nama' => 'Reva Nagali Aningtyas',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PPLG 2',
    ),
    20 => 
    array (
      'nama' => 'Riska Ramadhani Putri',
      'jabatan' => 'Anggota',
      'kelas' => 'XII DKV 2',
    ),
    21 => 
    array (
      'nama' => 'Amalia Candrawati',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    22 => 
    array (
      'nama' => 'Yolanda Marvelia Putri',
      'jabatan' => 'Anggota',
      'kelas' => 'XII AKL 5',
    ),
  ),
  'PKWU' => 
  array (
    0 => 
    array (
      'nama' => 'Syarifah Tazqiah',
      'jabatan' => 'Koordinator',
      'kelas' => 'XI MPLB 4',
    ),
    1 => 
    array (
      'nama' => 'Rovikoh',
      'jabatan' => 'Sekretaris',
      'kelas' => 'XII TF 1',
    ),
    2 => 
    array (
      'nama' => 'Mual`Afah Fiadhinata',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    3 => 
    array (
      'nama' => 'Arghya Rachma Aqeela',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PM 3',
    ),
    4 => 
    array (
      'nama' => 'Hindun Merdekawati A',
      'jabatan' => 'Anggota',
      'kelas' => 'XII AKL 5',
    ),
    5 => 
    array (
      'nama' => 'Lutviana Nur Nafi\'ah',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PM 3',
    ),
    6 => 
    array (
      'nama' => 'Farraza Nuradine F',
      'jabatan' => 'Anggota',
      'kelas' => 'XII DKV 2',
    ),
    7 => 
    array (
      'nama' => 'Eli Fidiah Amansa',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    8 => 
    array (
      'nama' => 'Henna Falisha Iftinan',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    9 => 
    array (
      'nama' => 'Tya Adelia Putri',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    10 => 
    array (
      'nama' => 'Endina Aurelia Elfrida P',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PM 2',
    ),
    11 => 
    array (
      'nama' => 'Irma Riskawati',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PM 2',
    ),
    12 => 
    array (
      'nama' => 'Arsela Edikta Juniar',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    13 => 
    array (
      'nama' => 'Nia Novita',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    14 => 
    array (
      'nama' => 'Dewi Rizki Mulyaningsih',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PPLG 1',
    ),
    15 => 
    array (
      'nama' => 'Khairani Saraswati',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    16 => 
    array (
      'nama' => 'Salsabila',
      'jabatan' => 'Anggota',
      'kelas' => 'XI AKL 5',
    ),
    17 => 
    array (
      'nama' => 'Dini Amalia Ismareta',
      'jabatan' => 'Anggota',
      'kelas' => 'XII PM 2',
    ),
    18 => 
    array (
      'nama' => 'Aisyah Sholihatunnisa',
      'jabatan' => 'Anggota',
      'kelas' => 'XII AKL 4',
    ),
    19 => 
    array (
      'nama' => 'Sitatun Fitriyani',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
    20 => 
    array (
      'nama' => 'Syavira Dinda Aurel',
      'jabatan' => 'Anggota',
      'kelas' => 'XII AKL 3',
    ),
    21 => 
    array (
      'nama' => 'Tiara Cahyati',
      'jabatan' => 'Anggota',
      'kelas' => '-',
    ),
  ),
  'Konselor Sebaya' => 
  array (
    0 => 
    array (
      'nama' => 'Benaya Iskha Ghifari',
      'jabatan' => 'Koordinator',
      'kelas' => 'XI MPLB 4',
    ),
    1 => 
    array (
      'nama' => 'Zein Vies Airinn Nabila Y',
      'jabatan' => 'Sekretaris',
      'kelas' => 'XII AKL 1',
    ),
    2 => 
    array (
      'nama' => 'Nazarli Nadiah Putri Y',
      'jabatan' => 'Anggota',
      'kelas' => 'XII MPLB 3',
    ),
    3 => 
    array (
      'nama' => 'Caca Cahyati Rahmadhani',
      'jabatan' => 'Anggota',
      'kelas' => 'XII TF 1',
    ),
    4 => 
    array (
      'nama' => 'Muhammad Paaza Abidin',
      'jabatan' => 'Anggota',
      'kelas' => 'XII DKV 2',
    ),
    5 => 
    array (
      'nama' => 'Zainatul Hamimah',
      'jabatan' => 'Anggota',
      'kelas' => 'XII AKL 5',
    ),
  ),
);
    }
    public function index() {
        return view('divisi.index');
    }
    public function show($slug) {
        $slugs = [
            'kegiatan' => 'Kegiatan',
            'humas' => 'Humas',
            'pdd' => 'PDD',
            'pkwu' => 'PKWU',
            'konselor-sebaya' => 'Konselor Sebaya'
        ];
        if (!isset($slugs[$slug])) abort(404);
        $divisiName = $slugs[$slug];
        $members = $this->getDivisiData()[$divisiName];
        return view('divisi.show', compact('divisiName', 'members'));
    }
}
