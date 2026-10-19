<?php

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\Division;
use App\Models\Event;
use App\Models\Member;
use App\Models\OrganizationPeriod;
use App\Models\P2ksSchedule;
use App\Models\Position;
use App\Models\RegistrationSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles & Permissions (Spatie)
        $roles = [
            'ADMIN', 'PEMBINA', 'PELATIH', 'KETUA', 'WAKIL_KETUA',
            'SEKRETARIS', 'BENDAHARA', 'KEGIATAN', 'HUMAS', 'PDD',
            'PKWU', 'KONSELOR_SEBAYA', 'USER',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // 2. Organization Period
        $period = OrganizationPeriod::firstOrCreate(
            ['name' => '2025/2026'],
            [
                'start_date' => '2025-07-01',
                'end_date' => '2026-06-30',
                'is_active' => true,
            ]
        );

        // 3. Divisions
        $divKegiatan = Division::firstOrCreate(['name' => 'Kegiatan'], ['description' => 'Mengurus agenda kegiatan']);
        $divHumas = Division::firstOrCreate(['name' => 'Hubungan Masyarakat'], ['description' => 'Humas dan komunikasi eksternal']);
        $divPdd = Division::firstOrCreate(['name' => 'Publikasi, Dekorasi & Dokumentasi (PDD)'], ['description' => 'Dokumentasi dan desain grafis']);
        $divPkwu = Division::firstOrCreate(['name' => 'Prakarya & Wirausaha (PKWU)'], ['description' => 'Wirausaha dan pendanaan']);
        $divKonselor = Division::firstOrCreate(['name' => 'Konselor Sebaya'], ['description' => 'Edukasi dan konseling teman sebaya']);

        // 4. Positions
        $posKetua = Position::firstOrCreate(['title' => 'Ketua']);
        $posWaka = Position::firstOrCreate(['title' => 'Wakil Ketua']);
        $posSekre = Position::firstOrCreate(['title' => 'Sekretaris']);
        $posBenda = Position::firstOrCreate(['title' => 'Bendahara']);
        $posAnggota = Position::firstOrCreate(['title' => 'Anggota']);

        $posKoordinator = Position::firstOrCreate(['title' => 'Koordinator']);

        // 5. Authentic Pengurus Accounts (<nama_depan>_<jabatan/divisi>@gmail.com, Password: password)
        // Administrator SKAKMATT
        $admin = User::firstOrCreate(
            ['email' => 'admin_skakmatt@gmail.com'],
            [
                'name' => 'Admin SKAKMATT',
                'password' => Hash::make('password'),
                'role' => 'ADMIN',
                'kelas' => null,
                'status_verifikasi' => 'APPROVED',
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles(['ADMIN']);

        $adminAlias = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin SKAKMATT',
                'password' => Hash::make('password'),
                'role' => 'ADMIN',
                'kelas' => null,
                'status_verifikasi' => 'APPROVED',
                'email_verified_at' => now(),
            ]
        );
        $adminAlias->syncRoles(['ADMIN']);

        $pengurusList = [
            // Pembina & Pelatih
            ['name' => "Hayu Almara'tus S. S.Pd", 'jabatan' => 'Pembina', 'divisi' => 'Pembina', 'role' => 'PEMBINA', 'email' => 'hayu_pembina@gmail.com', 'kelas' => null],
            ['name' => 'Nabila Rahma Verdiani', 'jabatan' => 'Pelatih', 'divisi' => 'Pembina', 'role' => 'PELATIH', 'email' => 'nabila_pelatih@gmail.com', 'kelas' => null],

            // Pengurus Inti
            ['name' => 'Afaf Nur Faizah', 'jabatan' => 'Ketua', 'divisi' => 'Inti', 'role' => 'KETUA', 'email' => 'afaf_ketua@gmail.com', 'kelas' => 'XII MPLB 3'],
            ['name' => 'Florisa Sabria Zahra', 'jabatan' => 'Wakil Ketua', 'divisi' => 'Inti', 'role' => 'WAKIL_KETUA', 'email' => 'florisa_wakil@gmail.com', 'kelas' => 'XII PM 3'],
            ['name' => 'Shayla Divania Putri', 'jabatan' => 'Sekretaris 1', 'divisi' => 'Inti', 'role' => 'SEKRETARIS', 'email' => 'shayla_sekretaris@gmail.com', 'kelas' => 'XII DKV 2'],
            ['name' => 'Zalfa Zahirah', 'jabatan' => 'Sekretaris 2', 'divisi' => 'Inti', 'role' => 'SEKRETARIS', 'email' => 'zalfa_sekretaris@gmail.com', 'kelas' => 'XII PPLG 1'],
            ['name' => 'Alyaa Hanifah Putri', 'jabatan' => 'Bendahara 1', 'divisi' => 'Inti', 'role' => 'BENDAHARA', 'email' => 'alyaa_bendahara@gmail.com', 'kelas' => 'XII TF 2'],
            ['name' => 'Fadilla Ristianingsih', 'jabatan' => 'Bendahara 2', 'divisi' => 'Inti', 'role' => 'BENDAHARA', 'email' => 'fadilla_bendahara@gmail.com', 'kelas' => 'XII PM 3'],

            // Divisi Kegiatan
            ['name' => 'Salsa Friscila Putri', 'jabatan' => 'Koordinator', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'salsa_kegiatan@gmail.com', 'kelas' => 'XII AKL 4'],
            ['name' => 'Anggun Syaikhan Zulfa N.', 'jabatan' => 'Sekretaris', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'anggun_kegiatan@gmail.com', 'kelas' => 'XII AKL 2'],
            ['name' => 'Desi Mulyani', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'desi_kegiatan@gmail.com', 'kelas' => 'XII AKL 4'],
            ['name' => 'Prima Kinarya Abdillah', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'prima_kegiatan@gmail.com', 'kelas' => null],
            ['name' => 'Nadissa Syifa Ramadhani', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'nadissa_kegiatan@gmail.com', 'kelas' => 'XII AKL 5'],
            ['name' => "Hasna' Tsaniatul Nabila", 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'hasna_kegiatan@gmail.com', 'kelas' => 'XII DKV 1'],
            ['name' => 'Haura Salsabila', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'haura_kegiatan@gmail.com', 'kelas' => 'XII DKV 1'],
            ['name' => 'Yusuf Hengki Fernando I', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'yusuf_kegiatan@gmail.com', 'kelas' => 'XII DKV 2'],
            ['name' => 'Elin Suci Septiani', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'elin_kegiatan@gmail.com', 'kelas' => null],
            ['name' => 'Alifah Devina', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'alifah_kegiatan@gmail.com', 'kelas' => 'XII PM 3'],
            ['name' => 'Jenis Tri Andini', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'jenis_kegiatan@gmail.com', 'kelas' => null],
            ['name' => 'Shamita Aisha Sabrina', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'shamita_kegiatan@gmail.com', 'kelas' => 'XII PM 3'],
            ['name' => 'Nasywa Tsaqib Oxyana H.', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'nasywa_kegiatan@gmail.com', 'kelas' => 'XII PPLG 2'],
            ['name' => 'Naydhira Zafkha Juanesya', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'naydhira_kegiatan@gmail.com', 'kelas' => 'XII TF 1'],
            ['name' => 'Queensha Vivin Arshela', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'queensha_kegiatan@gmail.com', 'kelas' => 'XII TF 2'],
            ['name' => 'Zazqia Aura Ramadhani', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'zazqia_kegiatan@gmail.com', 'kelas' => 'XII TJKT 1'],
            ['name' => 'Livina', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'livina_kegiatan@gmail.com', 'kelas' => 'XII AKL 1'],
            ['name' => 'Faiza Hidayati', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'faiza_kegiatan@gmail.com', 'kelas' => 'XII MPLB 1'],
            ['name' => 'Annaku Cykal Ramadhani', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'annaku_kegiatan@gmail.com', 'kelas' => null],
            ['name' => 'Fatwa Oktafiyani Putri', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'fatwa_kegiatan@gmail.com', 'kelas' => 'XII PM 2'],
            ['name' => 'Fatimah Az Zahra', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'fatimah_kegiatan@gmail.com', 'kelas' => null],
            ['name' => 'Dini Dwi Aulia', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'dini_kegiatan@gmail.com', 'kelas' => null],
            ['name' => 'Naysila Mulan Devita', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'naysila_kegiatan@gmail.com', 'kelas' => null],
            ['name' => 'Luthfiana Hasna', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'luthfiana_kegiatan@gmail.com', 'kelas' => 'XII PM 3'],
            ['name' => 'Nafiza Rimahayu', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'nafiza_kegiatan@gmail.com', 'kelas' => 'XII PM 2'],
            ['name' => 'Almira Nur Hana', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'almira_kegiatan@gmail.com', 'kelas' => null],
            ['name' => 'Clarrisa Surya Putri A.', 'jabatan' => 'Anggota', 'divisi' => 'Kegiatan', 'role' => 'KEGIATAN', 'email' => 'clarrisa_kegiatan@gmail.com', 'kelas' => null],

            // Divisi Humas
            ['name' => 'Sekarwangi Putri', 'jabatan' => 'Koordinator', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'sekarwangi_humas@gmail.com', 'kelas' => 'XII PPLG 2'],
            ['name' => 'Fadilla Atut Khosiah', 'jabatan' => 'Sekretaris', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'fadilla_humas@gmail.com', 'kelas' => 'XII AKL 4'],
            ['name' => 'Risma Amelia', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'risma_humas@gmail.com', 'kelas' => 'XII AKL 1'],
            ['name' => 'Dwiana Shanti', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'dwiana_humas@gmail.com', 'kelas' => 'XII AKL 2'],
            ['name' => 'Rizka Rahmadhani', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'rizka_humas@gmail.com', 'kelas' => 'XII AKL 3'],
            ['name' => 'Fiela Nur Rizkya', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'fiela_humas@gmail.com', 'kelas' => null],
            ['name' => 'Tri Asih Widyawati', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'tri_humas@gmail.com', 'kelas' => 'XII AKL 5'],
            ['name' => 'Putri Anom Tiara Sani', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'putri_humas@gmail.com', 'kelas' => null],
            ['name' => 'Indah Meilani Putri', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'indah_humas@gmail.com', 'kelas' => 'XII DKV 1'],
            ['name' => 'Nathania Elysia Rahma', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'nathania_humas@gmail.com', 'kelas' => 'XII MPLB 2'],
            ['name' => 'Alivia Apta Zena', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'alivia_humas@gmail.com', 'kelas' => null],
            ['name' => 'Hilda Nur Asiah', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'hilda_humas@gmail.com', 'kelas' => 'XII PM 2'],
            ['name' => 'Nadin Juliana Putri', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'nadin_humas@gmail.com', 'kelas' => null],
            ['name' => 'Hanindya Yumna Humaira', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'hanindya_humas@gmail.com', 'kelas' => null],
            ['name' => 'Melia Berlianti', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'melia_humas@gmail.com', 'kelas' => 'XII AKL 5'],
            ['name' => "Shafa Nuril 'Azmi", 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'shafa_humas@gmail.com', 'kelas' => 'XII PM 3'],
            ['name' => 'Rifa Amaliana', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'rifa_humas@gmail.com', 'kelas' => 'XII MPLB 2'],
            ['name' => 'Adelia Nur Ramadhan', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'adelia_humas@gmail.com', 'kelas' => 'XII TF 1'],
            ['name' => 'Umnia Sari', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'umnia_humas@gmail.com', 'kelas' => 'XII TF 2'],
            ['name' => 'Kayana Felicia Zuleika', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'kayana_humas@gmail.com', 'kelas' => 'XII AKL 1'],
            ['name' => 'Shaema Syiara Tauhid', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'shaema_humas@gmail.com', 'kelas' => null],
            ['name' => 'Anndin Eno Dwi Yuliani', 'jabatan' => 'Anggota', 'divisi' => 'Humas', 'role' => 'HUMAS', 'email' => 'anndin_humas@gmail.com', 'kelas' => 'XII AKL 3'],

            // Divisi PDD
            ['name' => 'Hanumara Arya Pramadani', 'jabatan' => 'Koordinator', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'hanumara_pdd@gmail.com', 'kelas' => 'XII DKV 2'],
            ['name' => '`Aqiilah Mufiida Zahra', 'jabatan' => 'Sekretaris', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'aqiilah_pdd@gmail.com', 'kelas' => 'XII TJKT 1'],
            ['name' => 'Afka Salsabila', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'afka_pdd@gmail.com', 'kelas' => 'XII DKV 1'],
            ['name' => 'Haaniyah Dwi Rahmawati', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'haaniyah_pdd@gmail.com', 'kelas' => 'XII DKV 1'],
            ['name' => 'Sintia Pratiwi', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'sintia_pdd@gmail.com', 'kelas' => null],
            ['name' => 'Fatimah Hidayanti', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'fatimah_pdd@gmail.com', 'kelas' => 'XII DKV 2'],
            ['name' => 'Riezelda Keumalasari', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'riezelda_pdd@gmail.com', 'kelas' => 'XII DKV 2'],
            ['name' => 'Bana Arvacelena', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'bana_pdd@gmail.com', 'kelas' => 'XII PM 2'],
            ['name' => 'Ri Zahrani Adila Sanjaya', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'ri_pdd@gmail.com', 'kelas' => 'XII MPLB 1'],
            ['name' => 'Ria Nur Syafa', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'ria_pdd@gmail.com', 'kelas' => 'XII PM 2'],
            ['name' => 'Viony Rahmadhani', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'viony_pdd@gmail.com', 'kelas' => 'XII DKV 1'],
            ['name' => 'Nur Devi Karomina', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'nur_pdd@gmail.com', 'kelas' => null],
            ['name' => 'Rofiif Agustina', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'rofiif_pdd@gmail.com', 'kelas' => 'XII PPLG 1'],
            ['name' => 'Safa Staqib Adzkiya', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'safa_pdd@gmail.com', 'kelas' => 'XII PPLG 1'],
            ['name' => "Afriza Nur 'Aini", 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'afriza_pdd@gmail.com', 'kelas' => 'XII PPLG 2'],
            ['name' => 'Amira Syifa Rizki', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'amira_pdd@gmail.com', 'kelas' => 'XII TJKT 1'],
            ['name' => 'Bela Putri Maghfiroh', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'bela_pdd@gmail.com', 'kelas' => null],
            ['name' => 'Aulia Hanun Salaa Auriye', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'aulia_pdd@gmail.com', 'kelas' => 'XII AKL 5'],
            ['name' => 'Najwa Azizah', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'najwa_pdd@gmail.com', 'kelas' => null],
            ['name' => 'Reva Nagali Aningtyas', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'reva_pdd@gmail.com', 'kelas' => 'XII PPLG 2'],
            ['name' => 'Riska Ramadhani Putri', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'riska_pdd@gmail.com', 'kelas' => 'XII DKV 2'],
            ['name' => 'Amalia Candrawati', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'amalia_pdd@gmail.com', 'kelas' => null],
            ['name' => 'Yolanda Marvelia', 'jabatan' => 'Anggota', 'divisi' => 'PDD', 'role' => 'PDD', 'email' => 'yolanda_pdd@gmail.com', 'kelas' => 'XII AKL 5'],

            // Divisi PKWU
            ['name' => 'Syarifah Tazqiah', 'jabatan' => 'Koordinator', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'syarifah_pkwu@gmail.com', 'kelas' => 'XI MPLB 4'],
            ['name' => 'Rovikoh', 'jabatan' => 'Sekretaris', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'rovikoh_pkwu@gmail.com', 'kelas' => 'XII TF 1'],
            ['name' => 'Mual`Afah Fiadhinata', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'mualafah_pkwu@gmail.com', 'kelas' => null],
            ['name' => 'Arghya Rachma Aqeela', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'arghya_pkwu@gmail.com', 'kelas' => 'XII PM 3'],
            ['name' => 'Hindun Merdekawati A.', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'hindun_pkwu@gmail.com', 'kelas' => 'XII AKL 5'],
            ['name' => "Lutviana Nur Nafi'ah", 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'lutviana_pkwu@gmail.com', 'kelas' => 'XII PM 3'],
            ['name' => 'Farraza Nuradine', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'farraza_pkwu@gmail.com', 'kelas' => 'XII DKV 2'],
            ['name' => 'Eli Fidiah Amansa', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'eli_pkwu@gmail.com', 'kelas' => null],
            ['name' => 'Henna Falisha Iftinan', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'henna_pkwu@gmail.com', 'kelas' => null],
            ['name' => 'Tya Adelia Putri', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'tya_pkwu@gmail.com', 'kelas' => null],
            ['name' => 'Endina Aurelia Elfrida', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'endina_pkwu@gmail.com', 'kelas' => 'XII PM 2'],
            ['name' => 'Irma Riskawati', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'irma_pkwu@gmail.com', 'kelas' => 'XII PM 2'],
            ['name' => 'Arsela Edikta Juniar', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'arsela_pkwu@gmail.com', 'kelas' => null],
            ['name' => 'Nia Novita', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'nia_pkwu@gmail.com', 'kelas' => null],
            ['name' => 'Dewi Rizki Mulyaningsih', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'dewi_pkwu@gmail.com', 'kelas' => null],
            ['name' => 'Khairani Saraswati', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'khairani_pkwu@gmail.com', 'kelas' => null],
            ['name' => 'Salsabila Maharani Az-Zahra', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'salsabila_pkwu@gmail.com', 'kelas' => 'XII AKL 4'],
            ['name' => 'Dini Amelia Ismareta', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'dini_pkwu@gmail.com', 'kelas' => 'XII PM 2'],
            ['name' => 'Aisyah Solihatunnisa', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'aisyah_pkwu@gmail.com', 'kelas' => 'XII AKL 4'],
            ['name' => 'Sitatun Fitriyani', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'sitatun_pkwu@gmail.com', 'kelas' => null],
            ['name' => 'Syafira Dinda Aurel', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'syafira_pkwu@gmail.com', 'kelas' => 'XII AKL 3'],
            ['name' => 'Tiara Cahyati', 'jabatan' => 'Anggota', 'divisi' => 'PKWU', 'role' => 'PKWU', 'email' => 'tiara_pkwu@gmail.com', 'kelas' => null],

            // Divisi Konselor Sebaya
            ['name' => 'Benaya Iskha Ghifari', 'jabatan' => 'Koordinator', 'divisi' => 'Konselor Sebaya', 'role' => 'KONSELOR_SEBAYA', 'email' => 'benaya_konselor@gmail.com', 'kelas' => 'XI MPLB 4'],
            ['name' => 'Zein Vies Airinn Nabila Y.', 'jabatan' => 'Sekretaris', 'divisi' => 'Konselor Sebaya', 'role' => 'KONSELOR_SEBAYA', 'email' => 'zein_konselor@gmail.com', 'kelas' => 'XII AKL 1'],
            ['name' => 'Nazarli Nadiah Putri Yuarina', 'jabatan' => 'Anggota', 'divisi' => 'Konselor Sebaya', 'role' => 'KONSELOR_SEBAYA', 'email' => 'nazarli_konselor@gmail.com', 'kelas' => 'XII MPLB 3'],
            ['name' => 'Caca Cahyati Rahmadani', 'jabatan' => 'Anggota', 'divisi' => 'Konselor Sebaya', 'role' => 'KONSELOR_SEBAYA', 'email' => 'caca_konselor@gmail.com', 'kelas' => 'XII TF 1'],
            ['name' => 'Muhammad Paaza Abidin', 'jabatan' => 'Anggota', 'divisi' => 'Konselor Sebaya', 'role' => 'KONSELOR_SEBAYA', 'email' => 'muhammad_konselor@gmail.com', 'kelas' => 'XII DKV 2'],
            ['name' => 'Zainatul Hamimah F.', 'jabatan' => 'Anggota', 'divisi' => 'Konselor Sebaya', 'role' => 'KONSELOR_SEBAYA', 'email' => 'zainatul_konselor@gmail.com', 'kelas' => 'XII AKL 5'],
        ];

        foreach ($pengurusList as $p) {
            $user = User::firstOrCreate(
                ['email' => $p['email']],
                [
                    'name' => $p['name'],
                    'password' => Hash::make('password'),
                    'role' => $p['role'],
                    'kelas' => $p['kelas'],
                    'status_verifikasi' => 'APPROVED',
                    'email_verified_at' => now(),
                ]
            );

            $user->syncRoles([$p['role']]);

            // Determine division_id
            $divId = null;
            if ($p['divisi'] === 'Kegiatan') {
                $divId = $divKegiatan->id;
            } elseif ($p['divisi'] === 'Humas') {
                $divId = $divHumas->id;
            } elseif ($p['divisi'] === 'PDD') {
                $divId = $divPdd->id;
            } elseif ($p['divisi'] === 'PKWU') {
                $divId = $divPkwu->id;
            } elseif ($p['divisi'] === 'Konselor Sebaya') {
                $divId = $divKonselor->id;
            }

            // Determine position_id
            $posId = null;
            if ($p['jabatan'] === 'Ketua') {
                $posId = $posKetua->id;
            } elseif ($p['jabatan'] === 'Wakil Ketua') {
                $posId = $posWaka->id;
            } elseif (str_contains($p['jabatan'], 'Sekretaris')) {
                $posId = $posSekre->id;
            } elseif (str_contains($p['jabatan'], 'Bendahara')) {
                $posId = $posBenda->id;
            } elseif ($p['jabatan'] === 'Koordinator') {
                $posId = $posKoordinator->id;
            } elseif ($p['jabatan'] === 'Anggota') {
                $posId = $posAnggota->id;
            }

            if ($p['role'] !== 'ADMIN' && $p['role'] !== 'PEMBINA' && $p['role'] !== 'PELATIH') {
                Member::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $p['name'],
                        'kelas' => $p['kelas'],
                        'organization_period_id' => $period->id,
                        'division_id' => $divId,
                        'position_id' => $posId,
                        'status' => 'AKTIF',
                    ]
                );
            }
        }

        // 6. Registration Settings Default
        RegistrationSetting::firstOrCreate([], [
            'is_account_registration_open' => true,
            'is_member_registration_open' => true,
            'announcement_title' => 'Penerimaan Anggota Baru PIK-R SKAKMATT 2026/2027',
            'announcement_description' => 'Selamat datang di portal pendaftaran resmi ekstrakurikuler PIK-R SKAKMATT SMK Negeri 1 Purwokerto. Mari bertumbuh bersama menjadi Generasi Berencana.',
            'requirements' => "1. Siswa aktif SMK Negeri 1 Purwokerto (Kelas X & XI)\n2. Memiliki komitmen belajar & berbagi bersama rekan sebaya\n3. Bersedia mematuhi norma dan kode etik organisasi",
        ]);

        // 7. Event
        Event::firstOrCreate(
            ['slug' => 'sdt-1'],
            [
                'organization_period_id' => $period->id,
                'title' => 'SKAKMATT Discussion Time (SDT) #1',
                'description' => 'Diskusi santai seputar kesehatan mental dan pencegahan Triad KRR.',
                'date' => Carbon::now()->addDays(7)->toDateString(),
                'time' => '15:30:00',
                'location' => 'Aula SMK Negeri 1 Purwokerto',
                'quota' => 50,
                'status' => 'OPEN',
            ]
        );

        // 8. Agendas (Kalender)
        $agendaSamples = [
            ['title' => 'Forum Rutin Mingguan', 'date' => Carbon::now()->addDays(2)->toDateString(), 'category' => 'Forum Rutin', 'location' => 'Aula'],
            ['title' => 'Workshop Konselor Sebaya', 'date' => Carbon::now()->addDays(5)->toDateString(), 'category' => 'Kegiatan', 'location' => 'Workshop'],
            ['title' => 'Sosialisasi Triad KRR ke Kelas', 'date' => Carbon::now()->addDays(9)->toDateString(), 'category' => 'Event', 'location' => 'Ruang Kelas'],
        ];
        foreach ($agendaSamples as $ag) {
            Agenda::firstOrCreate(
                ['title' => $ag['title'], 'date' => $ag['date']],
                [
                    'start_time' => '15:30:00',
                    'end_time' => '17:00:00',
                    'location' => $ag['location'],
                    'category' => $ag['category'],
                    'status' => 'Terjadwal',
                ]
            );
        }

        // 11. Run Specialized Seeders
        $this->call(ArticleSeeder::class);
        $this->call(MasterMemberSeeder::class);
        $this->call(MembersTableSeeder::class);
        $this->call(TaskSeeder::class);
        if (app()->environment(['local', 'testing'])) {
            $this->call(FinanceSeeder::class);
        }

        // 12. P2KS Schedule (Piket Ruang - khusus anggota biasa non-pengurus)
        $pengurusRoles = [
            'ADMIN', 'PEMBINA', 'PELATIH', 'KETUA', 'WAKIL_KETUA',
            'SEKRETARIS', 'BENDAHARA', 'KEGIATAN', 'HUMAS', 'PDD',
            'PKWU', 'KONSELOR_SEBAYA',
        ];
        $usersForPiket = User::role('USER')
            ->whereDoesntHave('roles', fn ($q) => $q->whereIn('name', $pengurusRoles))
            ->where(fn ($q) => $q->whereNull('role')->orWhere('role', 'USER'))
            ->take(5)
            ->get();

        if ($usersForPiket->isEmpty()) {
            $usersForPiket = User::where('role', 'USER')->take(4)->get();
        }

        foreach ($usersForPiket as $idx => $u) {
            P2ksSchedule::firstOrCreate(
                ['user_id' => $u->id, 'scheduled_date' => Carbon::today()->addDays($idx)->toDateString()],
                ['status' => $idx === 0 ? 'menunggu' : 'hadir']
            );
        }
    }
}
