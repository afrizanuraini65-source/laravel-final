<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicContentAndDutaGenreTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_page_contains_all_core_sections_and_duta_genre(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Ruang Tumbuh');
        $response->assertSee('Generasi Berencana');
        $response->assertSee('Kenali');
        $response->assertSee('Duta GenRe');
        $response->assertSee('Benaya Iskha Ghifari');
        $response->assertSee('Muhammad Paaza Abidin');
        $response->assertSee('Nazarli Nadiah Putri Yuarliana');
        $response->assertSee('Duta GenRe Putri');
        $response->assertSee('Duta GenRe Putra');
        $response->assertSee('Duta GenRe Influencer');
        $response->assertSee('Berani Melangkah, Siap Menjadi Inspirasi');
        $response->assertSee('Pengetahuan Hari Ini');
        $response->assertSee('Bekal untuk Masa Depan');
        $response->assertSee('Bergerak Bersama');
        $response->assertSee('Cerita dan Momen');
        $response->assertSee('Apa yang Kami Lakukan?');
        $response->assertSee('Konseling Sebaya');
        $response->assertSee('Pendaftaran');
        $response->assertSee('Daftar Anggota');
        $response->assertSee('Daftar Akun Siswa');
    }

    public function test_duta_genre_index_and_detail_pages_load_successfully(): void
    {
        $response = $this->get('/duta-genre');
        $response->assertStatus(200);
        $response->assertSee('Benaya Iskha Ghifari');
        $response->assertSee('Muhammad Paaza Abidin');
        $response->assertSee('Nazarli Nadiah Putri Yuarliana');
        $response->assertSee('Duta GenRe Putri');
        $response->assertSee('Duta GenRe Putra');
        $response->assertSee('Duta GenRe Influencer');

        // Test detail Benaya (Duta GenRe Putri)
        $resBenaya = $this->get('/duta-genre/benaya-iskha-ghifari');
        $resBenaya->assertStatus(200);
        $resBenaya->assertSee('Benaya Iskha Ghifari');
        $resBenaya->assertSee('Duta GenRe Putri');
        $resBenaya->assertSee('Biografi & Peran Duta');
        $resBenaya->assertSee('Prestasi & Capaian');
        $resBenaya->assertSee('Pengalaman & Rekam Jejak');

        // Test detail Paaza (Duta GenRe Putra)
        $resPaaza = $this->get('/duta-genre/muhammad-paaza-abidin');
        $resPaaza->assertStatus(200);
        $resPaaza->assertSee('Muhammad Paaza Abidin');
        $resPaaza->assertSee('Duta GenRe Putra');
        $resPaaza->assertSee('Prestasi & Capaian');

        // Test detail Nazarli (Duta GenRe Influencer)
        $resNazarli = $this->get('/duta-genre/nazarli-nadiah-putri-yuarliana');
        $resNazarli->assertStatus(200);
        $resNazarli->assertSee('Nazarli Nadiah Putri Yuarliana');
        $resNazarli->assertSee('Duta GenRe Influencer');
        $resNazarli->assertSee('Prestasi & Capaian');
    }

    public function test_informasi_page_and_all_nine_required_articles_load(): void
    {
        $response = $this->get('/informasi');
        $response->assertStatus(200);

        $slugs = [
            'berani-melangkah-siap-menjadi-inspirasi-pemilihan-duta-genre',
            'pemilihan-duta-genre-smkn-1-purwokerto', // alias test
            'mengenal-pik-r-skakmatt-ruang-tumbuh-remaja',
            'apa-itu-generasi-berencana-genre',
            'mengenal-duta-genre-smk-negeri-1-purwokerto',
            'bagaimana-proses-pemilihan-duta-genre',
            'pentingnya-merencanakan-pendidikan-karier-dan-masa-depan',
            'mengenal-triad-remaja-dan-pengambilan-keputusan',
            'konseling-sebaya-ruang-aman-untuk-berbagi-dan-mendengarkan',
            'remaja-berani-berkarya-berani-menjadi-inspirasi',
            'peran-pik-r-dalam-edukasi-dan-pengembangan-diri-remaja',
        ];

        foreach ($slugs as $slug) {
            $artRes = $this->get('/informasi/'.$slug);
            $artRes->assertStatus(200);
        }
    }

    public function test_kegiatan_page_loads_with_authentic_activities(): void
    {
        $response = $this->get('/kegiatan');
        $response->assertStatus(200);
        $response->assertSee('Grand Final Pemilihan Duta GenRe');
        $response->assertSee('SKAKMATT Goes to SMPN 9 Purwokerto');
    }

    public function test_ruang_sebaya_page_loads(): void
    {
        $response = $this->get('/ruang-sebaya');
        $response->assertStatus(200);
        $response->assertSee('Ruang Sebaya');
        $response->assertSee('@ruangsebaya_');
    }

    public function test_admin_content_management_access_control(): void
    {
        // Guest cannot access admin CMS
        $guestRes = $this->get('/admin/konten/artikel');
        $guestRes->assertRedirect('/login');

        // Normal USER cannot access admin CMS (403)
        $user = User::where('role', 'USER')->first();
        if ($user) {
            $userRes = $this->actingAs($user)->get('/admin/konten/artikel');
            $userRes->assertStatus(403);
        }

        // Admin can access admin CMS
        $admin = User::where('role', 'ADMIN')->first();
        if ($admin) {
            $adminRes = $this->actingAs($admin)->get('/admin/konten/artikel');
            $adminRes->assertStatus(200);

            $adminDutaRes = $this->actingAs($admin)->get('/admin/konten/duta-genre');
            $adminDutaRes->assertStatus(200);

            $adminKegRes = $this->actingAs($admin)->get('/admin/konten/kegiatan');
            $adminKegRes->assertStatus(200);
        }
    }

    public function test_kepengurusan_and_stats_synchronization(): void
    {
        $resWelcome = $this->get('/');
        $resWelcome->assertStatus(200);
        $resWelcome->assertSee('Divisi Aktif');
        $resWelcome->assertSee('Anggota');
        $resWelcome->assertSee('Program Kerja');
        $resWelcome->assertSee('Konselor');

        $resKepengurusan = $this->get('/kepengurusan');
        $resKepengurusan->assertStatus(200);
        $resKepengurusan->assertSee('Anggota (6 orang)');
        $resKepengurusan->assertSee('Anggota (27 orang)');
        $resKepengurusan->assertSee('Anggota (22 orang)');
        $resKepengurusan->assertSee('Anggota (23 orang)');
        $resKepengurusan->assertSee('Benaya Iskha Ghifari (Koor) &amp; Zein Vies Airinn (Sekre)', false);
        $resKepengurusan->assertSee('Nazarli Nadiah, Caca Cahyati, Muhammad Paaza, Zainatul Hamimah');

        $admin = User::where('role', 'ADMIN')->first();
        if ($admin) {
            $resDivisi = $this->actingAs($admin)->get('/feature/Divisi/konselor-sebaya');
            $resDivisi->assertStatus(200);
            $resDivisi->assertSee('6 Orang');
        }
    }

    public function test_surat_dokumen_resmi_generator_and_signatures(): void
    {
        // 1. Guest redirect to login
        $guestRes = $this->get('/feature/surat-dokumen');
        $guestRes->assertRedirect('/login');

        $admin = User::where('role', 'ADMIN')->first();
        $this->assertNotNull($admin);

        // 2. Tab Daftar Hadir (matching Gambar 1 & 2)
        $resHadir = $this->actingAs($admin)->get('/feature/surat-dokumen?tab=daftar-hadir');
        $resHadir->assertStatus(200);
        $resHadir->assertSee('EKSTRAKURIKULER');
        $resHadir->assertSee('PIK-R SKAKMATT');
        $resHadir->assertSee('SMK NEGERI 1 PURWOKERTO');
        $resHadir->assertSee('DAFTAR HADIR KELAS ANGGOTA');
        $resHadir->assertSee('Pelatih PIK-R SKAKMATT,');
        $resHadir->assertSee('Nabila Rahma Verdiani');

        // 3. Tab Surat Dispensasi (with Pembina, Guru BK, and Pak Sugeng)
        $resDisp = $this->actingAs($admin)->get('/feature/surat-dokumen?tab=dispensasi');
        $resDisp->assertStatus(200);
        $resDisp->assertSee('Permohonan Dispensasi Mengikuti Kegiatan');
        $resDisp->assertSee('Guru Bimbingan Konseling');
        $resDisp->assertSee('Pembina PIK-R SKAKMATT');
        $resDisp->assertSee("Hayu Almara'tus S., S.Pd");
        $resDisp->assertSee('Pak Sugeng');
        $resDisp->assertSee('Waka Kesiswaan');

        // 4. Tab Surat Izin Orang Tua
        $resIzin = $this->actingAs($admin)->get('/feature/surat-dokumen?tab=izin-ortu');
        $resIzin->assertStatus(200);
        $resIzin->assertSee('SURAT IZIN ORANG TUA / WALI');
        $resIzin->assertSee('Afaf Nur Faizah');
        $resIzin->assertSee("Hayu Almara'tus S., S.Pd");
    }
}
