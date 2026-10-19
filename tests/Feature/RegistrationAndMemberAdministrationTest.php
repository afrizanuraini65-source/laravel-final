<?php

namespace Tests\Feature;

use App\Models\MasterMember;
use App\Models\Member;
use App\Models\MemberApplication;
use App\Models\OrganizationPeriod;
use App\Models\Position;
use App\Models\RegistrationSetting;
use App\Models\User;
use App\Services\ExcelService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RegistrationAndMemberAdministrationTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected User $ketuaUser;

    protected User $sekreUser;

    protected User $regularUser;

    protected OrganizationPeriod $period;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure roles exist
        Role::firstOrCreate(['name' => 'ADMIN']);
        Role::firstOrCreate(['name' => 'KETUA']);
        Role::firstOrCreate(['name' => 'SEKRETARIS']);
        Role::firstOrCreate(['name' => 'USER']);

        $this->period = OrganizationPeriod::firstOrCreate(
            ['name' => '2025/2026'],
            ['start_date' => '2025-07-01', 'end_date' => '2026-06-30', 'is_active' => true]
        );

        $posKetua = Position::firstOrCreate(['title' => 'Ketua']);
        $posSekre = Position::firstOrCreate(['title' => 'Sekretaris']);

        $this->adminUser = User::firstOrCreate(
            ['email' => 'admin_test@pikr.test'],
            ['name' => 'Admin Tester', 'password' => Hash::make('password'), 'role' => 'ADMIN']
        );
        $this->adminUser->syncRoles(['ADMIN']);

        $this->ketuaUser = User::firstOrCreate(
            ['email' => 'ketua_test@pikr.test'],
            ['name' => 'Ketua Tester', 'password' => Hash::make('password'), 'role' => 'KETUA']
        );
        $this->ketuaUser->syncRoles(['KETUA']);
        Member::firstOrCreate(
            ['user_id' => $this->ketuaUser->id],
            ['organization_period_id' => $this->period->id, 'position_id' => $posKetua->id, 'status' => 'AKTIF']
        );

        $this->sekreUser = User::firstOrCreate(
            ['email' => 'sekre_test@pikr.test'],
            ['name' => 'Sekretaris Tester', 'password' => Hash::make('password'), 'role' => 'SEKRETARIS']
        );
        $this->sekreUser->syncRoles(['SEKRETARIS']);
        Member::firstOrCreate(
            ['user_id' => $this->sekreUser->id],
            ['organization_period_id' => $this->period->id, 'position_id' => $posSekre->id, 'status' => 'AKTIF']
        );

        $this->regularUser = User::firstOrCreate(
            ['email' => 'user_test@pikr.test'],
            ['name' => 'User Siswa Tester', 'password' => Hash::make('password'), 'role' => 'USER']
        );
        $this->regularUser->syncRoles(['USER']);
    }

    /**
     * Skenario 1: Pendaftaran akun dibuka.
     */
    public function test_01_pendaftaran_akun_dibuka_dapat_diakses_dan_mendaftar(): void
    {
        $settings = RegistrationSetting::current();
        $settings->update([
            'is_account_registration_open' => true,
            'account_registration_start' => null,
            'account_registration_end' => null,
        ]);

        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Pendaftaran Akun Siswa');

        // Test sending OTP
        $otpResponse = $this->postJson(route('register.otp'), [
            'email' => 'siswa_baru@smkn1pwt.sch.id',
            'name' => 'Siswa Baru',
        ]);
        $otpResponse->assertStatus(200);
        $otpResponse->assertJson(['success' => true]);

        // Submit registration with session OTP
        session(['reg_otp' => '123456', 'reg_otp_email' => 'siswa_baru@smkn1pwt.sch.id']);

        $registerSubmit = $this->post('/register', [
            'name' => 'Siswa Baru',
            'email' => 'siswa_baru@smkn1pwt.sch.id',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'kelas' => 'X PPLG 1',
            'birth_place' => 'Banyumas',
            'birth_date' => '2008-05-10',
            'phone' => '081234567890',
            'verification_code' => '123456',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'siswa_baru@smkn1pwt.sch.id']);
    }

    /**
     * Skenario 2: Pendaftaran akun ditutup.
     */
    public function test_02_pendaftaran_akun_ditutup_menampilkan_pemberitahuan_dan_menolak_registrasi(): void
    {
        $settings = RegistrationSetting::current();
        $settings->update([
            'is_account_registration_open' => false,
            'account_registration_start' => null,
            'account_registration_end' => null,
        ]);

        // GET /register shows registration closed notice
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Pendaftaran Akun Ditutup');

        // POST /register/otp blocked with 403
        $otpResponse = $this->postJson(route('register.otp'), [
            'email' => 'blocked_user@smkn1pwt.sch.id',
        ]);
        $otpResponse->assertStatus(403);

        // POST /register redirected with error
        $registerSubmit = $this->post('/register', [
            'name' => 'Blocked User',
            'email' => 'blocked_user@smkn1pwt.sch.id',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'kelas' => 'X PPLG 1',
            'birth_place' => 'Banyumas',
            'birth_date' => '2008-05-10',
            'phone' => '081234567890',
            'verification_code' => '123456',
        ]);
        $registerSubmit->assertRedirect(route('register'));
        $registerSubmit->assertSessionHasErrors('email');
    }

    /**
     * Skenario 3: Penerimaan anggota dibuka saat akun sudah tersedia.
     */
    public function test_03_penerimaan_anggota_dibuka_dapat_diakses_oleh_pengguna_berakun(): void
    {
        $settings = RegistrationSetting::current();
        $settings->update([
            'is_member_registration_open' => true,
            'member_registration_start' => null,
            'member_registration_end' => null,
        ]);

        $this->actingAs($this->regularUser);

        $response = $this->get(route('pendaftaran-anggota.create'));
        $response->assertStatus(200);
        $response->assertSee('Kirim Formulir Permohonan Keanggotaan');
        $response->assertSee('Penerimaan Anggota Baru Dibuka');
    }

    /**
     * Skenario 4: Pendaftaran akun dibuka tetapi penerimaan anggota ditutup.
     */
    public function test_04_akun_dibuka_tapi_penerimaan_anggota_ditutup(): void
    {
        $settings = RegistrationSetting::current();
        $settings->update([
            'is_account_registration_open' => true,
            'is_member_registration_open' => false,
            'member_registration_start' => null,
            'member_registration_end' => null,
        ]);

        // Register account remains open
        $resAccount = $this->get('/register');
        $resAccount->assertStatus(200);
        $resAccount->assertSee('Pendaftaran Akun Siswa');

        // Member application page shows closed status
        $this->actingAs($this->regularUser);
        $resMember = $this->get(route('pendaftaran-anggota.create'));
        $resMember->assertStatus(200);
        $resMember->assertSee('Penerimaan Anggota Baru Ditutup');

        // Submitting member application is blocked
        $postMember = $this->post(route('pendaftaran-anggota.store'), [
            'name' => 'Siswa Ingin Daftar',
            'kelas' => 'X PPLG 1',
            'reason' => 'Saya ingin belajar kesehatan reproduksi remaja di PIK-R.',
            'data_consent' => '1',
        ]);
        $postMember->assertRedirect(route('pendaftaran-anggota.create'));
        $postMember->assertSessionHas('error');
    }

    /**
     * Skenario 5: Siswa sudah mempunyai akun dan mengajukan keanggotaan.
     */
    public function test_05_siswa_berakun_dapat_mengajukan_keanggotaan(): void
    {
        $settings = RegistrationSetting::current();
        $settings->update(['is_member_registration_open' => true]);

        $applicant = User::create([
            'name' => 'Calon Anggota Test',
            'email' => 'calon_anggota_05@smkn1pwt.sch.id',
            'password' => Hash::make('password'),
            'role' => 'USER',
        ]);
        $applicant->assignRole('USER');

        $this->actingAs($applicant);

        $response = $this->post(route('pendaftaran-anggota.store'), [
            'name' => 'Calon Anggota Test',
            'kelas' => 'X PPLG 2',
            'nisn' => '0089912345',
            'reason' => 'Saya berkomitmen untuk aktif dalam kegiatan GenRe dan konseling sebaya.',
            'data_consent' => '1',
        ]);

        $response->assertRedirect(route('pendaftaran-anggota.create'));
        $this->assertDatabaseHas('member_applications', [
            'user_id' => $applicant->id,
            'name' => 'Calon Anggota Test',
            'kelas' => 'X PPLG 2',
            'nisn' => '0089912345',
        ]);
    }

    /**
     * Skenario 6: Siswa belum mempunyai akun mengakses formulir pendaftaran anggota.
     */
    public function test_06_siswa_belum_mempunyai_akun_diarahkan_login_atau_register(): void
    {
        $settings = RegistrationSetting::current();
        $settings->update([
            'is_account_registration_open' => true,
            'is_member_registration_open' => true,
        ]);

        // Guest accesses application page without prior account
        $response = $this->get(route('pendaftaran-anggota.create'));
        $response->assertStatus(200);
        $response->assertSee('Formulir Pendaftaran Anggota Baru');

        // Guest submits application directly
        $postResponse = $this->post(route('pendaftaran-anggota.store'), [
            'name' => 'Guest Calon Anggota',
            'kelas' => 'X PPLG 1',
            'jurusan' => 'Pengembangan Perangkat Lunak dan Gim (PPLG)',
            'phone' => '081234567899',
            'email' => 'calon_anggota@gmail.com',
            'reason' => 'Saya ingin mendaftar ekstrakurikuler PIK-R SKAKMATT Purwokerto.',
            'data_consent' => '1',
        ]);
        $postResponse->assertRedirect(route('pendaftaran-anggota.create'));
        $this->assertDatabaseHas('member_applications', [
            'name' => 'Guest Calon Anggota',
            'kelas' => 'X PPLG 1',
            'email' => 'calon_anggota@gmail.com',
        ]);
    }

    /**
     * Skenario 7: Identitas cocok dengan master siswa (Buku Induk).
     */
    public function test_07_identitas_cocok_dengan_master_siswa_tervalidasi_otomatis(): void
    {
        $settings = RegistrationSetting::current();
        $settings->update(['is_member_registration_open' => true]);

        // Create MasterMember record
        $master = MasterMember::create([
            'name' => 'Rina Siswi Cerdas',
            'nisn' => '0077889911',
            'kelas' => 'X AKL 1',
            'is_registered' => false,
        ]);

        $applicant = User::create([
            'name' => 'Rina Siswi Cerdas',
            'email' => 'rina_07@smkn1pwt.sch.id',
            'password' => Hash::make('password'),
            'role' => 'USER',
        ]);
        $applicant->assignRole('USER');

        $this->actingAs($applicant);

        $response = $this->post(route('pendaftaran-anggota.store'), [
            'name' => 'Rina Siswi Cerdas',
            'kelas' => 'X AKL 1',
            'nisn' => '0077889911',
            'reason' => 'Saya ingin menjadi pendidik sebaya PIK-R SKAKMATT Purwokerto.',
            'data_consent' => '1',
        ]);

        $this->assertDatabaseHas('member_applications', [
            'user_id' => $applicant->id,
            'validation_status' => 'VALID_AUTOMATIS',
            'status' => 'MENUNGGU_PERSETUJUAN',
        ]);
    }

    /**
     * Skenario 8: Identitas tidak cocok dan membutuhkan pemeriksaan manual.
     */
    public function test_08_identitas_tidak_cocok_membutuhkan_pemeriksaan_manual(): void
    {
        $settings = RegistrationSetting::current();
        $settings->update(['is_member_registration_open' => true]);

        $applicant = User::create([
            'name' => 'Siswa Baru Belum Masuk Induk',
            'email' => 'siswa_belum_induk_08@smkn1pwt.sch.id',
            'password' => Hash::make('password'),
            'role' => 'USER',
        ]);
        $applicant->assignRole('USER');

        $this->actingAs($applicant);

        $response = $this->post(route('pendaftaran-anggota.store'), [
            'name' => 'Siswa Baru Belum Masuk Induk',
            'kelas' => 'X DKV 1',
            'nisn' => '9999999999', // Unknown NISN
            'reason' => 'Saya baru pindah sekolah dan sangat ingin bergabung dengan PIK-R SKAKMATT.',
            'data_consent' => '1',
        ]);

        $this->assertDatabaseHas('member_applications', [
            'user_id' => $applicant->id,
            'validation_status' => 'TIDAK_DITEMUKAN',
            'status' => 'PERLU_PEMERIKSAAN',
        ]);
    }

    /**
     * Skenario 9: Pengajuan disetujui -> Catatan Member dibuat dan aktif.
     */
    public function test_09_pengajuan_disetujui_menjadi_anggota_resmi(): void
    {
        $applicant = User::create([
            'name' => 'Dina Approved',
            'email' => 'dina_09@smkn1pwt.sch.id',
            'password' => Hash::make('password'),
            'role' => 'USER',
        ]);
        $applicant->assignRole('USER');

        $application = MemberApplication::create([
            'user_id' => $applicant->id,
            'name' => 'Dina Approved',
            'kelas' => 'X PPLG 1',
            'nisn' => '0099887766',
            'reason' => 'Saya ingin mengasah kemampuan konselor sebaya.',
            'data_consent' => true,
            'validation_status' => 'VALID_AUTOMATIS',
            'status' => 'MENUNGGU_PERSETUJUAN',
        ]);

        // Sekretaris approves application
        $this->actingAs($this->sekreUser);

        $response = $this->post(route('verifikasi.application.approve', $application));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('member_applications', [
            'id' => $application->id,
            'status' => 'DISETUJUI',
            'reviewed_by_user_id' => $this->sekreUser->id,
        ]);

        $this->assertDatabaseHas('members', [
            'user_id' => $applicant->id,
            'name' => 'Dina Approved',
            'status' => 'AKTIF',
        ]);
    }

    /**
     * Skenario 10: Pengajuan ditolak dengan alasan penolakan.
     */
    public function test_10_pengajuan_ditolak_dengan_alasan_akun_tidak_dihapus(): void
    {
        $applicant = User::create([
            'name' => 'Bagus Rejected',
            'email' => 'bagus_10@smkn1pwt.sch.id',
            'password' => Hash::make('password'),
            'role' => 'USER',
        ]);
        $applicant->assignRole('USER');

        $application = MemberApplication::create([
            'user_id' => $applicant->id,
            'name' => 'Bagus Rejected',
            'kelas' => 'X PPLG 1',
            'nisn' => '0011223344',
            'reason' => 'Iseng mendaftar.',
            'data_consent' => true,
            'validation_status' => 'PERLU_PEMERIKSAAN',
            'status' => 'PERLU_PEMERIKSAAN',
        ]);

        $this->actingAs($this->ketuaUser);

        $response = $this->post(route('verifikasi.application.reject', $application), [
            'rejection_reason' => 'Data tidak lengkap dan motivasi belum memenuhi kriteria.',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('member_applications', [
            'id' => $application->id,
            'status' => 'DITOLAK',
            'rejection_reason' => 'Data tidak lengkap dan motivasi belum memenuhi kriteria.',
        ]);

        // User account must NOT be deleted
        $this->assertDatabaseHas('users', ['id' => $applicant->id]);
        // Member record must NOT exist
        $this->assertDatabaseMissing('members', ['user_id' => $applicant->id]);
    }

    /**
     * Skenario 11: Anggota yang sudah ada mencoba mendaftar ulang dicegah.
     */
    public function test_11_anggota_yang_sudah_ada_mencoba_daftar_ulang_dicegah(): void
    {
        $settings = RegistrationSetting::current();
        $settings->update(['is_member_registration_open' => true]);

        $existingUser = User::create([
            'name' => 'Anggota Sudah Aktif',
            'email' => 'aktif_11@smkn1pwt.sch.id',
            'password' => Hash::make('password'),
            'role' => 'USER',
        ]);
        $existingUser->assignRole('USER');

        Member::create([
            'user_id' => $existingUser->id,
            'organization_period_id' => $this->period->id,
            'name' => 'Anggota Sudah Aktif',
            'kelas' => 'XI PPLG 1',
            'status' => 'AKTIF',
        ]);

        $this->actingAs($existingUser);

        $response = $this->post(route('pendaftaran-anggota.store'), [
            'name' => 'Anggota Sudah Aktif',
            'kelas' => 'XI PPLG 1',
            'reason' => 'Mendaftar ulang keanggotaan.',
            'data_consent' => '1',
        ]);

        $response->assertRedirect(route('pendaftaran-anggota.create'));
        $response->assertSessionHas('error');
    }

    /**
     * Skenario 12: Sekretaris mengedit data anggota.
     */
    public function test_12_sekretaris_dapat_mengedit_data_anggota(): void
    {
        $targetMember = Member::create([
            'name' => 'Anggota Untuk Diedit',
            'kelas' => 'X AKL 2',
            'nisn' => '0022334455',
            'phone' => '081122334455',
            'status' => 'AKTIF',
            'organization_period_id' => $this->period->id,
        ]);

        $this->actingAs($this->sekreUser);

        $response = $this->put(route('anggota.update', $targetMember), [
            'name' => 'Anggota Diedit Sekretaris',
            'kelas' => 'XI AKL 2',
            'nisn' => '0022334455',
            'phone' => '089988776655',
            'status' => 'AKTIF',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('members', [
            'id' => $targetMember->id,
            'name' => 'Anggota Diedit Sekretaris',
            'kelas' => 'XI AKL 2',
            'phone' => '089988776655',
        ]);
    }

    /**
     * Skenario 13: Ketua mengunduh PDF anggota.
     */
    public function test_13_ketua_dapat_mengakses_dan_mengunduh_pdf_anggota(): void
    {
        $this->actingAs($this->ketuaUser);

        $response = $this->get(route('anggota.pdf', [
            'doc_title' => 'DAFTAR ANGGOTA RESMI',
            'show_kop' => '1',
            'show_signature_area' => '1',
        ]));

        $response->assertStatus(200);
        $response->assertSee('DAFTAR ANGGOTA RESMI');
        $response->assertSee('EKSTRAKURIKULER PIK-R SKAKMATT');
        $response->assertSee('SMK NEGERI 1 PURWOKERTO');
    }

    /**
     * Skenario 14: Impor dan ekspor Excel data anggota.
     */
    public function test_14_impor_dan_ekspor_excel_anggota(): void
    {
        $this->actingAs($this->sekreUser);

        // 1. Export Excel returns binary xlsx
        $exportResponse = $this->get(route('anggota.export'));
        $exportResponse->assertStatus(200);
        $this->assertStringContainsString('spreadsheetml', $exportResponse->headers->get('content-type'));

        // 2. Download template
        $templateResponse = $this->get(route('anggota.template'));
        $templateResponse->assertStatus(200);
        $this->assertStringContainsString('spreadsheetml', $templateResponse->headers->get('content-type'));

        // 3. Create mock xlsx via ExcelService and import
        $headers = ['Nama Lengkap', 'Kelas', 'NISN', 'No. WhatsApp', 'Status Keanggotaan'];
        $rows = [
            ['Siswa Import Excel', 'X PPLG 3', '0033445566', '081299887766', 'AKTIF'],
        ];
        $binary = ExcelService::buildXlsxContent($headers, $rows, 'Template Anggota');

        $tmpFile = tempnam(sys_get_temp_dir(), 'xlsx_test_');
        file_put_contents($tmpFile, $binary);

        $uploadedFile = new UploadedFile($tmpFile, 'import_test.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        $importResponse = $this->post(route('anggota.import'), [
            'excel_file' => $uploadedFile,
            'duplicate_mode' => 'skip',
        ]);

        $importResponse->assertSessionHas('success');
        $this->assertDatabaseHas('members', [
            'name' => 'Siswa Import Excel',
            'kelas' => 'X PPLG 3',
        ]);

        @unlink($tmpFile);
    }

    /**
     * Skenario 15: PDF dengan berbagai kombinasi kolom.
     */
    public function test_15_pdf_dengan_berbagai_kombinasi_kolom(): void
    {
        $this->actingAs($this->ketuaUser);

        // Kombinasi A: Nama saja
        $resA = $this->get(route('anggota.pdf', [
            'col_no' => '1',
            'col_name' => '1',
            'col_kelas' => '0',
            'col_signature' => '0',
            'col_ket' => '0',
        ]));
        $resA->assertStatus(200);
        $resA->assertSee('NAMA LENGKAP');
        $resA->assertDontSee('TANDA TANGAN');

        // Kombinasi B: Nama dan Kelas
        $resB = $this->get(route('anggota.pdf', [
            'col_no' => '1',
            'col_name' => '1',
            'col_kelas' => '1',
            'col_signature' => '0',
        ]));
        $resB->assertStatus(200);
        $resB->assertSee('KELAS');

        // Kombinasi C: Nama, NISN, dan Tanda Tangan (Format Daftar Hadir Kegiatan)
        $resC = $this->get(route('anggota.pdf', [
            'doc_title' => 'DAFTAR HADIR KEGIATAN',
            'col_no' => '1',
            'col_name' => '1',
            'col_kelas' => '1',
            'col_nisn' => '1',
            'col_signature' => '1',
        ]));
        $resC->assertStatus(200);
        $resC->assertSee('DAFTAR HADIR KEGIATAN');
        $resC->assertSee('TANDA TANGAN');
    }

    /**
     * Skenario 16: Pengguna tanpa izin (USER biasa) mencoba mengakses fitur administratif (403).
     */
    public function test_16_pengguna_biasa_dilarang_akses_fitur_administratif(): void
    {
        $this->actingAs($this->regularUser);

        // Akses Pengaturan Pendaftaran (Khusus Admin, Ketua, Sekretaris)
        $resSettings = $this->get(route('admin.settings.registration.index'));
        $resSettings->assertStatus(403);

        // Akses Verifikasi Anggota
        $resVerif = $this->get(route('feature.verifikasi_anggota'));
        $resVerif->assertStatus(403);

        // Tambah Anggota Manual
        $resStoreAnggota = $this->post(route('anggota.store'), [
            'name' => 'Hacker Siswa',
            'kelas' => 'X PPLG 1',
            'status' => 'AKTIF',
        ]);
        $resStoreAnggota->assertStatus(403);
    }

    /**
     * Skenario 17: Akun lama tetap bisa login ketika pendaftaran ditutup.
     */
    public function test_17_akun_lama_tetap_bisa_login_ketika_pendaftaran_ditutup(): void
    {
        $settings = RegistrationSetting::current();
        $settings->update([
            'is_account_registration_open' => false,
            'is_member_registration_open' => false,
        ]);

        // Attempt login with existing credentials
        $response = $this->post('/login', [
            'email' => $this->regularUser->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->regularUser);
    }

    /**
     * Skenario 18: Seluruh fitur lama tetap berfungsi dengan baik.
     */
    public function test_18_seluruh_fitur_lama_tetap_berfungsi(): void
    {
        // 1. Welcome page loads smoothly
        $resWelcome = $this->get('/');
        $resWelcome->assertStatus(200);
        $resWelcome->assertSee('PIK-R SKAKMATT');
        $resWelcome->assertSee('Duta GenRe');
        $resWelcome->assertSee('Benaya Iskha Ghifari');
        $resWelcome->assertSee('Nazarli Nadiah Putri Yuarliana');

        // 2. Duta GenRe detail page loads
        $resDuta = $this->get('/duta-genre/benaya-iskha-ghifari');
        $resDuta->assertStatus(200);

        // 3. Informasi Edukasi & Artikel loads
        $resInfo = $this->get('/informasi');
        $resInfo->assertStatus(200);

        // 4. Authenticated internal modules load
        $this->actingAs($this->regularUser);

        $resDashboard = $this->get('/dashboard');
        $resDashboard->assertStatus(200);

        $resKas = $this->get('/kas');
        $resKas->assertStatus(200);

        $resPendaftaran = $this->get('/pendaftaran-saya');
        $resPendaftaran->assertStatus(200);
    }

    /**
     * Skenario 19: Ekspor Master Data Anggota (Excel & PDF) berfungsi dengan baik.
     */
    public function test_19_ekspor_master_anggota_excel_dan_pdf(): void
    {
        $this->actingAs($this->adminUser);

        // Test export Excel/CSV stream
        $resExcel = $this->get(route('verifikasi.master.export'));
        $resExcel->assertStatus(200);

        // Test export PDF
        $resPdf = $this->get(route('verifikasi.master.export_pdf'));
        $resPdf->assertStatus(200);
        $resPdf->assertSee('BUKU INDUK MASTER DATA SISWA');
        $resPdf->assertSee('SMK NEGERI 1 PURWOKERTO');
    }

    /**
     * Skenario 20: Permohonan calon anggota disetujui otomatis masuk data anggota,
     * dan saat mendaftar akun tidak terjadi duplikasi data (data double).
     */
    public function test_20_calon_anggota_disetujui_dan_daftar_akun_tanpa_duplikasi(): void
    {
        // 1. Calon anggota mendaftar tanpa akun user (user_id = null)
        $app = MemberApplication::create([
            'user_id' => null,
            'name' => 'Siti Rahmawati',
            'kelas' => 'X AKL 3',
            'nisn' => '0081239999',
            'phone' => '081234999901',
            'email' => 'siti.rahma@smkn1pwt.sch.id',
            'reason' => 'Ingin bergabung menjadi pendidik sebaya PIK-R SKAKMATT.',
            'data_consent' => true,
            'validation_status' => 'PERLU_PEMERIKSAAN',
            'status' => 'MENUNGGU_PERSETUJUAN',
        ]);

        // 2. Sekretaris menyetujui permohonan calon anggota
        $this->actingAs($this->sekreUser);
        $resApproveApp = $this->post(route('verifikasi.application.approve', $app));
        $resApproveApp->assertSessionHas('success');

        // Pastikan permohonan disetujui dan otomatis masuk ke tabel members (Data Anggota)
        $app->refresh();
        $this->assertEquals('DISETUJUI', $app->status);

        $member = Member::where('nisn', '0081239999')->first();
        $this->assertNotNull($member, 'Data anggota harus otomatis terbuat di tabel members setelah permohonan disetujui.');
        $this->assertEquals('Siti Rahmawati', $member->name);
        $this->assertEquals('X AKL 3', $member->kelas);
        $this->assertEquals('AKTIF', $member->status);

        $initialMemberId = $member->id;
        $countMembersWithNisn = Member::where('nisn', '0081239999')->count();
        $this->assertEquals(1, $countMembersWithNisn);

        // Pastikan otomatis masuk ke Buku Induk (MasterMember) dengan status "Belum Akun"
        $master = MasterMember::where('nisn', '0081239999')->first();
        $this->assertNotNull($master, 'Data siswa harus otomatis muncul di Buku Induk saat permohonan calon anggota disetujui.');
        $this->assertFalse($master->is_registered, 'Status akun di Buku Induk harus Belum Akun (is_registered = false).');
        $this->assertEquals('Siti Rahmawati', $master->name);
        $this->assertEquals('X AKL 3', $master->kelas);

        // 3. Siswa kemudian mendaftar akun (Tahap 1 & 2)
        $newUser = User::create([
            'name' => 'Siti Rahmawati',
            'email' => 'siti.rahma@smkn1pwt.sch.id',
            'password' => Hash::make('password123'),
            'kelas' => 'X AKL 3',
            'nisn' => '0081239999',
            'phone' => '081234999901',
            'role' => 'USER',
            'status_verifikasi' => 'PENDING',
            'matched_application_id' => $app->id,
            'match_status' => 'TERVERIFIKASI',
        ]);
        $newUser->assignRole('USER');

        // 4. Admin menyetujui akun siswa tersebut (Tahap 3)
        $this->actingAs($this->adminUser);
        $resApproveUser = $this->post(route('verifikasi.approve', $newUser));
        $resApproveUser->assertSessionHas('success');

        // 5. Verifikasi: data anggota TIDAK BOLEH DOUBLE
        $finalCountWithNisn = Member::where('nisn', '0081239999')->count();
        $this->assertEquals(1, $finalCountWithNisn, 'Tidak boleh ada data ganda pada tabel members setelah akun disetujui.');

        $finalCountWithName = Member::whereRaw('LOWER(TRIM(name)) = ?', ['siti rahmawati'])->count();
        $this->assertEquals(1, $finalCountWithName, 'Tidak boleh ada nama siswa duplikat pada tabel members.');

        $updatedMember = Member::where('nisn', '0081239999')->first();
        $this->assertEquals($initialMemberId, $updatedMember->id, 'Data anggota yang sudah ada harus diperbarui, bukan membuat row baru.');
        $this->assertEquals($newUser->id, $updatedMember->user_id, 'user_id harus terhubung ke akun user yang baru disetujui.');
        $this->assertEquals('AKTIF', $updatedMember->status);

        // Verifikasi status akun di Buku Induk berubah menjadi Terdaftar (is_registered = true)
        $master->refresh();
        $this->assertTrue($master->is_registered, 'Status akun di Buku Induk harus otomatis berubah menjadi Terdaftar setelah akun disetujui.');
        $this->assertEquals($newUser->id, $master->user_id);

        // 6. Coba tambahkan manual data yang sama via AnggotaController::store -> harus ditolak
        $this->actingAs($this->sekreUser);
        $resDuplicateManual = $this->post(route('anggota.store'), [
            'name' => 'Siti Rahmawati',
            'kelas' => 'X AKL 3',
            'nisn' => '0081239999',
            'phone' => '081234999901',
            'status' => 'AKTIF',
        ]);
        $resDuplicateManual->assertSessionHas('error');
        $this->assertEquals(1, Member::where('nisn', '0081239999')->count());
    }
}
