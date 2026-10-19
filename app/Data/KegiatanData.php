<?php

namespace App\Data;

class KegiatanData
{
    /**
     * Get baseline verified activities.
     */
    protected static function getBaseline(): array
    {
        return [
            1 => [
                'id' => 1,
                'title' => 'Grand Final Pemilihan Duta GenRe SMK Negeri 1 Purwokerto',
                'category' => 'Pemilihan Duta GenRe',
                'category_label' => 'Pemilihan Duta',
                'date_display' => '12 Juni 2025',
                'month' => 'JUN',
                'day' => '12',
                'year' => '2025',
                'location' => 'Aula SMK Negeri 1 Purwokerto',
                'image' => 'pendutgen.png',
                'status' => 'TERLAKSANA',
                'status_label' => 'Terlaksana',
                'source' => 'Liputan RRI Purwokerto & iNews Purwokerto',
                'desc' => 'Puncak penobatan Duta GenRe SMK Negeri 1 Purwokerto sebagai role model sebaya, disaksikan oleh dewan juri, perwakilan BKKBN/Dinperkim, dan keluarga besar sekolah.',
            ],
            2 => [
                'id' => 2,
                'title' => 'Pembekalan Calon Duta GenRe dari Mba Dista',
                'category' => 'Pemilihan Duta GenRe',
                'category_label' => 'Pembekalan & Materi',
                'date_display' => '11 Juni 2026',
                'month' => 'JUN',
                'day' => '11',
                'year' => '2026',
                'location' => 'Aula',
                'image' => 'pembekalan pildugen dari mba dista.jpeg',
                'status' => 'TERLAKSANA',
                'status_label' => 'Terlaksana',
                'source' => 'Dokumentasi Internal PIK-R SKAKMATT',
                'desc' => 'Sesi transfer ilmu komprehensif mengenai teknik penyuluhan, pemahaman materi substansi GenRe, serta penguatan mentalitas sebagai role model sekolah.',
            ],
            3 => [
                'id' => 3,
                'title' => 'Pembekalan Pemilihan Duta GenRe dengan Kak Lilia',
                'category' => 'Pemilihan Duta GenRe',
                'category_label' => 'Pembekalan',
                'date_display' => '7 Mei 2026',
                'month' => 'MEI',
                'day' => '07',
                'year' => '2026',
                'location' => 'Workshop',
                'image' => 'pembekalan pildugen dengan kak lilia.jpeg',
                'status' => 'TERLAKSANA',
                'status_label' => 'Terlaksana',
                'source' => 'Dokumentasi Internal PIK-R SKAKMATT',
                'desc' => 'Pelatihan teknik public speaking, etika berbusana, dan kesiapan mental panggung bagi seluruh finalis calon duta.',
            ],
            4 => [
                'id' => 4,
                'title' => 'Seleksi Pemilihan Duta GenRe & Uji Tertulis',
                'category' => 'Pemilihan Duta GenRe',
                'category_label' => 'Seleksi & Tes',
                'date_display' => '13 Mei 2026',
                'month' => 'MEI',
                'day' => '13',
                'year' => '2026',
                'location' => 'Aula',
                'image' => 'seleksi pildugen.jpeg',
                'status' => 'TERLAKSANA',
                'status_label' => 'Terlaksana',
                'source' => 'Dokumentasi Internal PIK-R SKAKMATT',
                'desc' => 'Tahap pengujian pengetahuan dasar substansi GenRe, Triad KRR, dan wawasan kependudukan untuk menyaring kandidat terbaik.',
            ],
            5 => [
                'id' => 5,
                'title' => 'Uji Penyuluhan Sebaya Calon Duta GenRe',
                'category' => 'Pemilihan Duta GenRe',
                'category_label' => 'Uji Penyuluhan',
                'date_display' => '18 Mei 2026',
                'month' => 'MEI',
                'day' => '18',
                'year' => '2026',
                'location' => 'Aula',
                'image' => 'seleksi penyuluhan.jpeg',
                'status' => 'TERLAKSANA',
                'status_label' => 'Terlaksana',
                'source' => 'Dokumentasi Internal PIK-R SKAKMATT',
                'desc' => 'Simulasi praktik penyuluhan konseling di depan dewan juri untuk mengukur ketepatan penyampaian pesan edukasi kepada remaja.',
            ],
            6 => [
                'id' => 6,
                'title' => 'Technical Meeting Pemilihan Duta GenRe',
                'category' => 'Pemilihan Duta GenRe',
                'category_label' => 'Technical Meeting',
                'date_display' => '20 April 2026',
                'month' => 'APR',
                'day' => '20',
                'year' => '2026',
                'location' => 'Aula',
                'image' => 'tm pildugen.jpeg',
                'status' => 'TERLAKSANA',
                'status_label' => 'Terlaksana',
                'source' => 'Dokumentasi Internal PIK-R SKAKMATT',
                'desc' => 'Penjelasan juklak dan juknis kompetisi, kriteria penilaian, serta pembagian jadwal karantina bagi para pendaftar duta.',
            ],
            7 => [
                'id' => 7,
                'title' => 'SKAKMATT Goes to SMPN 9 Purwokerto',
                'category' => 'Edukasi dan Sosialisasi Remaja',
                'category_label' => 'Sosialisasi Eksternal',
                'date_display' => '31 Juli 2026',
                'month' => 'JUL',
                'day' => '31',
                'year' => '2026',
                'location' => 'SMPN 9 Purwokerto',
                'image' => 'skakmatt goes to SMPN 9 purwokerto.jpeg',
                'status' => 'TERLAKSANA',
                'status_label' => 'Terlaksana',
                'source' => 'Dokumentasi Sosialisasi Eksternal',
                'desc' => 'Aksi nyata pengurus dan konselor sebaya mengedukasi siswa sekolah mitra tentang pubertas, bahaya pergaulan bebas, dan kiat bijak bermedia sosial.',
            ],
            8 => [
                'id' => 8,
                'title' => 'Workshop Skakmattcreatif',
                'category' => 'Pengembangan Diri dan Kepemimpinan',
                'category_label' => 'Kreativitas & Media',
                'date_display' => '14 Januari 2026',
                'month' => 'JAN',
                'day' => '14',
                'year' => '2026',
                'location' => 'Aula',
                'image' => 'skakmattcreatif.jpeg',
                'status' => 'TERLAKSANA',
                'status_label' => 'Terlaksana',
                'source' => 'Dokumentasi Divisi PDD & PKWU',
                'desc' => 'Ruang eksplorasi pembuatan infografis edukasi, poster kreatif, dan konten kampanye digital agar pesan GenRe mudah dipahami generasi Z.',
            ],
            9 => [
                'id' => 9,
                'title' => 'Anjangsini Anjangsana Smecone x Skaga',
                'category' => 'Kegiatan Internal dan Kebersamaan Anggota',
                'category_label' => 'Studi Banding',
                'date_display' => '12 Februari 2026',
                'month' => 'FEB',
                'day' => '12',
                'year' => '2026',
                'location' => 'Workshop',
                'image' => 'anjangsini anjangsana smecone x skaga.jpeg',
                'status' => 'TERLAKSANA',
                'status_label' => 'Terlaksana',
                'source' => 'Kolaborasi PIK-R Antarsekolah',
                'desc' => 'Pertemuan kolaboratif pertukaran program kerja dan best-practice tata kelola organisasi PIK-R antarsekolah kejuruan di Kabupaten Banyumas.',
            ],
            10 => [
                'id' => 10,
                'title' => 'Buka Bersama PIK-R SKAKMATT',
                'category' => 'Kegiatan Internal dan Kebersamaan Anggota',
                'category_label' => 'Kebersamaan',
                'date_display' => '5 Maret 2026',
                'month' => 'MAR',
                'day' => '05',
                'year' => '2026',
                'location' => 'Samara Cafe',
                'image' => 'bukber.jpeg',
                'status' => 'TERLAKSANA',
                'status_label' => 'Terlaksana',
                'source' => 'Dokumentasi Kekeluargaan Anggota',
                'desc' => 'Temu keakraban seluruh kader dan alumni untuk mempererat tali silaturahmi, mengevaluasi capaian pertengahan semester, dan memperkuat kekompakan.',
            ],
            11 => [
                'id' => 11,
                'title' => 'SKAKMATT Discussion Time (SDT)',
                'category' => 'Diskusi dan Konseling Sebaya',
                'category_label' => 'Discussion Time',
                'date_display' => '8 Agustus 2026',
                'month' => 'AGU',
                'day' => '08',
                'year' => '2026',
                'location' => 'Buken',
                'image' => 'sdt buken.jpeg',
                'status' => 'TERLAKSANA',
                'status_label' => 'Terlaksana',
                'source' => 'Program Rutin Konselor Sebaya',
                'desc' => 'Forum diskusi santai antarsiswa mengupas isu-isu kesehatan mental, manajemen stres belajar, dan relasi sehat di lingkungan sekolah.',
            ],
            12 => [
                'id' => 12,
                'title' => 'Forum Perdana SKAKMATT',
                'category' => 'Diskusi dan Konseling Sebaya',
                'category_label' => 'Forum Diskusi',
                'date_display' => '29 Juli 2026',
                'month' => 'JUL',
                'day' => '29',
                'year' => '2026',
                'location' => 'Masjid',
                'image' => 'forum perdana.jpeg',
                'status' => 'TERLAKSANA',
                'status_label' => 'Terlaksana',
                'source' => 'Dokumentasi Internal',
                'desc' => 'Pertemuan orientasi awal bagi anggota baru untuk saling mengenal divisi, program kerja tahunan, serta nilai-nilai dasar PIK-R SKAKMATT.',
            ],
            13 => [
                'id' => 13,
                'title' => 'Dokumentasi Serah Terima Jabatan PIK-R 25/26',
                'category' => 'Kegiatan Internal dan Kebersamaan Anggota',
                'category_label' => 'Serah Terima Jabatan',
                'date_display' => 'Awal Kepengurusan 2025/2026',
                'month' => 'AWAL',
                'day' => '25/26',
                'year' => '2025',
                'location' => 'Workshop',
                'image' => 'dokumentasi serah terima jabatan pikr 2526.jpeg',
                'status' => 'TERLAKSANA',
                'status_label' => 'Terlaksana',
                'source' => 'Dokumentasi Resmi Kepengurusan',
                'desc' => 'Momen estafet kepemimpinan pengurus demisioner kepada pengurus terpilih periode 2025/2026 untuk melanjutkan roda organisasi.',
            ],
            14 => [
                'id' => 14,
                'title' => 'Reorganisasi PIK-R SKAKMATT 2026',
                'category' => 'Kegiatan Internal dan Kebersamaan Anggota',
                'category_label' => 'Reorganisasi',
                'date_display' => 'Reorganisasi 2026',
                'month' => 'REOR',
                'day' => '2026',
                'year' => '2026',
                'location' => 'Lapangan SMKN 1 Purwokerto',
                'image' => 'reor pikr 2026.jpeg',
                'status' => 'TERLAKSANA',
                'status_label' => 'Terlaksana',
                'source' => 'Dokumentasi Tahunan Organisasi',
                'desc' => 'Musyawarah besar regenerasi kepengurusan dan pemilihan formatur pengurus baru untuk masa bakti selanjutnya.',
            ],
            15 => [
                'id' => 15,
                'title' => 'Ruang Sebaya: Konseling Sebaya & Edukasi Digital',
                'category' => 'Diskusi dan Konseling Sebaya',
                'category_label' => 'Layanan Rutin',
                'date_display' => 'Setiap Hari Aktif',
                'month' => 'RUTIN',
                'day' => '24/7',
                'year' => '2026',
                'location' => 'Online & Ruang BK / Sekretariat PIK-R',
                'image' => 'ruangsebaya__qr.png',
                'status' => 'AKTIF',
                'status_label' => 'Layanan Rutin',
                'source' => 'Akun Resmi Instagram @ruangsebaya_',
                'desc' => 'Layanan teman curhat dan konseling sebaya yang aman, nyaman, dan menjaga kerahasiaan siswa secara penuh.',
            ],
        ];
    }

    /**
     * Get all activities merged with persistent storage additions/edits.
     */
    public static function getAll(): array
    {
        $data = static::getBaseline();

        $storagePath = storage_path('app/content/kegiatan.json');
        if (file_exists($storagePath)) {
            try {
                $custom = json_decode(file_get_contents($storagePath), true);
                if (is_array($custom)) {
                    foreach ($custom as $id => $item) {
                        $data[$id] = $item;
                    }
                }
            } catch (\Throwable $e) {
                // Silently fallback to baseline
            }
        }

        return $data;
    }

    public static function find($id): ?array
    {
        $all = static::getAll();

        return $all[$id] ?? null;
    }

    /**
     * Save/update activity.
     */
    public static function save(array $item): int
    {
        $storageDir = storage_path('app/content');
        if (! is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        $storagePath = $storageDir.'/kegiatan.json';
        $current = [];
        if (file_exists($storagePath)) {
            $current = json_decode(file_get_contents($storagePath), true) ?: [];
        }

        if (empty($item['id'])) {
            $all = static::getAll();
            $maxId = count($all) > 0 ? max(array_keys($all)) : 0;
            $item['id'] = $maxId + 1;
        }

        $current[$item['id']] = $item;
        file_put_contents($storagePath, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $item['id'];
    }

    /**
     * Delete an activity.
     */
    public static function delete($id): bool
    {
        $storageDir = storage_path('app/content');
        $storagePath = $storageDir.'/kegiatan.json';

        if (file_exists($storagePath)) {
            $current = json_decode(file_get_contents($storagePath), true) ?: [];
            if (isset($current[$id])) {
                unset($current[$id]);
                file_put_contents($storagePath, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

                return true;
            }
        }

        return false;
    }
}
