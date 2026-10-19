<?php

namespace App\Data;

class DutaGenreData
{
    protected static array $cache = [];

    /**
     * Get baseline Duta GenRe profiles.
     */
    protected static function getBaseline(): array
    {
        return [
            'benaya-iskha-ghifari' => [
                'slug' => 'benaya-iskha-ghifari',
                'name' => 'Benaya Iskha Ghifari',
                'role' => 'Duta GenRe Putri 2025',
                'title' => 'Duta GenRe Putri',
                'badge' => 'Duta GenRe Putri',
                'focus' => 'Kesehatan Mental, Konselor Sebaya & Pemberdayaan Remaja Putri',
                'summary' => 'Duta GenRe Putri SMK Negeri 1 Purwokerto yang aktif mengampanyekan pentingnya kesehatan mental, komunikasi asertif, dan perencanaan masa depan remaja putri.',
                'bio' => 'Benaya Iskha Ghifari terpilih sebagai Duta GenRe Putri SMK Negeri 1 Purwokerto. Beliau juga memegang amanah sebagai Koordinator Divisi Konselor Sebaya di kepengurusan PIK-R SKAKMATT 2025/2026. Dengan komitmen tinggi pada edukasi sebaya, Benaya rutin memfasilitasi diskusi kesehatan mental, membimbing rekan-rekan sebaya dalam menghadapi problematika remaja, serta aktif mewakili sekolah dalam berbagai ajang sosialisasi Generasi Berencana di tingkat daerah.',
                'quote' => 'Menjadi teladan sebaya bukanlah tentang menjadi sempurna, melainkan berani melangkah dan menjadi inspirasi nyata bagi teman-teman di sekitar kita.',
                'image' => 'benaya_genre.jpeg',
                'photo' => '/images/benaya_genre.jpeg',
                'contributions' => [
                    'Inisiator program ruang aman konseling sebaya di lingkungan sekolah',
                    'Pemateri workshop public speaking dan life skills bagi calon anggota baru',
                    'Sosialisasi pencegahan pernikahan dini dan triad KRR ke kelas-kelas',
                    'Fasilitator program pendampingan sebaya ramah remaja di SMKN 1 Purwokerto',
                ],
                'achievements' => [
                    'Duta GenRe Putri SMK Negeri 1 Purwokerto',
                    'Finalis Pemilihan Duta GenRe Kabupaten Banyumas',
                    'Koordinator Divisi Konselor Sebaya PIK-R SKAKMATT Terbaik 2025',
                    'Penghargaan Partisipasi Aktif Kampanye Stop Pernikahan Anak',
                ],
                'experiences' => [
                    'Koordinator Divisi Konselor Sebaya PIK-R SKAKMATT 2025/2026',
                    'Fasilitator Sebaya Program Triad KRR BKKBN Banyumas',
                    'Panitia Pelaksana Grand Final Pemilihan Duta GenRe Sekolah',
                    'Narasumber Sosialisasi KIE Kesehatan Reproduksi Remaja',
                ],
            ],
            'muhammad-paaza-abidin' => [
                'slug' => 'muhammad-paaza-abidin',
                'name' => 'Muhammad Paaza Abidin',
                'role' => 'Duta GenRe Putra 2025',
                'title' => 'Duta GenRe Putra',
                'badge' => 'Duta GenRe Putra',
                'focus' => 'Edukasi Substansi GenRe, Life Skills & Kepemimpinan Remaja Putra',
                'summary' => 'Duta GenRe Putra SMK Negeri 1 Purwokerto yang berdedikasi dalam penguatan substansi Triad KRR, kepemimpinan sebaya, dan literasi perencanaan masa depan.',
                'bio' => 'Muhammad Paaza Abidin membuktikan bahwa pemahaman substansi GenRe dan kecakapan kepemimpinan dapat berjalan beriringan secara seimbang. Sebagai Duta GenRe Putra, beliau mendedikasikan energinya untuk mengedukasi bahaya Triad KRR (NAPZA, seks bebas, dan pernikahan dini), menyusun materi interaktif, serta memotivasi siswa untuk merencanakan pendidikan tinggi dan karier profesional sejak bangku SMK.',
                'quote' => 'Ilmu dan perencanaan yang matang adalah fondasi kokoh untuk menyongsong masa depan yang cerah.',
                'image' => 'paaza_genre.jpeg',
                'photo' => '/images/paaza_genre.jpeg',
                'contributions' => [
                    'Penyusun modul ringkas edukasi GenRe dan Triad KRR',
                    'Mentor sebaya untuk bimbingan belajar dan literasi masa depan',
                    'Penggerak forum diskusi tematik kependudukan dan bonus demografi',
                ],
                'achievements' => [
                    'Duta GenRe Putra SMK Negeri 1 Purwokerto',
                    'Peringkat Terbaik Uji Substansi Materi GenRe Tingkat Sekolah',
                    'Juara Lomba Karya Tulis Edukasi Remaja Berencana',
                ],
                'experiences' => [
                    'Pengurus Divisi Konselor Sebaya PIK-R SKAKMATT',
                    'Delegasi Forum Anak dan Remaja Kabupaten Banyumas',
                    'Moderator Webinar Nasional Pencegahan Perilaku Berisiko Remaja',
                ],
            ],
            'nazarli-nadiah-putri-yuarliana' => [
                'slug' => 'nazarli-nadiah-putri-yuarliana',
                'name' => 'Nazarli Nadiah Putri Yuarliana',
                'role' => 'Duta GenRe Influencer 2025',
                'title' => 'Duta GenRe Influencer',
                'badge' => 'Duta GenRe Influencer',
                'focus' => 'Kampanye Kreatif Digital, Advokasi GenRe & Pemberdayaan Remaja',
                'summary' => 'Duta GenRe Influencer SMK Negeri 1 Purwokerto yang menggerakkan edukasi Generasi Berencana lewat media sosial kreatif, advokasi sebaya, dan aksi nyata positif.',
                'bio' => 'Nazarli Nadiah Putri Yuarliana membawa energi inspiratif melalui perannya sebagai Duta GenRe Influencer SMK Negeri 1 Purwokerto. Dengan mengoptimalkan platform media digital dan konten kreatif ramah remaja, Nazarli aktif mengampanyekan pentingnya gizi seimbang, pencegahan anemia dan stunting, serta pemahaman kesehatan reproduksi demi menyiapkan generasi muda yang berdaya saing.',
                'quote' => 'Manfaatkan ruang digital untuk menyebarkan kebaikan, menginspirasi sesama, dan mewujudkan masa depan berencana.',
                'image' => 'nazarli_genre.jpeg',
                'photo' => '/images/nazarli_genre.jpeg',
                'contributions' => [
                    'Kampanye konten kreatif edukasi GenRe dan aksi positif di media sosial',
                    'Penggerak kampanye pencegahan anemia dan gizi seimbang bagi remaja putri',
                    'Kolaborasi kampanye anti pernikahan dini melalui format multimedia interaktif',
                ],
                'achievements' => [
                    'Duta GenRe Influencer SMK Negeri 1 Purwokerto',
                    'Kreator Konten Edukasi GenRe Terfavorit 2025',
                    'Apresiasi Penggerak Kampanye Positif Remaja Sekolah',
                ],
                'experiences' => [
                    'Pengurus Aktif Divisi Konselor Sebaya PIK-R SKAKMATT 2025/2026',
                    'Inisiator Konten Digital Edukasi Kesehatan Reproduksi & GenRe',
                    'Koordinator Aksi Tablet Tambah Darah Serentak di Sekolah',
                ],
            ],
        ];
    }

    /**
     * Get storage file path.
     */
    protected static function getStoragePath(): string
    {
        return storage_path('app/dutagenre_data.json');
    }

    /**
     * Get all Duta profiles.
     */
    public static function getAll(): array
    {
        if (! empty(static::$cache)) {
            return static::$cache;
        }

        $path = static::getStoragePath();
        if (file_exists($path)) {
            $json = file_get_contents($path);
            $data = json_decode($json, true);
            if (is_array($data) && ! empty($data)) {
                static::$cache = array_merge(static::getBaseline(), $data);

                return static::$cache;
            }
        }

        static::$cache = static::getBaseline();

        return static::$cache;
    }

    /**
     * Find profile by slug.
     */
    public static function findBySlug(string $slug): ?array
    {
        $all = static::getAll();

        return $all[$slug] ?? null;
    }

    /**
     * Save/update profile.
     */
    public static function saveProfile(string $slug, array $data): bool
    {
        $all = static::getAll();
        $existing = $all[$slug] ?? [];
        $merged = array_merge($existing, $data, ['slug' => $slug]);
        $all[$slug] = $merged;
        static::$cache = $all;

        $dir = dirname(static::getStoragePath());
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        file_put_contents(static::getStoragePath(), json_encode($all, JSON_PRETTY_PRINT));

        return true;
    }
}
