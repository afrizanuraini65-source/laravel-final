<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\CashTransaction;
use App\Models\Division;
use App\Models\Due;
use App\Models\Event;
use App\Models\Member;
use App\Models\OrganizationPeriod;
use App\Models\P2ksSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name')->toArray();
        $primaryRole = count($roles) > 0 ? $roles[0] : 'USER';

        $isPengurus = count(array_intersect(['ADMIN', 'KETUA', 'WAKIL_KETUA', 'PEMBINA', 'PELATIH', 'SEKRETARIS', 'BENDAHARA'], $roles)) > 0;
        $isFinanceManager = count(array_intersect(['ADMIN', 'KETUA', 'WAKIL_KETUA', 'BENDAHARA', 'PEMBINA'], $roles)) > 0;

        $stats = [];

        // 1. Stats Organisasi untuk Pimpinan & Pengurus
        if ($isPengurus) {
            $stats['total_members'] = Member::count();
            $stats['total_divisions'] = Division::count();
            $stats['active_events'] = Event::where('status', 'OPEN')->count();
        }

        // 2. Stats Keuangan Kas & Tagihan untuk Pengurus Keuangan (Admin, Ketua, Bendahara, Pembina)
        if ($isFinanceManager) {
            $income = CashTransaction::verifiedIncomeTotal();
            $expense = CashTransaction::where('type', 'EXPENSE')->sum('amount');
            $stats['cash_balance'] = $income - $expense;
            $stats['total_verified_funds'] = $income;
            $stats['total_dues_count'] = Due::count();
            $activeMembers = Member::with('user')->where('status', 'AKTIF')->get();
            $allDues = Due::with('payments')->get();
            $totalTargetBills = 0;
            $totalTargetNominal = 0;
            $verifiedPaymentCount = 0;
            $pendingPaymentCount = 0;
            $verifiedPaymentAmount = 0;

            foreach ($allDues as $due) {
                $targetMembers = $activeMembers->filter(fn (Member $member) => $due->targetsMember($member));
                $targetUserIds = $targetMembers->pluck('user_id')->filter()->map(fn ($id) => (int) $id);
                $targetPayments = $due->payments->whereIn('user_id', $targetUserIds);
                $verifiedPayments = $targetPayments->where('status', 'LUNAS');
                $targetCount = $targetMembers->count();

                $totalTargetBills += $targetCount;
                $totalTargetNominal += ($targetCount * $due->amount);
                $verifiedPaymentCount += $verifiedPayments->count();
                $verifiedPaymentAmount += $verifiedPayments->sum('amount_paid');
                $pendingPaymentCount += $targetPayments->where('status', 'MENUNGGU_VERIFIKASI')->count();
            }

            $stats['total_target_bills'] = $totalTargetBills;
            $stats['total_target_nominal'] = $totalTargetNominal;
            $stats['verified_payments_count'] = $verifiedPaymentCount;
            $stats['pending_payments_count'] = $pendingPaymentCount;
            $stats['unpaid_bills_count'] = max(0, $totalTargetBills - $verifiedPaymentCount - $pendingPaymentCount);
            $stats['unpaid_dues_nominal'] = max(0, $totalTargetNominal - $verifiedPaymentAmount);
            $stats['recent_cash_transactions'] = CashTransaction::with(['creator', 'payment.user'])
                ->where(function ($query) {
                    $query->whereNull('payment_id')
                        ->orWhereHas('payment', fn ($paymentQuery) => $paymentQuery->where('status', 'LUNAS'));
                })
                ->latest('transaction_date')
                ->latest('id')
                ->take(5)
                ->get();
        }

        // 3. Stats & Riwayat Personal Anggota (Untuk semua anggota termasuk pengurus saat melihat tagihan pribadinya)
        $applicableDues = Due::all()->filter(fn (Due $due) => $due->appliesTo($user));

        $userPayments = $user->payments()->with('due')->get()->keyBy('due_id');

        $myTotalDuesCount = $applicableDues->count();
        $myPaidCount = 0;
        $myPendingCount = 0;
        $myUnpaidNominal = 0;

        $myActiveDuesList = [];
        foreach ($applicableDues as $due) {
            $payment = $userPayments->get($due->id);
            $status = $payment ? $payment->status : 'BELUM BAYAR';

            if ($status === 'LUNAS') {
                $myPaidCount++;
            } elseif ($status === 'MENUNGGU_VERIFIKASI') {
                $myPendingCount++;
            } else {
                $myUnpaidNominal += $due->amount;
            }

            $myActiveDuesList[] = [
                'due' => $due,
                'status' => $status,
                'payment' => $payment,
            ];
        }

        $stats['my_total_dues'] = $myTotalDuesCount;
        $stats['my_paid_dues'] = $myPaidCount;
        $stats['my_pending_dues'] = $myPendingCount;
        $stats['my_unpaid_dues'] = max(0, $myTotalDuesCount - $myPaidCount - $myPendingCount);
        $stats['my_unpaid_nominal'] = $myUnpaidNominal;
        $stats['my_recent_payments'] = $user->payments()->with('due')->latest()->take(5)->get();

        // Stats Event & Kehadiran Personal
        $stats['my_events'] = 0;
        if (Schema::hasTable('event_registrations')) {
            $stats['my_events'] = \DB::table('event_registrations')->where('user_id', $user->id)->count();
        }

        $stats['attendance_rate'] = '0%';
        if (Schema::hasTable('attendances')) {
            $totalAtt = \DB::table('attendances')->where('user_id', $user->id)->count();
            if ($totalAtt > 0) {
                $hadir = \DB::table('attendances')
                    ->where('user_id', $user->id)
                    ->where(function ($query) {
                        $query->whereIn('status', ['Hadir', 'hadir', 'HADIR', 'PRESENT', 'present'])
                            ->orWhereRaw('LOWER(status) = ?', ['hadir'])
                            ->orWhereRaw('LOWER(status) = ?', ['present']);
                    })
                    ->count();
                $stats['attendance_rate'] = round(($hadir / $totalAtt) * 100).'%';
            }
        }

        // Jadwal P2KS Aktif yang Belum Dikonfirmasi Selesai
        $todayP2ks = P2ksSchedule::where('user_id', $user->id)
            ->whereDate('scheduled_date', '<=', Carbon::today())
            ->where('status', 'menunggu')
            ->orderBy('scheduled_date', 'desc')
            ->first();

        // Common data for everyone
        $upcomingEvents = Event::where('status', 'OPEN')->orderBy('date', 'asc')->take(5)->get();

        // Event Forum Rutin / Presensi Hari Ini
        $todayForumEvent = Event::whereDate('date', Carbon::today('Asia/Jakarta')->toDateString())
            ->where(function ($q) {
                $q->where('title', 'like', '%Forum%')
                    ->orWhere('status', 'Berlangsung')
                    ->orWhere('status', 'OPEN');
            })
            ->first()
            ?? Event::where('title', 'like', '%Forum%')->first()
            ?? Event::where('status', 'OPEN')->first();

        $todayAttendance = null;
        if ($todayForumEvent) {
            $todayAttendance = Attendance::where('user_id', $user->id)
                ->where('event_id', $todayForumEvent->id)
                ->first();
        }

        if (! $todayAttendance) {
            $todayAttendance = Attendance::where('user_id', $user->id)
                ->whereDate('created_at', Carbon::today('Asia/Jakarta'))
                ->latest('id')
                ->first();
        }

        return view('dashboard', [
            'roles' => $roles,
            'primaryRole' => $primaryRole,
            'isPengurus' => $isPengurus,
            'isFinanceManager' => $isFinanceManager,
            'stats' => $stats,
            'upcomingEvents' => $upcomingEvents,
            'todayP2ks' => $todayP2ks,
            'myActiveDuesList' => $myActiveDuesList,
            'todayForumEvent' => $todayForumEvent,
            'todayAttendance' => $todayAttendance,
        ]);
    }

    public function storePresensi(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'status' => 'required|string',
        ]);

        $status = match (strtolower($request->status)) {
            'hadir' => 'Hadir',
            'sakit' => 'Sakit',
            'izin' => 'Izin',
            default => 'Hadir',
        };

        // Find today's event for presensi
        $event = null;
        if ($request->filled('event_id')) {
            $event = Event::find($request->event_id);
        }

        if (! $event) {
            $today = Carbon::today('Asia/Jakarta')->toDateString();
            $event = Event::whereDate('date', $today)
                ->where(function ($q) {
                    $q->where('title', 'like', '%Forum%')
                        ->orWhere('status', 'Berlangsung')
                        ->orWhere('status', 'OPEN');
                })
                ->first()
                ?? Event::where('title', 'like', '%Forum%')->first()
                ?? Event::where('status', 'OPEN')->first();
        }

        if (! $event) {
            $period = OrganizationPeriod::where('is_active', true)->first() ?? OrganizationPeriod::first();
            $event = Event::create([
                'organization_period_id' => $period ? $period->id : null,
                'title' => 'Forum Rutin',
                'date' => Carbon::today('Asia/Jakarta')->toDateString(),
                'status' => 'OPEN',
            ]);
        }

        $attendance = Attendance::updateOrCreate(
            [
                'event_id' => $event->id,
                'user_id' => $user->id,
            ],
            [
                'status' => $status,
                'recorded_by' => $user->id,
                'timestamp' => Carbon::now('Asia/Jakarta'),
            ]
        );

        $totalAtt = Attendance::where('user_id', $user->id)->count();
        $hadir = Attendance::where('user_id', $user->id)
            ->where(function ($query) {
                $query->whereIn('status', ['Hadir', 'hadir', 'HADIR', 'PRESENT', 'present'])
                    ->orWhereRaw('LOWER(status) = ?', ['hadir'])
                    ->orWhereRaw('LOWER(status) = ?', ['present']);
            })
            ->count();
        $rate = $totalAtt > 0 ? round(($hadir / $totalAtt) * 100).'%' : '0%';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $status,
                'attendance_rate' => $rate,
                'submitted_at' => Carbon::parse($attendance->timestamp)->format('H:i'),
                'message' => 'Data presensi berhasil dicatat.',
            ]);
        }

        return redirect()->back()->with('success', 'Data presensi berhasil dicatat.');
    }
}
