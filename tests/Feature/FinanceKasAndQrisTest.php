<?php

namespace Tests\Feature;

use App\Models\CashTransaction;
use App\Models\Division;
use App\Models\Due;
use App\Models\Member;
use App\Models\Payment;
use App\Models\PaymentSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FinanceKasAndQrisTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $bendahara;

    protected User $ketua;

    protected User $memberUser;

    protected Division $division;

    protected function setUp(): void
    {
        parent::setUp();

        // Create standard roles
        Role::firstOrCreate(['name' => 'ADMIN']);
        Role::firstOrCreate(['name' => 'KETUA']);
        Role::firstOrCreate(['name' => 'BENDAHARA']);
        Role::firstOrCreate(['name' => 'USER']);

        $this->division = Division::firstOrCreate(
            ['name' => 'Divisi Kegiatan'],
            ['description' => 'Divisi Kegiatan']
        );

        $this->admin = User::factory()->create(['name' => 'Admin Test', 'email' => 'admin_test@pikr.com']);
        $this->admin->assignRole('ADMIN');

        $this->bendahara = User::factory()->create(['name' => 'Bendahara Test', 'email' => 'bendahara_test@pikr.com']);
        $this->bendahara->assignRole('BENDAHARA');

        $this->ketua = User::factory()->create(['name' => 'Ketua Test', 'email' => 'ketua_test@pikr.com']);
        $this->ketua->assignRole('KETUA');

        $this->memberUser = User::factory()->create(['name' => 'Anggota Test', 'email' => 'anggota_test@pikr.com', 'kelas' => 'XI RPL 1']);
        $this->memberUser->assignRole('USER');

        Member::create([
            'user_id' => $this->memberUser->id,
            'name' => 'Anggota Test',
            'kelas' => 'XI RPL 1',
            'status' => 'AKTIF',
            'division_id' => $this->division->id,
        ]);
    }

    public function test_qris_image_file_exists_on_disk(): void
    {
        $qrisPath = public_path('images/qris_pikr.jpeg');
        $this->assertFileExists($qrisPath, 'File qris_pikr.jpeg harus tersedia di folder public/images.');
        $this->assertGreaterThan(0, filesize($qrisPath));
    }

    public function test_finance_manager_can_access_management_and_regular_user_cannot(): void
    {
        // 1. Bendahara access
        $responseBendahara = $this->actingAs($this->bendahara)->get(route('kas.index'));
        $responseBendahara->assertStatus(200);
        $responseBendahara->assertSee('Pusat Pengelolaan');
        $responseBendahara->assertSee('Tabel Pembayaran Anggota');
        $responseBendahara->assertSee('Export Rekap Kas (CSV)');
        $responseBendahara->assertSee('Buat Tagihan Baru');

        // 2. Regular User access to kas.index sees personal section
        $responseMember = $this->actingAs($this->memberUser)->get(route('kas.index'));
        $responseMember->assertStatus(200);
        $responseMember->assertSee('Tagihan Kas Milik Saya');
        $responseMember->assertDontSee('Tabel Pembayaran Anggota');
        $responseMember->assertDontSee('Export Rekap Kas (CSV)');

        // 3. Regular User attempting management routes gets 403 Forbidden
        $responseStore = $this->actingAs($this->memberUser)->post(route('kas.store'), [
            'title' => 'Tagihan Ilegal',
            'amount' => 5000,
            'type' => 'BULANAN',
        ]);
        $responseStore->assertStatus(403);

        $responseExport = $this->actingAs($this->memberUser)->get(route('kas.export'));
        $responseExport->assertStatus(403);
    }

    public function test_pengurus_can_create_update_and_safe_delete_due(): void
    {
        // 1. Create Due
        $response = $this->actingAs($this->bendahara)->post(route('kas.store'), [
            'title' => 'Iuran Kas Bulan Oktober',
            'amount' => 10000,
            'type' => 'BULANAN',
            'target_type' => 'DIVISI',
            'target_division_id' => $this->division->id,
            'deadline' => '2026-10-31',
            'description' => 'Iuran wajib anggota divisi kegiatan',
        ]);

        $response->assertRedirect(route('kas.index'));
        $this->assertDatabaseHas('dues', [
            'title' => 'Iuran Kas Bulan Oktober',
            'amount' => 10000,
            'target_type' => 'DIVISI',
            'target_division_id' => $this->division->id,
        ]);

        $due = Due::where('title', 'Iuran Kas Bulan Oktober')->first();

        // 2. Update Due
        $responseUpdate = $this->actingAs($this->bendahara)->put(route('kas.update', $due->id), [
            'title' => 'Iuran Kas Bulan Oktober (Revisi)',
            'amount' => 15000,
            'type' => 'BULANAN',
            'target_type' => 'SEMUA',
            'deadline' => '2026-11-05',
            'description' => 'Revisi untuk semua anggota',
        ]);

        $responseUpdate->assertRedirect(route('kas.index'));
        $this->assertDatabaseHas('dues', [
            'id' => $due->id,
            'title' => 'Iuran Kas Bulan Oktober (Revisi)',
            'amount' => 15000,
            'target_type' => 'SEMUA',
        ]);

        // 3. Delete Due without payments succeeds
        $responseDelete = $this->actingAs($this->bendahara)->delete(route('kas.destroy', $due->id));
        $responseDelete->assertRedirect(route('kas.index'));
        $this->assertDatabaseMissing('dues', ['id' => $due->id]);
    }

    public function test_weekly_dues_are_created_for_wednesday_and_are_idempotent(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-20 12:00:00', 'Asia/Jakarta'));

        try {
            $this->artisan('app:generate-weekly-dues')
                ->expectsOutput('1 tagihan kas mingguan dibuat. Tagihan yang sudah ada tidak diubah.')
                ->assertExitCode(0);

            $this->artisan('app:generate-weekly-dues')
                ->expectsOutput('0 tagihan kas mingguan dibuat. Tagihan yang sudah ada tidak diubah.')
                ->assertExitCode(0);
        } finally {
            Carbon::setTestNow();
        }

        $this->assertDatabaseHas('dues', [
            'weekly_period' => '2026-10-21',
            'amount' => 2000,
            'deadline' => '2026-10-21',
        ]);
        $this->assertSame(1, Due::whereDate('weekly_period', '2026-10-21')->count());
    }

    public function test_explicit_weekly_backfill_requires_wednesdays_and_does_not_duplicate(): void
    {
        $this->artisan('app:generate-weekly-dues', [
            '--from' => '2026-10-07',
            '--through' => '2026-10-21',
        ])->assertExitCode(0);

        $this->assertSame(3, Due::whereNotNull('weekly_period')->count());

        $this->artisan('app:generate-weekly-dues', [
            '--from' => '2026-10-07',
            '--through' => '2026-10-21',
        ])->assertExitCode(0);

        $this->assertSame(3, Due::whereNotNull('weekly_period')->count());
        $this->artisan('app:generate-weekly-dues', [
            '--from' => '2026-10-08',
            '--through' => '2026-10-21',
        ])->assertExitCode(1);
    }

    public function test_finance_manager_who_is_an_active_member_has_personal_dues(): void
    {
        Member::create([
            'user_id' => $this->bendahara->id,
            'name' => $this->bendahara->name,
            'kelas' => 'XI RPL 2',
            'status' => 'AKTIF',
            'division_id' => $this->division->id,
        ]);
        Due::create([
            'title' => 'Kas Pribadi Bendahara',
            'amount' => 2000,
            'type' => 'BULANAN',
            'target_type' => 'SEMUA',
        ]);

        $dashboardResponse = $this->actingAs($this->bendahara)->get(route('dashboard'));
        $dashboardResponse->assertViewHas('myActiveDuesList', fn (array $dues) => collect($dues)
            ->contains(fn (array $item) => $item['due']->title === 'Kas Pribadi Bendahara'
                && $item['status'] === 'BELUM BAYAR'));

        $kasResponse = $this->actingAs($this->bendahara)->get(route('kas.index'));
        $kasResponse->assertSee('Kas Pribadi Bendahara');
    }

    public function test_member_cannot_submit_payment_for_another_division_or_without_membership(): void
    {
        $otherDivision = Division::create(['name' => 'Divisi Lain']);
        $due = Due::create([
            'title' => 'Kas Divisi Kegiatan',
            'amount' => 2000,
            'type' => 'BULANAN',
            'target_type' => 'DIVISI',
            'target_division_id' => $this->division->id,
        ]);

        $otherMember = User::factory()->create(['email' => 'other_division@pikr.com']);
        $otherMember->assignRole('USER');
        Member::create([
            'user_id' => $otherMember->id,
            'name' => $otherMember->name,
            'status' => 'AKTIF',
            'division_id' => $otherDivision->id,
        ]);

        Storage::fake('public');
        $proof = UploadedFile::fake()->create('proof.jpg', 100, 'image/jpeg');
        $this->actingAs($otherMember)->post(route('payments.store'), [
            'due_id' => $due->id,
            'payment_method' => 'QRIS',
            'proof' => $proof,
        ])->assertForbidden();

        $unlinkedUser = User::factory()->create(['email' => 'unlinked@pikr.com']);
        $unlinkedUser->assignRole('USER');
        $this->actingAs($unlinkedUser)->post(route('payments.store'), [
            'due_id' => $due->id,
            'payment_method' => 'QRIS',
            'proof' => UploadedFile::fake()->create('proof.jpg', 100, 'image/jpeg'),
        ])->assertForbidden();
    }

    public function test_member_cannot_submit_a_second_pending_payment_for_same_due(): void
    {
        Storage::fake('public');
        $due = Due::create([
            'title' => 'Kas Satu Kali Submit',
            'amount' => 2000,
            'type' => 'BULANAN',
            'target_type' => 'SEMUA',
        ]);

        $payload = [
            'due_id' => $due->id,
            'payment_method' => 'QRIS',
            'proof' => UploadedFile::fake()->create('proof.jpg', 100, 'image/jpeg'),
        ];

        $this->actingAs($this->memberUser)->post(route('payments.store'), $payload)->assertRedirect(route('kas.index'));
        $this->actingAs($this->memberUser)->post(route('payments.store'), [
            ...$payload,
            'proof' => UploadedFile::fake()->create('duplicate.jpg', 100, 'image/jpeg'),
        ])->assertSessionHasErrors('due_id');

        $this->assertSame(1, Payment::where('due_id', $due->id)->where('user_id', $this->memberUser->id)->count());
    }

    public function test_member_payment_submission_status_is_pending_and_not_counted_in_cash(): void
    {
        Storage::fake('public');

        $due = Due::create([
            'title' => 'Kas Wajib Member',
            'amount' => 5000,
            'type' => 'BULANAN',
            'target_type' => 'SEMUA',
        ]);

        $fakeProof = UploadedFile::fake()->create('bukti_transfer.jpg', 150, 'image/jpeg');
        $initialVerifiedIncome = CashTransaction::verifiedIncomeTotal();

        $response = $this->actingAs($this->memberUser)->post(route('payments.store'), [
            'due_id' => $due->id,
            'payment_method' => 'QRIS',
            'proof' => $fakeProof,
        ]);

        $response->assertRedirect(route('kas.index'));

        // Payment status must be MENUNGGU_VERIFIKASI
        $this->assertDatabaseHas('payments', [
            'due_id' => $due->id,
            'user_id' => $this->memberUser->id,
            'amount_paid' => 5000,
            'payment_method' => 'QRIS',
            'status' => 'MENUNGGU_VERIFIKASI',
        ]);

        // Cash transaction must NOT be created for this payment yet
        $payment = Payment::where('due_id', $due->id)->where('user_id', $this->memberUser->id)->first();
        $this->assertNotNull($payment);
        $this->assertDatabaseMissing('cash_transactions', [
            'payment_id' => $payment->id,
        ]);
        $this->assertSame($initialVerifiedIncome, CashTransaction::verifiedIncomeTotal());
    }

    public function test_pengurus_can_approve_payment_and_creates_cash_transaction(): void
    {
        Storage::fake('public');

        $due = Due::create([
            'title' => 'Kas Event PIK-R',
            'amount' => 20000,
            'type' => 'KEGIATAN',
            'target_type' => 'SEMUA',
        ]);

        $payment = Payment::create([
            'due_id' => $due->id,
            'user_id' => $this->memberUser->id,
            'amount_paid' => 20000,
            'payment_method' => 'QRIS',
            'proof_file' => 'proofs/dummy.jpg',
            'status' => 'MENUNGGU_VERIFIKASI',
        ]);

        $initialIncome = (float) CashTransaction::where('type', 'INCOME')->sum('amount');

        // Bendahara verifies and approves payment
        $response = $this->actingAs($this->bendahara)->post(route('payments.verify', $payment->id), [
            'action' => 'setujui',
        ]);

        $response->assertRedirect();

        // Payment must be LUNAS
        $payment->refresh();
        $this->assertEquals('LUNAS', $payment->status);
        $this->assertEquals($this->bendahara->id, $payment->verified_by_user_id);
        $this->assertNotNull($payment->verified_at);

        // CashTransaction must be created
        $this->assertDatabaseHas('cash_transactions', [
            'payment_id' => $payment->id,
            'type' => 'INCOME',
            'amount' => 20000,
        ]);

        $newIncome = (float) CashTransaction::where('type', 'INCOME')->sum('amount');
        $this->assertEquals($initialIncome + 20000, $newIncome);
        $verifiedIncome = CashTransaction::verifiedIncomeTotal();

        $this->actingAs($this->bendahara)->post(route('payments.verify', $payment->id), [
            'action' => 'tolak',
            'rejection_reason' => 'Status pembayaran sudah diproses.',
        ])->assertSessionHasErrors('status');

        $this->assertSame($verifiedIncome, CashTransaction::verifiedIncomeTotal());
        $this->assertDatabaseHas('cash_transactions', ['payment_id' => $payment->id, 'amount' => 20000]);
    }

    public function test_pengurus_can_reject_payment_and_removes_any_cash_transaction(): void
    {
        $initialIncome = CashTransaction::verifiedIncomeTotal();

        $due = Due::create([
            'title' => 'Kas Khusus',
            'amount' => 15000,
            'type' => 'KHUSUS',
            'target_type' => 'SEMUA',
        ]);

        $payment = Payment::create([
            'due_id' => $due->id,
            'user_id' => $this->memberUser->id,
            'amount_paid' => 15000,
            'payment_method' => 'QRIS',
            'proof_file' => 'proofs/dummy.jpg',
            'status' => 'MENUNGGU_VERIFIKASI',
        ]);

        $initialTxCount = CashTransaction::count();

        // Bendahara rejects payment
        $response = $this->actingAs($this->bendahara)->post(route('payments.verify', $payment->id), [
            'action' => 'tolak',
            'rejection_reason' => 'Gambar buram dan mutasi belum masuk.',
        ]);

        $response->assertRedirect();

        $payment->refresh();
        $this->assertEquals('DITOLAK', $payment->status);
        $this->assertEquals('Gambar buram dan mutasi belum masuk.', $payment->rejection_reason);

        // Verify no cash transaction for this payment and count unchanged
        $this->assertDatabaseMissing('cash_transactions', [
            'payment_id' => $payment->id,
        ]);
        $this->assertEquals($initialTxCount, CashTransaction::count());
        $this->assertSame($initialIncome, CashTransaction::verifiedIncomeTotal());
    }

    public function test_due_with_verified_payment_cannot_be_deleted(): void
    {
        $due = Due::create([
            'title' => 'Kas Terkunci',
            'amount' => 10000,
            'type' => 'BULANAN',
            'target_type' => 'SEMUA',
        ]);

        Payment::create([
            'due_id' => $due->id,
            'user_id' => $this->memberUser->id,
            'amount_paid' => 10000,
            'status' => 'LUNAS',
            'payment_method' => 'CASH',
        ]);

        $response = $this->actingAs($this->bendahara)->delete(route('kas.destroy', $due->id));
        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Due must still exist
        $this->assertDatabaseHas('dues', ['id' => $due->id]);
    }

    public function test_export_rekap_kas_csv_downloads_valid_csv(): void
    {
        $due = Due::create([
            'title' => 'Iuran Ekspor',
            'amount' => 12000,
            'type' => 'BULANAN',
        ]);

        Payment::create([
            'due_id' => $due->id,
            'user_id' => $this->memberUser->id,
            'amount_paid' => 12000,
            'status' => 'LUNAS',
            'payment_method' => 'QRIS',
        ]);

        $response = $this->actingAs($this->bendahara)->get(route('kas.export'));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'text/csv'));
        $content = $response->streamedContent();
        $this->assertStringContainsString('Iuran Ekspor', $content);
        $this->assertStringContainsString('12000', $content);
    }

    public function test_dashboard_synchronization_for_pengurus_and_member(): void
    {
        $due = Due::create([
            'title' => 'Kas Sinkronisasi',
            'amount' => 25000,
            'type' => 'BULANAN',
            'target_type' => 'SEMUA',
        ]);

        // 1. Pengurus Dashboard check
        $resPengurus = $this->actingAs($this->bendahara)->get(route('dashboard'));
        $resPengurus->assertStatus(200);
        $resPengurus->assertSee('Ringkasan Kas & Tagihan PIK-R SKAKMATT', false);
        $resPengurus->assertSee('Dana Terverifikasi');
        $resPengurus->assertSee('Target Anggota');

        // 2. Member Dashboard check
        $resMember = $this->actingAs($this->memberUser)->get(route('dashboard'));
        $resMember->assertStatus(200);
        $resMember->assertSee('Status Tagihan Kas Saya');
        $resMember->assertSee('Kas Sinkronisasi');
        $resMember->assertSee('Bayar Sekarang');
    }

    public function test_payment_modal_displays_bank_accounts_and_ewallet_number(): void
    {
        Due::create([
            'title' => 'Kas untuk Uji Metode',
            'amount' => 2000,
            'type' => 'BULANAN',
            'target_type' => 'SEMUA',
        ]);

        PaymentSetting::current()->update([
            'bank_name' => 'Bank Uji',
            'account_holder' => 'Pemilik Uji',
            'account_number' => '999000111222',
            'wallet_provider' => 'Dompet Uji',
            'wallet_account_holder' => 'Pemilik Dompet Uji',
            'wallet_account_number' => '081234567890',
        ]);
        $this->assertDatabaseHas('payment_settings', [
            'bank_name' => 'Bank Uji',
            'account_number' => '999000111222',
        ]);

        $response = $this->actingAs($this->memberUser)->get(route('kas.index'));
        $response->assertStatus(200);
        $response->assertViewHas('paymentSettings', fn (PaymentSetting $settings) => $settings->account_number === '999000111222');
        $response->assertSee('999000111222');
        $response->assertSee('Bank Uji');
        $response->assertSee('Pemilik Uji');
        $response->assertSee('081234567890');
        $response->assertSee('Dompet Uji');
        $response->assertSee('Bayar Sekarang');
    }

    public function test_payment_details_are_empty_until_configured_and_settings_are_finance_only(): void
    {
        Due::create([
            'title' => 'Kas untuk Uji Konfigurasi',
            'amount' => 2000,
            'type' => 'BULANAN',
            'target_type' => 'SEMUA',
        ]);

        $memberResponse = $this->actingAs($this->memberUser)->get(route('kas.index'));
        $memberResponse->assertStatus(200);
        $memberResponse->assertSee('Rekening bank belum dikonfigurasi.');
        $memberResponse->assertDontSee('046-2003-6841');
        $memberResponse->assertDontSee('0882003684111');
        $this->actingAs($this->memberUser)->get(route('admin.settings.payment.index'))->assertForbidden();

        $adminResponse = $this->actingAs($this->admin)->get(route('admin.settings.payment.index'));
        $adminResponse->assertOk();
        $adminResponse->assertSee('Informasi Pembayaran');

        $this->actingAs($this->admin)->put(route('admin.settings.payment.update'), [
            'bank_name' => 'Bank Uji',
            'account_holder' => 'Pemilik Uji',
            'account_number' => '999000111222',
            'instructions' => 'Cantumkan nama anggota pada berita transfer.',
        ])->assertRedirect(route('admin.settings.payment.index'));

        $this->assertDatabaseHas('payment_settings', [
            'id' => 1,
            'bank_name' => 'Bank Uji',
            'account_number' => '999000111222',
        ]);
    }

    public function test_member_can_submit_payment_with_ewallet_and_transfer_methods(): void
    {
        Storage::fake('public');
        PaymentSetting::current()->update([
            'bank_name' => 'Bank Uji',
            'account_holder' => 'Pemilik Uji',
            'account_number' => '999000111222',
            'wallet_provider' => 'Dompet Uji',
            'wallet_account_holder' => 'Pemilik Uji',
            'wallet_account_number' => '081234567890',
        ]);

        $due = Due::create([
            'title' => 'Iuran Multi Metode',
            'amount' => 10000,
            'type' => 'BULANAN',
            'target_type' => 'SEMUA',
        ]);

        $fakeProof = UploadedFile::fake()->create('bukti_ewallet.jpg', 150, 'image/jpeg');

        // Submit via EWALLET
        $response = $this->actingAs($this->memberUser)->post(route('payments.store'), [
            'due_id' => $due->id,
            'payment_method' => 'EWALLET',
            'proof' => $fakeProof,
        ]);

        $response->assertRedirect(route('kas.index'));

        $this->assertDatabaseHas('payments', [
            'due_id' => $due->id,
            'user_id' => $this->memberUser->id,
            'amount_paid' => 10000,
            'payment_method' => 'EWALLET',
            'status' => 'MENUNGGU_VERIFIKASI',
        ]);

        $transferDue = Due::create([
            'title' => 'Iuran Transfer Bank',
            'amount' => 15000,
            'type' => 'BULANAN',
            'target_type' => 'SEMUA',
        ]);

        $this->actingAs($this->memberUser)->post(route('payments.store'), [
            'due_id' => $transferDue->id,
            'payment_method' => 'TRANSFER',
            'proof' => UploadedFile::fake()->create('bukti_transfer.jpg', 150, 'image/jpeg'),
        ])->assertRedirect(route('kas.index'));

        $this->assertDatabaseHas('payments', [
            'due_id' => $transferDue->id,
            'user_id' => $this->memberUser->id,
            'payment_method' => 'TRANSFER',
            'status' => 'MENUNGGU_VERIFIKASI',
        ]);
    }
}
