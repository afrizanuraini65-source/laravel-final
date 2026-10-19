<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Event;
use App\Models\Member;
use Illuminate\Http\Request;

class SuratDokumenController extends Controller
{
    /**
     * Display surat & dokumen hub with interactive generators.
     */
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'daftar-hadir');

        $kelasList = Member::select('kelas')
            ->whereNotNull('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        $events = Event::latest()->take(10)->get();
        $agendas = Agenda::latest()->take(10)->get();

        // 1. DAFTAR HADIR DATA
        $selectedKelas = $request->get('kelas', '');
        $hadirQuery = Member::query();
        if ($selectedKelas) {
            $hadirQuery->where('kelas', $selectedKelas);
        }
        $hadirMembers = $hadirQuery->orderBy('kelas')->orderBy('name')->get();

        $metaHari = $request->get('meta_hari', 'Rabu');
        $metaTanggal = $request->get('meta_tanggal', '2 September 2026');
        $metaTempat = $request->get('meta_tempat', 'Ruang C202');
        $metaKegiatan = $request->get('meta_kegiatan', 'Forum Rutin PIK-R SKAKMATT');
        $metaTahunAjaran = $request->get('meta_tahun', '2025/2026');

        // 2. SURAT DISPENSASI DATA
        $nomorDispensasi = $request->get('nomor_dispensasi', '045/SKAKMATT/DISP/X/2026');
        $perihalDispensasi = $request->get('perihal_dispensasi', 'Permohonan Dispensasi Mengikuti Kegiatan PIK-R');
        $kegiatanDispensasi = $request->get('kegiatan_dispensasi', 'Workshop & Edukasi Sebaya PIK-R SKAKMATT');
        $tglDispensasi = $request->get('tgl_dispensasi', 'Kamis, 15 Oktober 2026');
        $waktuDispensasi = $request->get('waktu_dispensasi', '08.00 - 14.30 WIB');
        $tempatDispensasi = $request->get('tempat_dispensasi', 'Aula SMK Negeri 1 Purwokerto');

        // Sample students for dispensasi
        $rawSiswa = $request->get('siswa_dispensasi');
        if (is_array($rawSiswa) && count($rawSiswa) > 0) {
            $dispensasiStudents = $rawSiswa;
        } else {
            $dispensasiStudents = [
                ['nama' => 'Afaf Nur Faizah', 'kelas' => 'XII AKL 1', 'keterangan' => 'Ketua Pelaksana'],
                ['nama' => 'Benaya Iskha Ghifari', 'kelas' => 'XI MPLB 4', 'keterangan' => 'Fasilitator Sebaya'],
                ['nama' => 'Muhammad Paaza Abidin', 'kelas' => 'XII DKV 2', 'keterangan' => 'Dokumentasi & Media'],
                ['nama' => 'Salsa Friscila Putri', 'kelas' => 'XII AKL 4', 'keterangan' => 'Koordinator Lapangan'],
            ];
        }

        $namaPembina = $request->get('nama_pembina', "Hayu Almara'tus S., S.Pd");
        $nipPembina = $request->get('nip_pembina', 'NIP. 19850412 201001 2 025');
        $namaGuruBk = $request->get('nama_guru_bk', 'Guru BK SMK Negeri 1 Purwokerto');
        $nipGuruBk = $request->get('nip_guru_bk', 'NIP. 19780915 200801 1 012');
        $namaWaka = $request->get('nama_waka', 'Pak Sugeng (Drs. Sugeng)');
        $jabatanWaka = $request->get('jabatan_waka', 'Waka Kesiswaan SMK Negeri 1 Purwokerto');
        $nipWaka = $request->get('nip_waka', 'NIP. 19690320 199512 1 002');

        // 3. SURAT IZIN ORANG TUA DATA
        $nomorIzinOrtu = $request->get('nomor_izin_ortu', '046/SKAKMATT/IZIN-ORTU/X/2026');
        $namaOrtu = $request->get('nama_ortu', '......................................................');
        $alamatOrtu = $request->get('alamat_ortu', '......................................................');
        $nohpOrtu = $request->get('nohp_ortu', '......................................................');
        $namaSiswaIzin = $request->get('nama_siswa_izin', '......................................................');
        $kelasSiswaIzin = $request->get('kelas_siswa_izin', '........................');
        $nisnSiswaIzin = $request->get('nisn_siswa_izin', '........................');
        $kegiatanIzin = $request->get('kegiatan_izin', 'Kemah Karakter & Pelatihan Duta GenRe 2026');
        $waktuIzin = $request->get('waktu_izin', 'Sabtu - Minggu, 24 - 25 Oktober 2026');
        $tempatIzin = $request->get('tempat_izin', 'Bumper Kendalisada Kalibagor, Banyumas');

        return view('feature.surat-dokumen', compact(
            'activeTab',
            'kelasList',
            'events',
            'agendas',
            'hadirMembers',
            'selectedKelas',
            'metaHari',
            'metaTanggal',
            'metaTempat',
            'metaKegiatan',
            'metaTahunAjaran',
            'nomorDispensasi',
            'perihalDispensasi',
            'kegiatanDispensasi',
            'tglDispensasi',
            'waktuDispensasi',
            'tempatDispensasi',
            'dispensasiStudents',
            'namaPembina',
            'nipPembina',
            'namaGuruBk',
            'nipGuruBk',
            'namaWaka',
            'jabatanWaka',
            'nipWaka',
            'nomorIzinOrtu',
            'namaOrtu',
            'alamatOrtu',
            'nohpOrtu',
            'namaSiswaIzin',
            'kelasSiswaIzin',
            'nisnSiswaIzin',
            'kegiatanIzin',
            'waktuIzin',
            'tempatIzin'
        ));
    }
}
