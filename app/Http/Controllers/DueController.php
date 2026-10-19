<?php

namespace App\Http\Controllers;

use App\Models\CashTransaction;
use App\Models\Division;
use App\Models\Due;
use App\Models\Member;
use App\Models\OrganizationPeriod;
use App\Models\Payment;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DueController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isFinanceManager = $user->hasRole(['ADMIN', 'KETUA', 'WAKIL_KETUA', 'BENDAHARA', 'PEMBINA']);

        // 1. Tagihan untuk user yang sedang login (Personal View)
        // Dues yang relevan untuk user ini
        $allDues = Due::with(['period', 'targetDivision', 'payments'])->latest()->get();

        $myApplicableDues = $allDues->filter(fn (Due $due) => $due->appliesTo($user));

        $myPayments = Payment::where('user_id', $user->id)->get()->keyBy('due_id');

        $formattedMyDues = $myApplicableDues->map(function ($due) use ($myPayments) {
            $payment = $myPayments->get($due->id);

            return [
                'id' => $due->id,
                'title' => $due->title,
                'description' => $due->description,
                'amount' => $due->amount,
                'type' => $due->type,
                'target_type' => $due->target_type,
                'target_division' => $due->targetDivision?->name,
                'weekly_period' => $due->weekly_period,
                'deadline' => $due->deadline,
                'status' => $payment ? $payment->status : 'BELUM BAYAR',
                'payment_id' => $payment ? $payment->id : null,
                'payment_method' => $payment ? $payment->payment_method : null,
                'proof_file' => $payment ? $payment->proof_file : null,
                'rejection_reason' => $payment ? $payment->rejection_reason : null,
                'verified_at' => $payment ? $payment->verified_at : null,
                'created_at' => $due->created_at,
            ];
        });

        // 2. Data khusus Pengurus Keuangan (Admin, Ketua, Bendahara, Pembina)
        $financeSummary = [];
        $memberPayments = collect();
        $divisions = [];
        $recentTransactions = collect();

        if ($isFinanceManager) {
            // Ringkasan Kas & Tagihan Organisasi
            $totalDuesCreated = $allDues->count();
            $incomeSum = CashTransaction::verifiedIncomeTotal();
            $expenseSum = CashTransaction::where('type', 'EXPENSE')->sum('amount');
            $cashBalance = $incomeSum - $expenseSum;
            $activeMembers = Member::with('user')->where('status', 'AKTIF')->get();
            $totalPotentialBills = 0;
            $verifiedPaidCount = 0;
            $pendingCount = 0;
            $rejectedCount = 0;
            $totalPotentialAmount = 0;
            $verifiedPaymentAmount = 0;

            foreach ($allDues as $d) {
                $targetMembers = $activeMembers->filter(fn (Member $member) => $d->targetsMember($member));
                $targetUserIds = $targetMembers->pluck('user_id')->filter()->map(fn ($id) => (int) $id);
                $targetPayments = $d->payments->whereIn('user_id', $targetUserIds);

                $totalPotentialBills += $targetMembers->count();
                $totalPotentialAmount += $targetMembers->count() * (float) $d->amount;
                $verifiedPayments = $targetPayments->where('status', 'LUNAS');
                $verifiedPaidCount += $verifiedPayments->count();
                $verifiedPaymentAmount += $verifiedPayments->sum('amount_paid');
                $pendingCount += $targetPayments->where('status', 'MENUNGGU_VERIFIKASI')->count();
                $rejectedCount += $targetPayments->where('status', 'DITOLAK')->count();
            }
            $unpaidCount = max(0, $totalPotentialBills - $verifiedPaidCount - $pendingCount);

            $financeSummary = [
                'total_dues' => $totalDuesCreated,
                'cash_balance' => $cashBalance,
                'income_sum' => $incomeSum,
                'expense_sum' => $expenseSum,
                'paid_count' => $verifiedPaidCount,
                'pending_count' => $pendingCount,
                'rejected_count' => $rejectedCount,
                'unpaid_count' => $unpaidCount,
                'total_potential_bills' => $totalPotentialBills,
                'unpaid_amount' => max(0, $totalPotentialAmount - $verifiedPaymentAmount),
            ];

            // Tabel Data Tagihan & Pembayaran Anggota (dengan Filter & Search)
            $query = Payment::with(['due.targetDivision', 'user.member.division', 'verifier'])
                ->select('payments.*');

            // Filter status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter jenis tagihan / due_id
            if ($request->filled('due_id')) {
                $query->where('due_id', $request->due_id);
            }

            // Search nama anggota / kelas
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('kelas', 'like', "%{$search}%")
                        ->orWhereHas('member', function ($mq) use ($search) {
                            $mq->where('name', 'like', "%{$search}%")
                                ->orWhere('kelas', 'like', "%{$search}%");
                        });
                });
            }

            // Filter kelas
            if ($request->filled('kelas')) {
                $kelas = $request->kelas;
                $query->whereHas('user', function ($q) use ($kelas) {
                    $q->where('kelas', $kelas)
                        ->orWhereHas('member', function ($mq) use ($kelas) {
                            $mq->where('kelas', $kelas);
                        });
                });
            }

            $memberPayments = $query->latest('updated_at')->paginate(15)->withQueryString();

            $divisions = Division::all();

            $recentTransactions = CashTransaction::with(['creator', 'payment.user'])
                ->where(function ($query) {
                    $query->whereNull('payment_id')
                        ->orWhereHas('payment', fn ($paymentQuery) => $paymentQuery->where('status', 'LUNAS'));
                })
                ->latest('transaction_date')
                ->latest('id')
                ->take(10)
                ->get();
        }

        // List kelas unik untuk dropdown filter
        $classList = Member::whereNotNull('kelas')->distinct()->pluck('kelas')->sort()->values();

        return view('finance.index', [
            'dues' => $formattedMyDues,
            'allDues' => $allDues,
            'rawDues' => $allDues,
            'canManageFinance' => $isFinanceManager,
            'financeSummary' => $financeSummary,
            'memberPayments' => $memberPayments,
            'divisions' => $divisions,
            'recentTransactions' => $recentTransactions,
            'classList' => $classList,
            'paymentSettings' => PaymentSetting::current(),
            'qrisAvailable' => is_file(public_path('images/qris_pikr.jpeg')),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000',
            'type' => 'required|in:BULANAN,KEGIATAN,KHUSUS,MINGGUAN',
            'deadline' => 'nullable|date',
            'description' => 'nullable|string',
            'target_type' => 'nullable|in:SEMUA,DIVISI,INTI',
            'target_division_id' => 'nullable|exists:divisions,id',
        ]);

        $period = OrganizationPeriod::where('is_active', true)->first()
            ?? OrganizationPeriod::first();

        Due::create([
            'organization_period_id' => $period?->id,
            'title' => $request->title,
            'amount' => $request->amount,
            'type' => $request->type,
            'deadline' => $request->deadline,
            'description' => $request->description,
            'target_type' => $request->target_type ?? 'SEMUA',
            'target_division_id' => $request->target_type === 'DIVISI' ? $request->target_division_id : null,
        ]);

        return redirect()->route('kas.index')->with('success', 'Tagihan Kas baru berhasil dibuat dan diterbitkan.');
    }

    public function update(Request $request, Due $due)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000',
            'type' => 'required|in:BULANAN,KEGIATAN,KHUSUS,MINGGUAN',
            'deadline' => 'nullable|date',
            'description' => 'nullable|string',
            'target_type' => 'nullable|in:SEMUA,DIVISI,INTI',
            'target_division_id' => 'nullable|exists:divisions,id',
        ]);

        $due->update([
            'title' => $request->title,
            'amount' => $request->amount,
            'type' => $request->type,
            'deadline' => $request->deadline,
            'description' => $request->description,
            'target_type' => $request->target_type ?? 'SEMUA',
            'target_division_id' => $request->target_type === 'DIVISI' ? $request->target_division_id : null,
        ]);

        return redirect()->route('kas.index')->with('success', 'Data tagihan kas berhasil diperbarui.');
    }

    public function destroy(Due $due)
    {
        $hasPaid = $due->payments()->where('status', 'LUNAS')->exists();
        if ($hasPaid) {
            return redirect()->route('kas.index')->with('error', 'Tagihan tidak dapat dihapus karena sudah ada pembayaran lunas yang tercatat.');
        }

        $due->payments()->delete();
        $due->delete();

        return redirect()->route('kas.index')->with('success', 'Tagihan kas berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $payments = Payment::with(['due', 'user.member', 'verifier'])
            ->latest('created_at')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="rekap_kas_skakmatt_'.date('Ymd_His').'.csv"',
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Tanggal', 'Nama Anggota', 'Kelas', 'Nama Tagihan', 'Jenis Iuran', 'Nominal', 'Metode', 'Status', 'Diverifikasi Oleh', 'Tgl Verifikasi']);

            $no = 1;
            foreach ($payments as $p) {
                fputcsv($file, [
                    $no++,
                    $p->created_at->format('d/m/Y H:i'),
                    $p->user->name ?? '-',
                    $p->user->kelas ?? ($p->user->member->kelas ?? '-'),
                    $p->due->title ?? '-',
                    $p->due->type ?? '-',
                    $p->amount_paid,
                    $p->payment_method,
                    $p->status,
                    $p->verifier->name ?? '-',
                    $p->verified_at ? $p->verified_at->format('d/m/Y H:i') : '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
