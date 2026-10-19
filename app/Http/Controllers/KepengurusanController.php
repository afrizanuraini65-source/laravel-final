<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class KepengurusanController extends Controller
{
    public function index(Request $request)
    {
        $kepengurusan = [
            // Pembina & Pelatih
            ['nama' => 'Hayu Almara\'tus S. S.Pd', 'jabatan' => 'Pembina', 'divisi' => 'Pembina'],
            ['nama' => 'Nabila Rahma Verdiani', 'jabatan' => 'Pelatih', 'divisi' => 'Pembina'],
            
            // Pengurus Inti
            ['nama' => 'Afaf Nur Faizah', 'jabatan' => 'Ketua', 'divisi' => 'Inti'],
            ['nama' => 'Florisa Sabria Zahra', 'jabatan' => 'Wakil Ketua', 'divisi' => 'Inti'],
            ['nama' => 'Shayla Divania Putri', 'jabatan' => 'Sekretaris 1', 'divisi' => 'Inti'],
            ['nama' => 'Zalfa Zahirah', 'jabatan' => 'Sekretaris 2', 'divisi' => 'Inti'],
            ['nama' => 'Alyaa Hanifah Putri', 'jabatan' => 'Bendahara 1', 'divisi' => 'Inti'],
            ['nama' => 'Fadilla Ristianingsih', 'jabatan' => 'Bendahara 2', 'divisi' => 'Inti'],

            // Divisi Kegiatan
            ['nama' => 'Salsa Friscila Putri', 'jabatan' => 'Koordinator', 'divisi' => 'Kegiatan'],
            ['nama' => 'Anggun Syaikhan Zulfa N.', 'jabatan' => 'Sekretaris', 'divisi' => 'Kegiatan'],
            ['nama' => 'Desi Mulyani', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Prima Kinarya Abdillah', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Nadissa Syifa Ramadhani', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Hasna\' Tsaniatul Nabila', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Haura Salsabila', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Yusuf Hengki Fernando I', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Elin Suci Septiani', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Alifah Devina', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Jenis Tri Andini', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Shamita Aisha Sabrina', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Nasywa Tsaqib Oxiyana H', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Naydhira Zafkha Juanesya', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Queensha Vivin Arshela', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Zazqia Aura Ramadhani', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Livina', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Faiza Hidayati', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Annaku Cykal Ramadhani', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Fatwa Oktafiyani Putri', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Fatimah Az Zahra', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Dini Dwi Aulia', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Naysila Mulan Devita', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Luthfiana Hasna', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Nafiza Rimahayu', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Almira Nur Hana', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],
            ['nama' => 'Clarrisa Surya Putri A.', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan'],

            // Divisi Humas
            ['nama' => 'Sekarwangi Putri', 'jabatan' => 'Koordinator', 'divisi' => 'Humas'],
            ['nama' => 'Fadilla Atut Khosiah', 'jabatan' => 'Sekretaris', 'divisi' => 'Humas'],
            ['nama' => 'Risma Amelia', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Dwiana Shanti', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Rizka Rahmadhani', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Fiela Nur Rizkya', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Tri Asih Widyawati', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Putri Anom Tiara Sani', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Indah Meilani Putri', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Nathania Elysia Rahma', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Alivia Apta Zena', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Hilda Nur Asiah', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Nadin Juliana Putri', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Hanindya Yumna Humaira', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Melia Berlianti', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Shafa Nuril \'Azmi', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Rifa Amaliana', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Adelia Nur Ramadhan', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Umnia Sari', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Kayana Felicia Zuleika', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Shaema Syiara Tauhid', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],
            ['nama' => 'Anndin Eno Dwi Yuliani', 'jabatan' => 'Anggota', 'divisi' => 'Humas'],

            // Divisi PDD
            ['nama' => 'Hanumara Arya Pramadani', 'jabatan' => 'Koordinator', 'divisi' => 'PDD'],
            ['nama' => '`Aqiilah Mufiida Zahra', 'jabatan' => 'Sekretaris', 'divisi' => 'PDD'],
            ['nama' => 'Afka Salsabila', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Haaniyah Dwi Rahmawati', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Sintia Pratiwi', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Fatimah Hidayanti', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Riezelda Keumalasari', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Bana Arvacelena', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Ri Zahrani Adila Sanjaya', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Ria Nur Syafa', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Viony Rahmadhani', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Nur Devi Karomina', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Rofiif Agustina', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Safa Staqib Adzkiya', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Afriza Nur \'Aini', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Amira Syifa Rizki', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Bela Putri Maghfiroh', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Aulia Hanun Salaa Auriye', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Najwa Azizah', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Reva Nagali Aningtyas', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Riska Ramadhani Putri', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Amalia Candrawati', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],
            ['nama' => 'Yolanda Marvelia', 'jabatan' => 'Anggota', 'divisi' => 'PDD'],

            // Divisi PKWU
            ['nama' => 'Syarifah Tazqiah', 'jabatan' => 'Koordinator', 'divisi' => 'PKWU'],
            ['nama' => 'Rovikoh', 'jabatan' => 'Sekretaris', 'divisi' => 'PKWU'],
            ['nama' => 'Mual`Afah Fiadhinata', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Arghya Rachma Aqeela', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Hindun Merdekawati A.', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Lutviana Nur Nafi\'ah', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Farraza Nuradine', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Eli Fidiah Amansa', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Henna Falisha Iftinan', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Tya Adelia Putri', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Endina Aurelia Elfrida', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Irma Riskawati', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Arsela Edikta Juniar', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Nia Novita', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Dewi Rizki Mulyaningsih', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Khairani Saraswati', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Salsabila Maharani Az-Zahra', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Dini Amelia Ismareta', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Aisyah Solihatunnisa', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Sitatun Fitriyani', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Syafira Dinda Aurel', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],
            ['nama' => 'Tiara Cahyati', 'jabatan' => 'Anggota', 'divisi' => 'PKWU'],

            // Divisi Konselor Sebaya
            ['nama' => 'Benaya Iskha Ghifari', 'jabatan' => 'Koordinator', 'divisi' => 'Konselor Sebaya'],
            ['nama' => 'Zein Vies Airinn Nabila Y.', 'jabatan' => 'Sekretaris', 'divisi' => 'Konselor Sebaya'],
            ['nama' => 'Nazarli Nadiah Putri Yuarina', 'jabatan' => 'Anggota', 'divisi' => 'Konselor Sebaya'],
            ['nama' => 'Caca Cahyati Rahmadani', 'jabatan' => 'Anggota', 'divisi' => 'Konselor Sebaya'],
            ['nama' => 'Muhammad Paaza Abidin', 'jabatan' => 'Anggota', 'divisi' => 'Konselor Sebaya'],
            ['nama' => 'Zainatul Hamimah F.', 'jabatan' => 'Anggota', 'divisi' => 'Konselor Sebaya'],
        ];

        $collection = collect($kepengurusan);

        // Filter functionality
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $collection = $collection->filter(function ($item) use ($search) {
                return str_contains(strtolower($item['nama']), $search);
            });
        }

        if ($request->filled('divisi')) {
            $collection = $collection->where('divisi', $request->divisi);
        }

        if ($request->filled('jabatan')) {
            $jabatan = strtolower($request->jabatan);
            if ($jabatan == 'inti') {
                $collection = $collection->whereIn('jabatan', ['Ketua', 'Wakil Ketua', 'Sekretaris 1', 'Sekretaris 2', 'Bendahara 1', 'Bendahara 2']);
            } elseif ($jabatan == 'koordinator') {
                $collection = $collection->where('jabatan', 'Koordinator');
            } elseif ($jabatan == 'anggota') {
                $collection = $collection->where('jabatan', 'Anggota');
            }
        }

        // Stats
        $totalPengurus = count($kepengurusan);
        $totalInti = collect($kepengurusan)->whereIn('jabatan', ['Ketua', 'Wakil Ketua', 'Sekretaris 1', 'Sekretaris 2', 'Bendahara 1', 'Bendahara 2'])->count();
        $totalDivisi = collect($kepengurusan)->whereNotIn('divisi', ['Pembina', 'Inti'])->count();

        // Get unique division list for filter dropdown
        $divisiList = collect($kepengurusan)->pluck('divisi')->unique()->sort();

        // Manual Pagination
        $perPage = 20;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        
        $pengurus = new LengthAwarePaginator(
            $currentItems, 
            $collection->count(), 
            $perPage, 
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('kepengurusan.admin', compact(
            'pengurus', 
            'totalPengurus', 
            'totalInti', 
            'totalDivisi',
            'divisiList'
        ));
    }
}
