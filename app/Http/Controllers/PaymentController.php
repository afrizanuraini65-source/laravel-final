<?php

namespace App\Http\Controllers;

use App\Models\CashTransaction;
use App\Models\Due;
use App\Models\Payment;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['due', 'user', 'verifier'])
            ->latest()
            ->get();

        return view('finance.verification', [
            'payments' => $payments,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'due_id' => 'required|exists:dues,id',
            'payment_method' => 'required|in:TRANSFER,QRIS,CASH,EWALLET',
            'proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $method = $request->payment_method;

        if ($method === 'QRIS' && ! is_file(public_path('images/qris_pikr.jpeg'))) {
            abort(422, 'Metode QRIS belum tersedia.');
        }

        $paymentSettings = PaymentSetting::current();
        if (($method === 'TRANSFER' && ! $paymentSettings->hasBankAccount())
            || ($method === 'EWALLET' && ! $paymentSettings->hasWalletAccount())) {
            abort(422, 'Metode pembayaran belum dikonfigurasi.');
        }

        DB::transaction(function () use ($request, $user, $method): void {
            $due = Due::query()->lockForUpdate()->findOrFail($request->due_id);

            if (! $due->appliesTo($user)) {
                abort(403, 'Tagihan ini tidak berlaku untuk akun Anda.');
            }

            $existingPayment = Payment::where('due_id', $due->id)
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if ($existingPayment && in_array($existingPayment->status, ['LUNAS', 'MENUNGGU_VERIFIKASI'], true)) {
                throw ValidationException::withMessages([
                    'due_id' => 'Pembayaran untuk tagihan ini sudah tercatat dan tidak dapat dikirim ulang.',
                ]);
            }

            $path = $request->file('proof')->store('proofs', 'public');
            Payment::updateOrCreate(
                ['due_id' => $due->id, 'user_id' => $user->id],
                [
                    'amount_paid' => $due->amount,
                    'status' => 'MENUNGGU_VERIFIKASI',
                    'payment_method' => $method,
                    'proof_file' => $path,
                    'rejection_reason' => null,
                ]
            );
        });

        return redirect()->route('kas.index')->with('success', 'Pembayaran berhasil dikirim dan menunggu verifikasi.');
    }

    public function verify(Request $request, Payment $payment)
    {
        if ($request->has('action')) {
            $mappedStatus = match ($request->action) {
                'setujui' => 'LUNAS',
                'tolak' => 'DITOLAK',
                default => $request->action,
            };
            $request->merge(['status' => $mappedStatus]);
        }

        $request->validate([
            'status' => 'required|in:LUNAS,DITOLAK',
            'rejection_reason' => 'required_if:status,DITOLAK|string|nullable',
        ]);

        DB::transaction(function () use ($request, $payment): void {
            $lockedPayment = Payment::with(['due', 'user'])
                ->lockForUpdate()
                ->findOrFail($payment->id);

            if ($lockedPayment->status !== 'MENUNGGU_VERIFIKASI') {
                throw ValidationException::withMessages([
                    'status' => 'Pembayaran ini sudah diproses dan tidak dapat diverifikasi ulang.',
                ]);
            }

            $lockedPayment->update([
                'status' => $request->status,
                'verified_by_user_id' => Auth::id(),
                'verified_at' => now(),
                'rejection_reason' => $request->status === 'DITOLAK' ? $request->rejection_reason : null,
            ]);

            if ($request->status === 'LUNAS') {
                // Ensure no duplicate transaction exists for this payment
                $exists = CashTransaction::where('payment_id', $lockedPayment->id)->exists();
                if (! $exists) {
                    CashTransaction::create([
                        'type' => 'INCOME',
                        'amount' => $lockedPayment->amount_paid,
                        'payment_id' => $lockedPayment->id,
                        'description' => 'Pembayaran '.$lockedPayment->due->title.' oleh '.$lockedPayment->user->name,
                        'transaction_date' => now(),
                        'created_by' => Auth::id(),
                    ]);
                }
            }
        });

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
