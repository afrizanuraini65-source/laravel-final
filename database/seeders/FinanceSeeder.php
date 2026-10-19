<?php

namespace Database\Seeders;

use App\Models\CashTransaction;
use App\Models\Due;
use App\Models\Member;
use App\Models\OrganizationPeriod;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FinanceSeeder extends Seeder
{
    /**
     * Run the database seeds for Kas and Keuangan.
     */
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            return;
        }

        $period = OrganizationPeriod::where('is_active', true)->first()
            ?? OrganizationPeriod::first();

        $weeklyDate = Carbon::now('Asia/Jakarta')->startOfDay();
        if ($weeklyDate->dayOfWeek !== Carbon::WEDNESDAY) {
            $weeklyDate->previous(Carbon::WEDNESDAY);
        }

        $due = Due::firstOrCreate(
            ['weekly_period' => $weeklyDate->toDateString()],
            [
                'organization_period_id' => $period?->id,
                'title' => 'Kas Mingguan - Rabu '.$weeklyDate->format('d/m/Y'),
                'type' => 'BULANAN',
                'target_type' => 'SEMUA',
                'amount' => 2000,
                'deadline' => $weeklyDate->toDateString(),
                'description' => 'Iuran kas mingguan Rp2.000 per anggota aktif; jatuh tempo setiap Rabu.',
            ]
        );

        $members = Member::with('user')
            ->where('status', 'AKTIF')
            ->whereNotNull('user_id')
            ->get()
            ->filter(fn (Member $member) => $member->user !== null)
            ->take(30)
            ->values();
        $verifiers = User::role('BENDAHARA')->get();
        $daysSinceDue = (int) $weeklyDate->diffInDays(Carbon::now('Asia/Jakarta')->startOfDay());

        foreach ($members as $index => $member) {
            $status = match ($index % 10) {
                0 => 'MENUNGGU_VERIFIKASI',
                1 => 'DITOLAK',
                default => 'LUNAS',
            };
            $paymentDate = $weeklyDate->copy()->addDays($daysSinceDue === 0 ? 0 : $index % ($daysSinceDue + 1));
            $verifier = $verifiers->isEmpty() ? null : $verifiers->get($index % $verifiers->count());

            $payment = Payment::firstOrCreate(
                ['due_id' => $due->id, 'user_id' => $member->user_id],
                [
                    'amount_paid' => $due->amount,
                    'payment_method' => ['CASH', 'TRANSFER', 'QRIS'][$index % 3],
                    'status' => $status,
                    'proof_file' => null,
                    'rejection_reason' => $status === 'DITOLAK' ? 'Bukti pembayaran demo tidak terbaca.' : null,
                    'verified_by_user_id' => $status === 'LUNAS' ? $verifier?->id : null,
                    'verified_at' => $status === 'LUNAS' ? $paymentDate : null,
                    'created_at' => $paymentDate,
                    'updated_at' => $paymentDate,
                ]
            );

            if ($payment->status === 'LUNAS') {
                CashTransaction::firstOrCreate(
                    ['payment_id' => $payment->id],
                    [
                        'type' => 'INCOME',
                        'amount' => $payment->amount_paid,
                        'description' => 'Pembayaran '.$due->title.' - '.$member->name,
                        'transaction_date' => $paymentDate->toDateString(),
                        'created_by' => $payment->verified_by_user_id,
                    ]
                );
            }
        }
    }
}
