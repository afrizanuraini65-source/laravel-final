<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\MemberApplication;
use App\Models\OrganizationPeriod;
use App\Models\RegistrationSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FinalAutoMatchingAndApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected OrganizationPeriod $period;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'ADMIN']);
        Role::firstOrCreate(['name' => 'KETUA']);
        Role::firstOrCreate(['name' => 'SEKRETARIS']);
        Role::firstOrCreate(['name' => 'USER']);

        $this->period = OrganizationPeriod::firstOrCreate(
            ['name' => '2025/2026'],
            ['start_date' => '2025-07-01', 'end_date' => '2026-06-30', 'is_active' => true]
        );

        $this->adminUser = User::firstOrCreate(
            ['email' => 'admin_matching_tester@pikr.test'],
            ['name' => 'Admin Matching Tester', 'password' => Hash::make('password'), 'role' => 'ADMIN']
        );
        $this->adminUser->syncRoles(['ADMIN']);
    }

    /**
     * Skenario Kondisi 1: Data cocok dan pendaftaran anggota sudah disetujui
     */
    public function test_kondisi_1_data_cocok_dan_disetujui_terverifikasi_otomatis(): void
    {
        RegistrationSetting::current()->update([
            'is_account_registration_open' => true,
            'is_member_registration_open' => true,
        ]);

        // 1. Pendaftaran anggota disetujui oleh pengurus
        $app = MemberApplication::create([
            'name' => 'Reva Nagali Aningtyas',
            'kelas' => 'XII PPLG 2',
            'nisn' => '0061122334',
            'phone' => '081234567811',
            'email' => 'reva_pplg@gmail.com',
            'reason' => 'Ingin aktif memajukan PIK-R SKAKMATT.',
            'data_consent' => true,
            'status' => 'DISETUJUI',
            'validation_status' => 'VALID_AUTOMATIS',
        ]);

        // 2. Siswa mendaftar akun di register
        session([
            'reg_otp' => '998877',
            'reg_otp_email' => 'reva_pplg@gmail.com',
            'reg_otp_expires_at' => now()->addMinutes(15),
        ]);

        $response = $this->post('/register', [
            'name' => 'Reva Nagali Aningtyas',
            'email' => 'reva_pplg@gmail.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'kelas' => 'XII PPLG 2',
            'nisn' => '0061122334',
            'birth_place' => 'Banyumas',
            'birth_date' => '2007-08-15',
            'phone' => '081234567811',
            'verification_code' => '998877',
        ]);

        $response->assertRedirect(route('verification.pending'));

        $user = User::where('email', 'reva_pplg@gmail.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('PENDING', $user->status_verifikasi);
        $this->assertEquals('TERVERIFIKASI', $user->match_status);
        $this->assertEquals('Reva Nagali Aningtyas', $user->matched_name);
        $this->assertEquals('XII PPLG 2', $user->matched_kelas);
        $this->assertEquals($app->id, $user->matched_application_id);

        // 3. Admin approval (Tahap 3)
        $this->actingAs($this->adminUser);
        $approveResponse = $this->post(route('verifikasi.approve', $user));
        $approveResponse->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('APPROVED', $user->status_verifikasi);

        // Member record created and active
        $this->assertDatabaseHas('members', [
            'user_id' => $user->id,
            'name' => 'Reva Nagali Aningtyas',
            'kelas' => 'XII PPLG 2',
            'status' => 'AKTIF',
        ]);
    }

    /**
     * Skenario Kondisi 2: Data tidak ditemukan dalam daftar keanggotaan
     */
    public function test_kondisi_2_data_tidak_ditemukan(): void
    {
        RegistrationSetting::current()->update([
            'is_account_registration_open' => true,
        ]);

        session([
            'reg_otp' => '112233',
            'reg_otp_email' => 'orang_asing@gmail.com',
            'reg_otp_expires_at' => now()->addMinutes(15),
        ]);

        $response = $this->post('/register', [
            'name' => 'Siswa Belum Pernah Daftar Anggota',
            'email' => 'orang_asing@gmail.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'kelas' => 'X DKV 1',
            'nisn' => '9999990000',
            'birth_place' => 'Banyumas',
            'birth_date' => '2008-01-01',
            'phone' => '081234567899',
            'verification_code' => '112233',
        ]);

        $response->assertRedirect(route('verification.pending'));

        $user = User::where('email', 'orang_asing@gmail.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('PENDING', $user->status_verifikasi);
        $this->assertEquals('TIDAK_DITEMUKAN', $user->match_status);
        $this->assertEquals('TIDAK_DITEMUKAN', $user->membership_status);
    }

    /**
     * Skenario Kondisi 3: Data pendaftaran anggota masih menunggu verifikasi (belum disetujui)
     */
    public function test_kondisi_3_data_pendaftaran_masih_menunggu_verifikasi(): void
    {
        RegistrationSetting::current()->update([
            'is_account_registration_open' => true,
        ]);

        MemberApplication::create([
            'name' => 'Budi Masih Menunggu',
            'kelas' => 'XI AKL 2',
            'nisn' => '0088112233',
            'phone' => '081234567888',
            'email' => 'budi_menunggu@gmail.com',
            'reason' => 'Ingin bergabung dengan PIK-R SKAKMATT.',
            'data_consent' => true,
            'status' => 'MENUNGGU_PERSETUJUAN',
        ]);

        session([
            'reg_otp' => '445566',
            'reg_otp_email' => 'budi_menunggu@gmail.com',
            'reg_otp_expires_at' => now()->addMinutes(15),
        ]);

        $response = $this->post('/register', [
            'name' => 'Budi Masih Menunggu',
            'email' => 'budi_menunggu@gmail.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'kelas' => 'XI AKL 2',
            'nisn' => '0088112233',
            'birth_place' => 'Purwokerto',
            'birth_date' => '2008-02-02',
            'phone' => '081234567888',
            'verification_code' => '445566',
        ]);

        $response->assertRedirect(route('verification.pending'));

        $user = User::where('email', 'budi_menunggu@gmail.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('PENDING', $user->status_verifikasi);
        $this->assertEquals('MENUNGGU_VERIFIKASI_ANGGOTA', $user->match_status);
        $this->assertEquals('MENUNGGU', $user->membership_status);
    }

    /**
     * Skenario Kondisi 5: Data keanggotaan sudah terhubung dengan akun lain
     */
    public function test_kondisi_5_data_keanggotaan_sudah_terhubung_akun_lain(): void
    {
        RegistrationSetting::current()->update([
            'is_account_registration_open' => true,
        ]);

        $existingUser = User::create([
            'name' => 'User Pertama',
            'email' => 'user_pertama@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'USER',
        ]);

        $app = MemberApplication::create([
            'user_id' => $existingUser->id,
            'name' => 'Siswa Ganda',
            'kelas' => 'XI PPLG 1',
            'nisn' => '0055443322',
            'phone' => '081234567777',
            'email' => 'user_pertama@gmail.com',
            'reason' => 'Ingin bergabung.',
            'data_consent' => true,
            'status' => 'DISETUJUI',
        ]);

        session([
            'reg_otp' => '556677',
            'reg_otp_email' => 'akun_kedua@gmail.com',
            'reg_otp_expires_at' => now()->addMinutes(15),
        ]);

        $response = $this->post('/register', [
            'name' => 'Siswa Ganda',
            'email' => 'akun_kedua@gmail.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'kelas' => 'XI PPLG 1',
            'nisn' => '0055443322',
            'birth_place' => 'Purwokerto',
            'birth_date' => '2007-03-03',
            'phone' => '081234567777',
            'verification_code' => '556677',
        ]);

        $response->assertRedirect(route('verification.pending'));

        $user = User::where('email', 'akun_kedua@gmail.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('SUDAH_TERHUBUNG', $user->match_status);
    }
}
