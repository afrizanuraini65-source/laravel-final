@extends('layouts.admin')

@section('title', 'Tagihan & Kas - PIK-R SKAKMATT')
@section('header', $canManageFinance ? 'Pengelolaan Tagihan & Kas' : 'Tagihan Kas Saya')

@section('content')
<div x-data="{ 
    showCreateModal: false,
    showEditModal: false,
    editDueData: { id: null, title: '', amount: 0, type: 'BULANAN', target_type: 'SEMUA', target_division_id: '', deadline: '', description: '' },
    payModal: false, 
    selectedDue: null,
    payData: {
        due_id: '',
        payment_method: @js($paymentSettings->bank_name && $paymentSettings->account_number ? 'TRANSFER' : ($qrisAvailable ? 'QRIS' : 'CASH')),
        notes: ''
    },
    copiedField: null,
    copyToClipboard(text, field) {
        if (!text) return;
        const str = String(text);
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(str);
        } else {
            const textArea = document.createElement('textarea');
            textArea.value = str;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
            } catch (err) {}
            textArea.remove();
        }
        this.copiedField = field;
        setTimeout(() => {
            if (this.copiedField === field) {
                this.copiedField = null;
            }
        }, 2000);
    },
    previewProofUrl: null,
    handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            this.previewProofUrl = URL.createObjectURL(file);
        } else {
            this.previewProofUrl = null;
        }
    },
    openPayModal(due) {
        this.selectedDue = due;
        this.payData.due_id = due.id;
        this.payData.payment_method = @js($paymentSettings->bank_name && $paymentSettings->account_number ? 'TRANSFER' : ($qrisAvailable ? 'QRIS' : 'CASH'));
        this.previewProofUrl = null;
        this.copiedField = null;
        this.payModal = true;
        this.$nextTick(() => {
            const outer = document.getElementById('payment-modal-outer');
            if (outer) outer.scrollTop = 0;
            const box = document.getElementById('payment-modal-box');
            if (box) box.scrollTop = 0;
        });
    },
    openEditModal(due) {
        this.editDueData = {
            id: due.id,
            title: due.title,
            amount: due.amount,
            type: due.type,
            target_type: due.target_type || 'SEMUA',
            target_division_id: due.target_division_id || '',
            deadline: due.deadline ? due.deadline.substring(0, 10) : '',
            description: due.description || ''
        };
        this.showEditModal = true;
    },
    // Review Proof Modal for Pengurus
    reviewModal: false,
    reviewPayment: null,
    rejectModal: false,
    rejectPaymentId: null,
    rejectReason: '',
    openReviewModal(payment) {
        this.reviewPayment = payment;
        this.reviewModal = true;
    },
    openRejectModal(paymentId) {
        this.rejectPaymentId = paymentId;
        this.rejectReason = '';
        this.rejectModal = true;
        this.reviewModal = false;
    },
    activeTab: '{{ $canManageFinance ? (request()->hasAny(['search', 'kelas', 'due_id', 'status', 'page']) ? 'anggota' : 'anggota') : 'saya' }}'
}" class="space-y-8">

    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 bg-[#121B2D]/90 backdrop-blur-xl rounded-3xl p-6 md:p-8 border border-emerald-500/20 shadow-[0_10px_40px_rgba(0,0,0,0.5)] relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-80 h-80 bg-emerald-600/10 rounded-full blur-[90px] pointer-events-none group-hover:scale-110 transition-transform duration-700"></div>
        <div class="absolute bottom-0 left-1/3 w-64 h-64 bg-cyan-600/10 rounded-full blur-[80px] pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                    Sistem Keuangan PIK-R
                </span>
                @if($canManageFinance)
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-cyan-500/10 text-cyan-300 border border-cyan-500/30">
                        Akses Pengurus
                    </span>
                @endif
            </div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight drop-shadow-[0_0_8px_rgba(255,255,255,0.2)]">
                Pusat Pengelolaan <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400">Tagihan & Kas Organisasi</span>
            </h2>
            <p class="text-slate-400 text-sm mt-1 max-w-2xl">
                Transparansi pencatatan iuran kas, verifikasi bukti pembayaran QRIS, dan rekapitulasi keuangan resmi PIK-R SKAKMATT SMKN 1 Purwokerto.
            </p>
        </div>

        <div class="relative z-10 flex flex-wrap items-center gap-3 w-full lg:w-auto">
            @if($canManageFinance)
                <a 
                    href="{{ route('kas.export') }}"
                    class="px-4 py-3 rounded-2xl bg-[#0B1221] hover:bg-slate-800 text-emerald-400 font-bold text-xs md:text-sm border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)] hover:shadow-[0_0_20px_rgba(16,185,129,0.3)] transition-all duration-300 flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export Rekap Kas (CSV)
                </a>
                <button 
                    @click="showCreateModal = true"
                    class="px-5 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-bold text-xs md:text-sm shadow-[0_0_20px_rgba(16,185,129,0.4)] hover:shadow-[0_0_30px_rgba(16,185,129,0.7)] hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Tagihan Baru
                </button>
            @endif
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 rounded-2xl font-medium shadow-[0_0_20px_rgba(16,185,129,0.15)] flex items-center gap-3 animate-fade-in">
            <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-500/10 border border-red-500/30 text-red-300 rounded-2xl font-medium shadow-[0_0_20px_rgba(239,68,68,0.15)] flex items-center gap-3 animate-fade-in">
            <div class="w-8 h-8 rounded-full bg-red-500/20 text-red-400 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($canManageFinance)
        <!-- ======================================================== -->
        <!-- 1. RINGKASAN METRIK KEUANGAN & TAGIHAN (PENGURUS)       -->
        <!-- ======================================================== -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <!-- Saldo Kas Aktif -->
            <div class="col-span-2 bg-[#121B2D]/80 backdrop-blur-md border border-emerald-500/30 rounded-2xl p-5 shadow-lg relative overflow-hidden group hover:border-emerald-400 transition-all">
                <div class="absolute -top-6 -right-6 w-20 h-20 bg-emerald-500/10 rounded-full blur-xl"></div>
                <div class="flex items-center justify-between mb-2">
                    <p class="text-[11px] text-emerald-400 font-bold uppercase tracking-wider">Saldo Kas Aktif</p>
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                </div>
                <p class="text-2xl md:text-3xl font-black text-white">
                    Rp {{ number_format($financeSummary['cash_balance'] ?? 0, 0, ',', '.') }}
                </p>
                <p class="text-[10px] text-slate-400 mt-1">
                    Masuk: Rp {{ number_format($financeSummary['income_sum'] ?? 0, 0, ',', '.') }} | Keluar: Rp {{ number_format($financeSummary['expense_sum'] ?? 0, 0, ',', '.') }}
                </p>
            </div>

            <!-- Total Tagihan Dibuat -->
            <div class="bg-[#121B2D]/80 backdrop-blur-md border border-cyan-500/20 rounded-2xl p-5 shadow-lg hover:border-cyan-400 transition-all">
                <p class="text-[10px] text-cyan-400 font-bold uppercase tracking-wider mb-2">Tagihan Dibuat</p>
                <p class="text-2xl md:text-3xl font-black text-white">{{ $financeSummary['total_dues'] ?? 0 }}</p>
                <p class="text-[10px] text-slate-400 mt-1">Jenis iuran diterbitkan</p>
            </div>

            <!-- Tagihan Lunas Terverifikasi -->
            <div class="bg-[#121B2D]/80 backdrop-blur-md border border-emerald-500/20 rounded-2xl p-5 shadow-lg hover:border-emerald-400 transition-all">
                <p class="text-[10px] text-emerald-400 font-bold uppercase tracking-wider mb-2">Tagihan Lunas</p>
                <p class="text-2xl md:text-3xl font-black text-emerald-400">{{ $financeSummary['paid_count'] ?? 0 }}</p>
                <p class="text-[10px] text-slate-400 mt-1">Sudah terkonfirmasi</p>
            </div>

            <!-- Tagihan Belum Bayar -->
            <div class="bg-[#121B2D]/80 backdrop-blur-md border border-slate-700/60 rounded-2xl p-5 shadow-lg hover:border-slate-500 transition-all">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2">Belum Lunas</p>
                <p class="text-2xl md:text-3xl font-black text-slate-300">{{ $financeSummary['unpaid_count'] ?? 0 }}</p>
                <p class="text-[10px] text-slate-400 mt-1">Dari target anggota</p>
            </div>

            <!-- Menunggu Verifikasi -->
            <div class="bg-[#121B2D]/80 backdrop-blur-md border border-amber-500/30 rounded-2xl p-5 shadow-lg hover:border-amber-400 transition-all relative">
                @if(($financeSummary['pending_count'] ?? 0) > 0)
                    <span class="absolute top-3 right-3 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                    </span>
                @endif
                <p class="text-[10px] text-amber-400 font-bold uppercase tracking-wider mb-2">Antrean Verifikasi</p>
                <p class="text-2xl md:text-3xl font-black text-amber-400">{{ $financeSummary['pending_count'] ?? 0 }}</p>
                <p class="text-[10px] text-slate-400 mt-1">Menunggu ditinjau</p>
            </div>
        </div>

        @php
            $myUnpaidDuesList = collect($dues ?? [])->whereIn('status', ['BELUM BAYAR', 'DITOLAK']);
        @endphp
        @if($myUnpaidDuesList->count() > 0)
            <div class="bg-gradient-to-r from-emerald-950/60 via-teal-950/40 to-[#0B1221] border border-emerald-500/40 rounded-2xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-lg">
                <div class="flex items-center gap-3">
                    <span class="p-2.5 bg-emerald-500/20 text-emerald-400 rounded-xl shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </span>
                    <div>
                        <h4 class="text-sm font-bold text-white">Anda memiliki {{ $myUnpaidDuesList->count() }} Tagihan Kas Pribadi yang Belum Dibayar</h4>
                        <p class="text-xs text-slate-300">Total: <strong class="text-emerald-400">Rp {{ number_format($myUnpaidDuesList->sum('amount'), 0, ',', '.') }}</strong>. Pilih metode pembayaran yang tersedia untuk menyelesaikan iuran kas Anda.</p>
                    </div>
                </div>
                <button 
                    type="button" 
                    @click="activeTab = 'saya'" 
                    class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-bold text-xs rounded-xl shadow-[0_0_15px_rgba(16,185,129,0.3)] transition-all shrink-0 flex items-center gap-1.5"
                >
                    Bayar Sekarang
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        @endif

        <!-- Navigation Tabs Pengurus -->
        <div class="flex flex-wrap items-center gap-2 border-b border-cyan-500/20 pb-4">
            <button 
                @click="activeTab = 'anggota'"
                :class="activeTab === 'anggota' ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/20 text-emerald-300 border-emerald-500/40 shadow-[0_0_15px_rgba(16,185,129,0.2)] font-bold' : 'text-slate-400 hover:text-white border-transparent hover:bg-slate-800/40'"
                class="px-5 py-2.5 rounded-2xl border text-xs md:text-sm font-semibold transition-all duration-300 flex items-center gap-2"
            >
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                Tabel Pembayaran Anggota
                @if(($financeSummary['pending_count'] ?? 0) > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-500 text-black">
                        {{ $financeSummary['pending_count'] }}
                    </span>
                @endif
            </button>

            <button 
                @click="activeTab = 'master'"
                :class="activeTab === 'master' ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/20 text-emerald-300 border-emerald-500/40 shadow-[0_0_15px_rgba(16,185,129,0.2)] font-bold' : 'text-slate-400 hover:text-white border-transparent hover:bg-slate-800/40'"
                class="px-5 py-2.5 rounded-2xl border text-xs md:text-sm font-semibold transition-all duration-300 flex items-center gap-2"
            >
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Kelola Tagihan (Master Due)
            </button>

            <button 
                @click="activeTab = 'kas'"
                :class="activeTab === 'kas' ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/20 text-emerald-300 border-emerald-500/40 shadow-[0_0_15px_rgba(16,185,129,0.2)] font-bold' : 'text-slate-400 hover:text-white border-transparent hover:bg-slate-800/40'"
                class="px-5 py-2.5 rounded-2xl border text-xs md:text-sm font-semibold transition-all duration-300 flex items-center gap-2"
            >
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Riwayat Transaksi Kas
            </button>

            <button 
                @click="activeTab = 'saya'"
                :class="activeTab === 'saya' ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/20 text-emerald-300 border-emerald-500/40 shadow-[0_0_15px_rgba(16,185,129,0.2)] font-bold' : 'text-slate-400 hover:text-white border-transparent hover:bg-slate-800/40'"
                class="px-5 py-2.5 rounded-2xl border text-xs md:text-sm font-semibold transition-all duration-300 flex items-center gap-2 ml-auto"
            >
                <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Tagihan Pribadi Saya
                @if(isset($myUnpaidDuesList) && $myUnpaidDuesList->count() > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-500 text-slate-900 animate-pulse">
                        {{ $myUnpaidDuesList->count() }}
                    </span>
                @endif
            </button>
        </div>

        <!-- ======================================================== -->
        <!-- TAB 1: TABEL TAGIHAN & PEMBAYARAN ANGGOTA (PENGURUS)    -->
        <!-- ======================================================== -->
        <div x-show="activeTab === 'anggota'" x-transition class="space-y-6">
            <!-- Filter & Search Toolbar -->
            <form method="GET" action="{{ route('kas.index') }}" class="bg-[#121B2D]/80 border border-slate-700/60 rounded-3xl p-5 shadow-lg">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <!-- Search nama / kelas -->
                    <div class="lg:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Cari Anggota / Kelas</label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}" 
                                placeholder="Ketik nama anggota atau kelas..." 
                                class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all pl-9"
                            />
                            <svg class="w-4 h-4 text-slate-500 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>

                    <!-- Filter Kelas -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Filter Kelas</label>
                        <select name="kelas" class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-3 py-2.5 text-xs text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <option value="">Semua Kelas</option>
                            @foreach($classList as $cls)
                                <option value="{{ $cls }}" {{ request('kelas') == $cls ? 'selected' : '' }}>{{ $cls }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Jenis Tagihan -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jenis Tagihan</label>
                        <select name="due_id" class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-3 py-2.5 text-xs text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <option value="">Semua Tagihan</option>
                            @foreach($allDues as $d)
                                <option value="{{ $d->id }}" {{ request('due_id') == $d->id ? 'selected' : '' }}>{{ $d->title }} (Rp {{ number_format($d->amount, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Status Pembayaran -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status</label>
                        <select name="status" class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-3 py-2.5 text-xs text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <option value="">Semua Status</option>
                            <option value="MENUNGGU_VERIFIKASI" {{ request('status') == 'MENUNGGU_VERIFIKASI' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="LUNAS" {{ request('status') == 'LUNAS' ? 'selected' : '' }}>Lunas</option>
                            <option value="DITOLAK" {{ request('status') == 'DITOLAK' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-slate-800">
                    <a href="{{ route('kas.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-semibold transition-all">
                        Reset Filter
                    </a>
                    <button type="submit" class="px-5 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl text-xs font-bold shadow-[0_0_15px_rgba(16,185,129,0.3)] transition-all">
                        Terapkan Filter
                    </button>
                </div>
            </form>

            <!-- Tabel Data Pembayaran -->
            <div class="bg-[#121B2D]/85 border border-slate-700/70 rounded-3xl overflow-hidden shadow-[0_10px_40px_rgba(0,0,0,0.5)]">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-300">
                        <thead class="bg-[#0B1221] text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-700">
                            <tr>
                                <th class="py-4 px-4 text-center w-12">No</th>
                                <th class="py-4 px-4">Nama Anggota</th>
                                <th class="py-4 px-4">Kelas & Divisi</th>
                                <th class="py-4 px-4">Nama Tagihan</th>
                                <th class="py-4 px-4 text-right">Nominal</th>
                                <th class="py-4 px-4">Tanggal Pembayaran</th>
                                <th class="py-4 px-4">Metode</th>
                                <th class="py-4 px-4 text-center">Status</th>
                                <th class="py-4 px-4 text-center">Aksi Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80">
                            @forelse($memberPayments as $index => $pay)
                                <tr class="hover:bg-slate-800/30 transition-colors">
                                    <td class="py-3.5 px-4 text-center text-slate-500 font-mono">
                                        {{ $memberPayments->firstItem() + $index }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-white text-sm">
                                            {{ $pay->user?->member?->name ?? $pay->user?->name ?? 'Anggota #' . $pay->user_id }}
                                        </div>
                                        <div class="text-[11px] text-slate-400">
                                            {{ $pay->user?->email }}
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2 py-0.5 rounded-md bg-slate-800 border border-slate-700 text-slate-300 font-semibold text-[11px]">
                                            {{ $pay->user?->member?->kelas ?? $pay->user?->kelas ?? '-' }}
                                        </span>
                                        @if($pay->user?->member?->division)
                                            <div class="text-[10px] text-teal-400 mt-1">
                                                {{ $pay->user->member->division->name }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-semibold text-white">
                                            {{ $pay->due?->title ?? '-' }}
                                        </div>
                                        <div class="text-[10px] text-cyan-400">
                                            {{ $pay->due?->type ?? 'KAS' }}
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <span class="font-black text-emerald-400 text-sm">
                                            Rp {{ number_format($pay->amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-300 text-[11px]">
                                        {{ \Carbon\Carbon::parse($pay->payment_date)->translatedFormat('d M Y, H:i') }}
                                        @if($pay->verified_at)
                                            <div class="text-[10px] text-slate-500">
                                                Diverifikasi: {{ \Carbon\Carbon::parse($pay->verified_at)->format('d/m/Y') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase {{ $pay->payment_method === 'QRIS' ? 'bg-cyan-950/60 text-cyan-300 border border-cyan-500/30' : ($pay->payment_method === 'TRANSFER' ? 'bg-indigo-950/60 text-indigo-300 border border-indigo-500/30' : 'bg-slate-800 text-slate-300') }}">
                                            {{ $pay->payment_method ?? 'QRIS' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        @if($pay->status === 'LUNAS')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-950/60 border border-emerald-500/50 text-emerald-400 font-bold text-[10px] rounded-full shadow-[0_0_10px_rgba(16,185,129,0.2)]">
                                                <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                LUNAS
                                            </span>
                                        @elseif($pay->status === 'MENUNGGU_VERIFIKASI')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-950/60 border border-amber-500/50 text-amber-400 font-bold text-[10px] rounded-full shadow-[0_0_10px_rgba(245,158,11,0.2)]">
                                                <svg class="w-3 h-3 text-amber-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                MENUNGGU
                                            </span>
                                        @elseif($pay->status === 'DITOLAK')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-950/60 border border-red-500/50 text-red-400 font-bold text-[10px] rounded-full">
                                                <svg class="w-3 h-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                DITOLAK
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            @if($pay->proof_file)
                                                <button 
                                                    @click="openReviewModal({{ json_encode($pay) }})"
                                                    class="px-2.5 py-1.5 rounded-xl bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-300 border border-cyan-500/30 text-xs font-semibold transition-all flex items-center gap-1"
                                                    title="Lihat Bukti Transfer"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    Bukti
                                                </button>
                                            @endif

                                            @if($pay->status === 'MENUNGGU_VERIFIKASI')
                                                <!-- Form Setujui Cepat -->
                                                <form method="POST" action="{{ route('payments.verify', $pay->id) }}" onsubmit="return confirm('Konfirmasi bahwa dana pembayaran sebesar Rp {{ number_format($pay->amount, 0, ',', '.') }} telah benar-benar diterima di rekening kas?')">
                                                    @csrf
                                                    <input type="hidden" name="action" value="setujui" />
                                                    <button type="submit" class="px-2.5 py-1.5 rounded-xl bg-emerald-500/20 hover:bg-emerald-500 text-emerald-300 hover:text-white border border-emerald-500/40 text-xs font-bold transition-all shadow-[0_0_10px_rgba(16,185,129,0.2)]">
                                                        Setujui
                                                    </button>
                                                </form>

                                                <!-- Tombol Tolak -->
                                                <button 
                                                    @click="openRejectModal({{ $pay->id }})"
                                                    class="px-2.5 py-1.5 rounded-xl bg-red-500/20 hover:bg-red-500 text-red-300 hover:text-white border border-red-500/40 text-xs font-bold transition-all"
                                                >
                                                    Tolak
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-12 text-center text-slate-500">
                                        <svg class="w-12 h-12 text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <p class="font-medium">Tidak ada data tagihan atau pembayaran yang cocok dengan kriteria filter.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($memberPayments->hasPages())
                    <div class="p-4 border-t border-slate-800 bg-[#0B1221]">
                        {{ $memberPayments->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- TAB 2: KELOLA MASTER TAGIHAN (PENGURUS)                  -->
        <!-- ======================================================== -->
        <div x-show="activeTab === 'master'" x-transition class="space-y-6" style="display: none;">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-white">Daftar Tagihan Iuran Organisasi</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Kelola tagihan, target peserta (seluruh anggota, divisi, atau pengurus inti), dan tenggat waktu.</p>
                </div>
                <button 
                    @click="showCreateModal = true"
                    class="px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold text-xs rounded-xl shadow-lg transition-all flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Tagihan Baru
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($allDues as $due)
                    <div class="bg-[#121B2D]/85 border border-slate-700/80 hover:border-cyan-500/40 rounded-3xl p-6 shadow-lg flex flex-col justify-between transition-all group">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black tracking-wider uppercase bg-cyan-950/80 border border-cyan-500/40 text-cyan-300">
                                    {{ $due->type }}
                                </span>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-slate-800 border border-slate-700 text-slate-300">
                                    @if($due->target_type === 'DIVISI')
                                        🎯 Divisi: {{ $due->targetDivision?->name ?? 'Tertentu' }}
                                    @elseif($due->target_type === 'INTI')
                                        ⭐ Pengurus Inti
                                    @else
                                        👥 Semua Anggota
                                    @endif
                                </span>
                            </div>

                            <h4 class="text-lg font-bold text-white group-hover:text-cyan-300 transition-colors">
                                {{ $due->title }}
                            </h4>
                            <p class="text-slate-400 text-xs mt-1 line-clamp-2">
                                {{ $due->description ?: 'Tidak ada catatan deskripsi tambahan.' }}
                            </p>

                            <div class="my-4">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Nominal</span>
                                <p class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">
                                    Rp {{ number_format($due->amount, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="text-[11px] text-slate-400 space-y-1 pt-3 border-t border-slate-800">
                                <div class="flex items-center justify-between">
                                    <span>Tenggat Pembayaran:</span>
                                    <span class="font-semibold text-slate-300">
                                        {{ $due->deadline ? \Carbon\Carbon::parse($due->deadline)->translatedFormat('d M Y') : 'Tidak ditentukan' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Diterbitkan:</span>
                                    <span class="text-slate-400">
                                        {{ $due->created_at ? $due->created_at->format('d/m/Y') : '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-4 mt-4 border-t border-slate-800">
                            <button 
                                @click="openEditModal({{ json_encode($due) }})"
                                class="px-3 py-1.5 bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 rounded-xl text-xs font-semibold transition-all flex items-center gap-1"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit
                            </button>

                            <form method="POST" action="{{ route('kas.destroy', $due->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tagihan ini? Tindakan ini akan diblokir jika sudah ada pembayaran lunas.')">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit"
                                    class="px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/30 rounded-xl text-xs font-semibold transition-all flex items-center gap-1"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- TAB 3: RIWAYAT BUKU KAS & TRANSAKSI (PENGURUS)          -->
        <!-- ======================================================== -->
        <div x-show="activeTab === 'kas'" x-transition class="space-y-6" style="display: none;">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-white">Buku Rekapitulasi Kas & Transaksi</h3>
                    <p class="text-slate-400 text-xs mt-0.5">Catatan mutasi pemasukan dan pengeluaran dana kas organisasi PIK-R SKAKMATT.</p>
                </div>
                <div class="text-right">
                    <span class="text-xs text-slate-400 block">Total Saldo Terverifikasi</span>
                    <span class="text-xl font-black text-emerald-400">Rp {{ number_format($financeSummary['cash_balance'] ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="bg-[#121B2D]/85 border border-slate-700/70 rounded-3xl overflow-hidden shadow-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-300">
                        <thead class="bg-[#0B1221] text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-700">
                            <tr>
                                <th class="py-4 px-4 w-12 text-center">No</th>
                                <th class="py-4 px-4">Tanggal Transaksi</th>
                                <th class="py-4 px-4">Jenis Mutasi</th>
                                <th class="py-4 px-4">Keterangan / Sumber</th>
                                <th class="py-4 px-4">Dicatat Oleh</th>
                                <th class="py-4 px-4 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @forelse($recentTransactions as $idx => $t)
                                <tr class="hover:bg-slate-800/30 transition-colors">
                                    <td class="py-3 px-4 text-center text-slate-500 font-mono">{{ $idx + 1 }}</td>
                                    <td class="py-3 px-4 text-slate-300">
                                        {{ \Carbon\Carbon::parse($t->transaction_date)->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($t->type === 'INCOME')
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-950/80 text-emerald-300 border border-emerald-500/40">
                                                + PEMASUKAN
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-950/80 text-red-300 border border-red-500/40">
                                                - PENGELUARAN
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-semibold text-white">{{ $t->description }}</div>
                                        @if($t->payment && $t->payment->user)
                                            <div class="text-[11px] text-teal-400">Pembayar: {{ $t->payment->user->name }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-slate-400 text-[11px]">
                                        {{ $t->creator?->name ?? 'Bendahara' }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-black {{ $t->type === 'INCOME' ? 'text-emerald-400' : 'text-red-400' }} text-sm">
                                        {{ $t->type === 'INCOME' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-10 text-center text-slate-500">
                                        Belum ada riwayat transaksi kas yang tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- ======================================================== -->
    <!-- TAB 4 / BAGIAN ANGGOTA: TAGIHAN KAS SAYA                -->
    <!-- ======================================================== -->
    <div x-show="activeTab === 'saya'" x-transition class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-xl md:text-2xl font-bold text-white tracking-tight">Tagihan Kas Milik Saya</h3>
                <p class="text-slate-400 text-xs mt-0.5">Daftar iuran kas yang ditujukan untuk Anda. Lakukan pembayaran melalui QRIS, E-Wallet, atau Transfer Bank.</p>
            </div>
        </div>

        <!-- Grid Tagihan Personal Anggota -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($dues as $due)
                <div class="bg-[#121B2D]/85 backdrop-blur-xl border border-cyan-500/30 hover:border-cyan-400/60 rounded-3xl p-6 md:p-7 shadow-[0_10px_30px_rgba(0,0,0,0.5)] hover:shadow-[0_15px_35px_rgba(6,182,212,0.15)] transition-all duration-500 relative overflow-hidden group flex flex-col justify-between hover:translate-y-[-2px]">
                    
                    <div class="absolute -top-12 -right-12 w-36 h-36 bg-cyan-500/10 rounded-full blur-2xl pointer-events-none group-hover:scale-125 transition-transform duration-700"></div>
                    <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="relative z-10">
                        <!-- Badges Row -->
                        <div class="flex justify-between items-center mb-5">
                            <div class="px-3 py-1 bg-cyan-950/60 border border-cyan-500/40 text-cyan-300 text-[10px] font-extrabold tracking-wider uppercase rounded-full shadow-[0_0_10px_rgba(6,182,212,0.2)] flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                {{ $due['type'] }}
                            </div>

                            @if($due['status'] === 'LUNAS')
                                <span class="px-3 py-1 bg-emerald-950/60 border border-emerald-500/50 text-emerald-400 font-bold text-xs rounded-full flex items-center gap-1.5 shadow-[0_0_12px_rgba(16,185,129,0.3)]">
                                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    LUNAS
                                </span>
                            @elseif($due['status'] === 'MENUNGGU_VERIFIKASI')
                                <span class="px-3 py-1 bg-amber-950/60 border border-amber-500/50 text-amber-400 font-bold text-xs rounded-full flex items-center gap-1.5 shadow-[0_0_12px_rgba(245,158,11,0.3)]">
                                    <svg class="w-3.5 h-3.5 text-amber-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    MENUNGGU VERIFIKASI
                                </span>
                            @elseif($due['status'] === 'DITOLAK')
                                <span class="px-3 py-1 bg-red-950/60 border border-red-500/50 text-red-400 font-bold text-xs rounded-full flex items-center gap-1.5 shadow-[0_0_12px_rgba(239,68,68,0.3)]">
                                    <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    DITOLAK
                                </span>
                            @else
                                <span class="px-3 py-1 bg-slate-800/90 border border-slate-600/70 text-slate-300 font-bold text-xs rounded-full flex items-center gap-1.5 shadow-[0_0_8px_rgba(148,163,184,0.15)]">
                                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                    BELUM DIBAYAR
                                </span>
                            @endif
                        </div>

                        <!-- Title & Nominal -->
                        <h4 class="text-xl font-bold text-white group-hover:text-cyan-200 transition-colors mb-2 tracking-tight">
                            {{ $due['title'] }}
                        </h4>

                        @if(!empty($due['description']))
                            <p class="text-slate-400 text-xs mb-3 line-clamp-2">{{ $due['description'] }}</p>
                        @endif

                        @if($due['weekly_period'])
                            <p class="text-xs text-emerald-300 mb-3">Periode kas: Rabu, {{ \Carbon\Carbon::parse($due['weekly_period'])->translatedFormat('d F Y') }}</p>
                        @endif

                        <div class="mb-4">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Nominal Tagihan</span>
                            <p class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-300 drop-shadow-[0_0_15px_rgba(16,185,129,0.35)]">
                                Rp {{ number_format($due['amount'], 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="text-[11px] text-slate-400 mb-4 flex items-center justify-between">
                            <span>Tenggat Waktu:</span>
                            <span class="font-semibold text-slate-300">
                                {{ $due['deadline'] ? \Carbon\Carbon::parse($due['deadline'])->translatedFormat('d F Y') : 'Tidak ditentukan' }}
                            </span>
                        </div>
                    </div>

                    <div class="relative z-10 pt-3 border-t border-slate-700/50">
                        @if($due['status'] === 'DITOLAK')
                            <div class="bg-red-950/40 border border-red-500/40 text-red-300 p-3 rounded-2xl text-xs mb-4 flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <div>
                                    <span class="font-bold text-red-400">Alasan Penolakan:</span>
                                    <p class="mt-0.5 text-slate-300">{{ $due['rejection_reason'] ?? 'Bukti pembayaran tidak terbaca atau tidak valid.' }}</p>
                                </div>
                            </div>
                        @endif

                        @if($due['status'] === 'BELUM BAYAR' || $due['status'] === 'DITOLAK')
                            <button
                                @click="openPayModal({{ json_encode($due) }})"
                                class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white py-3.5 rounded-2xl font-bold text-sm shadow-[0_0_20px_rgba(16,185,129,0.4)] hover:shadow-[0_0_30px_rgba(16,185,129,0.7)] transition-all duration-300 flex items-center justify-center gap-2 group/btn hover:scale-[1.01] active:scale-[0.99]"
                            >
                                <svg class="w-4 h-4 text-white group-hover/btn:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                {{ $due['status'] === 'DITOLAK' ? 'Unggah Ulang Bukti' : 'Bayar Sekarang' }}
                            </button>
                        @elseif($due['status'] === 'LUNAS')
                            <div class="w-full bg-emerald-950/40 border border-emerald-500/30 text-emerald-400 py-3 rounded-2xl font-bold text-xs text-center flex items-center justify-center gap-2 shadow-[0_0_15px_rgba(16,185,129,0.15)]">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Sudah Lunas Terkonfirmasi
                            </div>
                        @else
                            <div class="w-full bg-amber-950/40 border border-amber-500/30 text-amber-300 py-3 rounded-2xl font-bold text-xs text-center flex items-center justify-center gap-2 shadow-[0_0_15px_rgba(245,158,11,0.15)]">
                                <svg class="w-4 h-4 text-amber-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Bukti Sedang Diverifikasi Pengurus
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-[#121B2D]/60 border border-dashed border-slate-700 rounded-3xl p-12 text-center">
                    <svg class="w-12 h-12 text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-slate-300 font-bold text-base">Tidak Ada Tagihan Aktif</p>
                    <p class="text-slate-400 text-xs mt-1">Saat ini Anda tidak memiliki tanggungan tagihan iuran kas.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL PEMBAYARAN MULTI-METODE (QRIS, BANK, E-WALLET, CASH) -->
    <!-- ======================================================== -->
    <div x-show="payModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div x-show="payModal" x-transition.opacity class="fixed inset-0 bg-black/85 backdrop-blur-md" @click="payModal = false"></div>
        
        <div x-show="payModal" x-transition class="bg-[#0F172A] border border-cyan-500/30 rounded-3xl w-full max-w-2xl p-6 md:p-8 relative z-10 shadow-[0_0_60px_rgba(6,182,212,0.2)] text-white max-h-[92vh] overflow-y-auto">
            
            <div class="flex items-center justify-between pb-4 border-b border-slate-700/60 mb-5">
                <div>
                    <h3 class="text-lg md:text-xl font-extrabold text-white flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-400 shadow-[0_0_10px_rgba(16,185,129,0.8)]"></span>
                        Pembayaran Tagihan Kas
                    </h3>
                    <p class="text-slate-400 text-xs mt-0.5">Pilih metode pembayaran (QRIS, E-Wallet, atau Transfer Bank), selesaikan transaksi, dan unggah bukti transfer yang sah.</p>
                </div>
                <button type="button" @click="payModal = false" class="text-slate-400 hover:text-white p-1 rounded-lg transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Detail Tagihan Box with Nominal Copy Button -->
            <div class="bg-gradient-to-r from-emerald-950/40 via-teal-950/30 to-[#0B1221] border border-emerald-500/40 rounded-2xl p-4 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-inner">
                <div>
                    <span class="text-[10px] uppercase tracking-wider text-emerald-400 font-bold block">Tagihan yang dipilih</span>
                    <h4 class="text-base font-extrabold text-white" x-text="selectedDue?.title"></h4>
                    <span class="text-xs text-slate-400" x-text="'Jenis: ' + (selectedDue?.type || 'Kas')"></span>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold block">Nominal yang harus dibayar</span>
                    <div class="flex items-center sm:justify-end gap-2 mt-0.5">
                        <p class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300" x-text="selectedDue ? 'Rp ' + new Intl.NumberFormat('id-ID').format(selectedDue.amount) : 'Rp 0'"></p>
                        <button 
                            type="button" 
                            @click="copyToClipboard(selectedDue?.amount, 'amount')"
                            class="px-2 py-1 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/40 rounded-lg text-[10px] font-bold transition-all flex items-center gap-1 active:scale-95"
                            title="Salin nominal tagihan"
                        >
                            <span x-text="copiedField === 'amount' ? 'Tersalin! ✓' : 'Salin'"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- PILIH SALURAN METODE PEMBAYARAN (Tabs Modern) -->
            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2.5">
                    Pilih Metode Pembayaran
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    @if($qrisAvailable)
                    <button 
                        type="button"
                        @click="payData.payment_method = 'QRIS'"
                        :class="payData.payment_method === 'QRIS' ? 'border-emerald-400 bg-emerald-950/40 shadow-[0_0_15px_rgba(16,185,129,0.25)] ring-1 ring-emerald-400' : 'border-slate-700/80 bg-[#0B1221] hover:border-slate-600'"
                        class="p-3 rounded-2xl border text-left transition-all relative flex flex-col justify-between"
                    >
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="p-1.5 rounded-lg bg-emerald-500/20 text-emerald-400 inline-block">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                </span>
                                <span x-show="payData.payment_method === 'QRIS'" class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_6px_rgba(16,185,129,1)]"></span>
                            </div>
                            <span class="font-bold text-xs text-white block">QRIS</span>
                            <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">Semua E-Wallet & Bank</span>
                        </div>
                    </button>
                    @endif

                    <!-- Tab E-Wallet -->
                    @if($paymentSettings->hasWalletAccount())
                    <button 
                        type="button"
                        @click="payData.payment_method = 'EWALLET'"
                        :class="payData.payment_method === 'EWALLET' ? 'border-cyan-400 bg-cyan-950/40 shadow-[0_0_15px_rgba(6,182,212,0.25)] ring-1 ring-cyan-400' : 'border-slate-700/80 bg-[#0B1221] hover:border-slate-600'"
                        class="p-3 rounded-2xl border text-left transition-all relative flex flex-col justify-between"
                    >
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="p-1.5 rounded-lg bg-cyan-500/20 text-cyan-400 inline-block">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </span>
                                <span x-show="payData.payment_method === 'EWALLET'" class="w-2 h-2 rounded-full bg-cyan-400 shadow-[0_0_6px_rgba(6,182,212,1)]"></span>
                            </div>
                            <span class="font-bold text-xs text-white block">E-Wallet</span>
                            <span class="text-[10px] text-cyan-300 font-mono leading-tight block mt-0.5">{{ $paymentSettings->wallet_provider }}</span>
                        </div>
                    </button>
                    @endif

                    <!-- Tab Transfer Bank -->
                    @if($paymentSettings->hasBankAccount())
                    <button 
                        type="button"
                        @click="payData.payment_method = 'TRANSFER'"
                        :class="payData.payment_method === 'TRANSFER' ? 'border-indigo-400 bg-indigo-950/40 shadow-[0_0_15px_rgba(99,102,241,0.25)] ring-1 ring-indigo-400' : 'border-slate-700/80 bg-[#0B1221] hover:border-slate-600'"
                        class="p-3 rounded-2xl border text-left transition-all relative flex flex-col justify-between"
                    >
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="p-1.5 rounded-lg bg-indigo-500/20 text-indigo-400 inline-block">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                                </span>
                                <span x-show="payData.payment_method === 'TRANSFER'" class="w-2 h-2 rounded-full bg-indigo-400 shadow-[0_0_6px_rgba(99,102,241,1)]"></span>
                            </div>
                            <span class="font-bold text-xs text-white block">Transfer Bank</span>
                            <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">{{ $paymentSettings->bank_name }}</span>
                        </div>
                    </button>
                    @endif

                    <!-- Tab Tunai -->
                    <button 
                        type="button"
                        @click="payData.payment_method = 'CASH'"
                        :class="payData.payment_method === 'CASH' ? 'border-amber-400 bg-amber-950/40 shadow-[0_0_15px_rgba(245,158,11,0.25)] ring-1 ring-amber-400' : 'border-slate-700/80 bg-[#0B1221] hover:border-slate-600'"
                        class="p-3 rounded-2xl border text-left transition-all relative flex flex-col justify-between"
                    >
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="p-1.5 rounded-lg bg-amber-500/20 text-amber-400 inline-block">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </span>
                                <span x-show="payData.payment_method === 'CASH'" class="w-2 h-2 rounded-full bg-amber-400 shadow-[0_0_6px_rgba(245,158,11,1)]"></span>
                            </div>
                            <span class="font-bold text-xs text-white block">Tunai (Cash)</span>
                            <span class="text-[10px] text-slate-400 leading-tight block mt-0.5">Titip ke Bendahara</span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- DETAIL & PETUNJUK SESUAI METODE YANG DIPILIH -->

            <!-- 1. DETAIL METODE: QRIS -->
            @if($qrisAvailable)
            <div x-show="payData.payment_method === 'QRIS'" x-transition class="space-y-4 mb-6">
                <div class="text-center p-4 bg-[#0B1221] border border-emerald-500/30 rounded-2xl">
                    <span class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider block mb-2">QRIS PIK-R SKAKMATT</span>
                    <div class="inline-block p-3.5 bg-white rounded-2xl shadow-[0_0_25px_rgba(255,255,255,0.15)] border-2 border-emerald-400/50">
                        <img 
                            src="{{ asset('images/qris_pikr.jpeg') }}" 
                            alt="QRIS PIK-R SKAKMATT SMK Negeri 1 Purwokerto" 
                            class="w-52 h-auto sm:w-60 max-w-full rounded-xl object-contain mx-auto transition-transform hover:scale-105 duration-300"
                        />
                    </div>
                    <div class="mt-2.5">
                        <a 
                            href="{{ asset('images/qris_pikr.jpeg') }}" 
                            target="_blank" 
                            download="qris_pikr_skakmatt.jpeg"
                            class="text-xs text-cyan-400 hover:text-cyan-300 underline font-semibold inline-flex items-center gap-1.5"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Buka Gambar Ukuran Penuh / Unduh QRIS
                        </a>
                    </div>
                </div>

                <div class="bg-[#0B1221] border border-slate-700/70 rounded-2xl p-4 text-xs text-slate-300 space-y-2">
                    <p class="font-bold text-emerald-400 text-xs flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Langkah Pembayaran via QRIS:
                    </p>
                    <ol class="list-decimal list-inside space-y-1.5 text-slate-400 leading-relaxed pl-1 text-[11px]">
                        <li>Buka aplikasi m-Banking atau E-Wallet apa pun (<strong class="text-white">BCA, Dana, GoPay, OVO, ShopeePay, Livin', LinkAja</strong>, dll).</li>
                        <li>Pilih menu <strong class="text-white">Scan / QRIS</strong> lalu arahkan kamera ke barcode QRIS di atas.</li>
                        <li>Pastikan nama merchant tertera <strong class="text-emerald-400">KAS PIK-R SKAKMATT</strong>.</li>
                        <li>Masukkan nominal tagihan sesuai tagihan: <span class="text-emerald-400 font-bold" x-text="selectedDue ? 'Rp ' + new Intl.NumberFormat('id-ID').format(selectedDue.amount) : ''"></span>.</li>
                        <li>Selesaikan transaksi dan <strong class="text-white">simpan tangkapan layar (screenshot) / resi</strong> bukti pembayaran.</li>
                    </ol>
                </div>
            </div>
            @endif

            <!-- 2. DETAIL METODE: E-WALLET -->
            @if($paymentSettings->hasWalletAccount())
            <div x-show="payData.payment_method === 'EWALLET'" x-transition class="space-y-4 mb-6">
                <!-- E-Wallet Primary Card -->
                <div class="bg-gradient-to-br from-[#0e2238] to-[#0b172a] border border-cyan-500/40 rounded-2xl p-5 shadow-[0_0_25px_rgba(6,182,212,0.15)] relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-cyan-500/10 rounded-full blur-xl pointer-events-none"></div>
                    
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-cyan-400 animate-pulse"></span>
                            <span class="text-[10px] font-bold text-cyan-400 uppercase tracking-wider">Nomor Resmi E-Wallet</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">{{ $paymentSettings->wallet_provider }}</span>
                        </div>
                    </div>

                    <div class="bg-[#070D18] border border-slate-700/80 rounded-xl p-3.5 flex items-center justify-between gap-3">
                        <div>
                            <span class="text-[10px] text-slate-400 block font-medium">{{ $paymentSettings->wallet_provider }}</span>
                            <span class="text-xl sm:text-2xl font-black text-cyan-300 font-mono tracking-wider">{{ $paymentSettings->wallet_account_number }}</span>
                            <span class="text-[11px] text-slate-300 block font-semibold mt-0.5">a/n <strong class="text-white">{{ $paymentSettings->wallet_account_holder }}</strong></span>
                        </div>
                        <button 
                            type="button" 
                            @click="copyToClipboard(@js($paymentSettings->wallet_account_number), 'ewallet')" 
                            class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shrink-0 shadow-md active:scale-95"
                            :class="copiedField === 'ewallet' ? 'bg-emerald-500 text-white shadow-emerald-500/30' : 'bg-cyan-500 hover:bg-cyan-400 text-slate-900 shadow-cyan-500/30'"
                        >
                            <svg x-show="copiedField !== 'ewallet'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                            <svg x-show="copiedField === 'ewallet'" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span x-text="copiedField === 'ewallet' ? 'Tersalin! ✓' : 'Salin Nomor'"></span>
                        </button>
                    </div>
                </div>

                <div class="bg-[#0B1221] border border-slate-700/70 rounded-2xl p-4 text-xs text-slate-300 space-y-2">
                    <p class="font-bold text-cyan-400 text-xs flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Langkah Pembayaran via E-Wallet:
                    </p>
                    <ol class="list-decimal list-inside space-y-1.5 text-slate-400 leading-relaxed pl-1 text-[11px]">
                        <li>Buka aplikasi {{ $paymentSettings->wallet_provider }} Anda.</li>
                        <li>Pilih menu <strong class="text-white">Transfer / Kirim Uang</strong> ke nomor ponsel / sesama akun.</li>
                        <li>Masukkan nomor tujuan: <strong class="text-cyan-300 font-mono">{{ $paymentSettings->wallet_account_number }}</strong>.</li>
                        <li>Pastikan nama akun penerima adalah <strong class="text-white">{{ $paymentSettings->wallet_account_holder }}</strong>.</li>
                        <li>Ketikkan nominal yang sesuai: <span class="text-emerald-400 font-bold" x-text="selectedDue ? 'Rp ' + new Intl.NumberFormat('id-ID').format(selectedDue.amount) : ''"></span>.</li>
                        <li>Selesaikan transfer lalu simpan screenshot resi bukti pembayaran berhasil.</li>
                    </ol>
                </div>
            </div>
            @endif

            <!-- 3. DETAIL METODE: TRANSFER BANK -->
            @if($paymentSettings->hasBankAccount())
            <div x-show="payData.payment_method === 'TRANSFER'" x-transition class="space-y-4 mb-6">
                <div class="space-y-2.5">
                    <div class="bg-[#0B1221] border border-indigo-500/30 rounded-2xl p-3.5 flex items-center justify-between gap-3 hover:border-indigo-400/60 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-600/20 border border-blue-500/30 flex items-center justify-center font-black text-blue-400 text-xs shrink-0">
                                {{ strtoupper(substr($paymentSettings->bank_name, 0, 3)) }}
                            </div>
                            <div>
                                <span class="text-xs font-bold text-white block">{{ $paymentSettings->bank_name }}</span>
                                <p class="text-sm sm:text-base font-mono font-black text-indigo-300">{{ $paymentSettings->account_number }}</p>
                                <span class="text-[10px] text-slate-400 block">a/n <strong class="text-slate-200">{{ $paymentSettings->account_holder }}</strong></span>
                            </div>
                        </div>
                        <button 
                            type="button" 
                            @click="copyToClipboard(@js($paymentSettings->account_number), 'bank')" 
                            class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1 shrink-0 active:scale-95"
                            :class="copiedField === 'bank' ? 'bg-emerald-500 text-white' : 'bg-indigo-500/20 hover:bg-indigo-500/30 text-indigo-300 border border-indigo-500/40'"
                        >
                            <span x-text="copiedField === 'bank' ? 'Tersalin! ✓' : 'Salin Rekening'"></span>
                        </button>
                    </div>
                </div>

                <div class="bg-[#0B1221] border border-slate-700/70 rounded-2xl p-4 text-xs text-slate-300 space-y-2">
                    <p class="font-bold text-indigo-400 text-xs flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Langkah Pembayaran via Transfer Bank:
                    </p>
                    <ol class="list-decimal list-inside space-y-1.5 text-slate-400 leading-relaxed pl-1 text-[11px]">
                        <li>Buka aplikasi Mobile/Internet Banking bank Anda atau kunjungi mesin ATM terdekat.</li>
                        <li>Pilih menu <strong class="text-white">Transfer Saldo</strong> (ke Rekening Bank yang sama atau Antar Bank Online).</li>
                        <li>Masukkan nomor rekening tujuan yang tercantum di atas.</li>
                        <li>Pastikan nama penerima adalah <strong class="text-white">{{ $paymentSettings->account_holder }}</strong>.</li>
                        <li>Masukkan nominal tagihan: <span class="text-emerald-400 font-bold" x-text="selectedDue ? 'Rp ' + new Intl.NumberFormat('id-ID').format(selectedDue.amount) : ''"></span>.</li>
                        <li>Selesaikan transfer lalu simpan foto struk atau screenshot bukti transfer berhasil.</li>
                    </ol>
                </div>
            </div>
            @else
            <div x-show="payData.payment_method === 'TRANSFER'" class="mb-6 rounded-2xl border border-amber-500/30 bg-amber-500/10 p-4 text-sm text-amber-200">
                Rekening bank belum dikonfigurasi. Silakan pilih metode pembayaran lain atau hubungi pengurus.
            </div>
            @endif

            <!-- 4. DETAIL METODE: CASH / TUNAI -->
            <div x-show="payData.payment_method === 'CASH'" x-transition class="space-y-4 mb-6">
                <div class="bg-amber-950/30 border border-amber-500/30 rounded-2xl p-4 text-xs text-amber-200 leading-relaxed">
                    <p class="font-bold text-amber-400 text-sm flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Pembayaran Tunai ke Bendahara
                    </p>
                    <p class="mb-2 text-slate-300">Untuk pembayaran tunai, serahkan uang pas secara langsung kepada Bendahara PIK-R SKAKMATT saat jam sekolah atau kegiatan ekstrakurikuler.</p>
                    <p class="text-slate-400 text-[11px]">Sebagai pencatatan di sistem, minta nota / paraf bukti penyerahan uang dari bendahara, lalu unggah fotonya melalui formulir di bawah ini.</p>
                </div>
            </div>

            <!-- Disclaimer Verifikasi Manual -->
            <div class="p-3 bg-amber-500/10 border border-amber-500/30 rounded-2xl text-[11px] text-amber-300 mb-6 flex items-start gap-2.5">
                <span class="text-amber-400 text-sm mt-0.5">⚠️</span>
                <div>
                    <strong class="font-bold text-amber-300">PENTING - VERIFIKASI MANUAL:</strong>
                    <p class="text-slate-300 text-[11px] mt-0.5">Setelah mengunggah bukti, status tagihan Anda akan berstatus <strong class="text-amber-300">MENUNGGU VERIFIKASI</strong>. Status akan otomatis berubah menjadi <strong class="text-emerald-400">LUNAS</strong> setelah Bendahara mengonfirmasi mutasi pembayaran Anda.</p>
                </div>
            </div>

            <!-- Form Upload Bukti -->
            <form method="POST" action="{{ route('payments.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="due_id" x-model="payData.due_id" />
                <input type="hidden" name="payment_method" :value="payData.payment_method" />

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Upload Bukti Pembayaran (JPG/PNG, Maksimal 2MB) <span class="text-emerald-400">*</span>
                    </label>
                    <input 
                        type="file" 
                        name="proof" 
                        accept="image/jpeg,image/png,image/jpg" 
                        @change="handleFileSelect"
                        class="w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-500/20 file:text-emerald-300 hover:file:bg-emerald-500/30 file:cursor-pointer transition-colors border border-slate-700 rounded-xl bg-[#0B1221] p-2" 
                        required 
                    />
                </div>

                <!-- Preview Gambar Bukti -->
                <template x-if="previewProofUrl">
                    <div class="p-3.5 bg-[#0B1221] border border-slate-700 rounded-2xl text-center">
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold block mb-2">Preview Bukti yang Dipilih</span>
                        <img :src="previewProofUrl" alt="Preview Bukti Pembayaran" class="max-h-52 rounded-xl mx-auto shadow-md border border-slate-600 object-contain" />
                    </div>
                </template>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Catatan Tambahan (Opsional)</label>
                    <input type="text" name="notes" placeholder="Contoh: Transfer atas nama Fulan kelas XI RPL 2" class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" />
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-700/60 mt-6">
                    <button type="button" @click="payModal = false" class="px-5 py-2.5 text-slate-300 font-semibold hover:bg-slate-800 rounded-xl transition-colors text-xs">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold rounded-xl shadow-[0_0_20px_rgba(16,185,129,0.4)] hover:shadow-[0_0_30px_rgba(16,185,129,0.6)] transition-all text-xs flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Kirim Bukti Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($canManageFinance)
        <!-- ======================================================== -->
        <!-- MODAL TINJAU BUKTI PEMBAYARAN (UNTUK PENGURUS)          -->
        <!-- ======================================================== -->
        <div x-show="reviewModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
            <div x-show="reviewModal" x-transition.opacity class="fixed inset-0 bg-black/85 backdrop-blur-md" @click="reviewModal = false"></div>
            
            <div x-show="reviewModal" x-transition class="bg-[#0F172A] border border-cyan-500/30 rounded-3xl w-full max-w-xl p-6 md:p-8 relative z-10 shadow-2xl text-white max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-700 mb-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>
                        Tinjau Bukti Pembayaran
                    </h3>
                    <button type="button" @click="reviewModal = false" class="text-slate-400 hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3 bg-[#0B1221] p-3.5 rounded-2xl text-xs border border-slate-800">
                        <div>
                            <span class="text-slate-400 text-[10px] uppercase font-bold block">Anggota</span>
                            <span class="font-bold text-white" x-text="reviewPayment?.user?.member?.name || reviewPayment?.user?.name || '-'"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 text-[10px] uppercase font-bold block">Tagihan</span>
                            <span class="font-bold text-emerald-400" x-text="reviewPayment?.due?.title || '-'"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 text-[10px] uppercase font-bold block">Nominal</span>
                            <span class="font-bold text-white" x-text="reviewPayment ? 'Rp ' + new Intl.NumberFormat('id-ID').format(reviewPayment.amount) : 'Rp 0'"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 text-[10px] uppercase font-bold block">Status Saat Ini</span>
                            <span class="font-bold text-amber-400" x-text="reviewPayment?.status || '-'"></span>
                        </div>
                    </div>

                    <!-- Bukti Image -->
                    <div class="text-center p-3 bg-[#0B1221] border border-slate-700 rounded-2xl">
                        <template x-if="reviewPayment?.proof_file">
                            <div>
                                <img :src="'/storage/' + reviewPayment.proof_file" alt="Bukti Pembayaran" class="max-h-80 mx-auto rounded-xl shadow-lg border border-slate-700 object-contain" />
                                <a :href="'/storage/' + reviewPayment.proof_file" target="_blank" class="text-xs text-cyan-400 hover:underline mt-2 inline-block">
                                    Buka Gambar Asli (Ukuran Penuh) ↗
                                </a>
                            </div>
                        </template>
                        <template x-if="!reviewPayment?.proof_file">
                            <p class="text-slate-400 text-xs py-8">Tidak ada file bukti transfer yang dilampirkan.</p>
                        </template>
                    </div>

                    <!-- Tindakan Verifikasi -->
                    <template x-if="reviewPayment?.status === 'MENUNGGU_VERIFIKASI'">
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                            <button 
                                type="button" 
                                @click="openRejectModal(reviewPayment.id)"
                                class="px-5 py-2.5 bg-red-500/20 hover:bg-red-500 text-red-300 hover:text-white border border-red-500/40 rounded-xl text-xs font-bold transition-all"
                            >
                                Tolak Pembayaran
                            </button>

                            <form :action="'/payments/' + reviewPayment.id + '/verify'" method="POST" onsubmit="return confirm('Konfirmasi bahwa dana telah benar-benar diterima di kas?')">
                                @csrf
                                <input type="hidden" name="action" value="setujui" />
                                <button type="submit" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl text-xs font-bold shadow-lg transition-all">
                                    Setujui Pembayaran (Lunas)
                                </button>
                            </form>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- MODAL TOLAK DENGAN ALASAN -->
        <div x-show="rejectModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div x-show="rejectModal" x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="rejectModal = false"></div>
            
            <div x-show="rejectModal" x-transition class="bg-[#0F172A] border border-red-500/30 rounded-3xl w-full max-w-md p-6 relative z-10 shadow-2xl text-white">
                <div class="flex items-center justify-between pb-3 border-b border-slate-700 mb-4">
                    <h3 class="text-base font-bold text-red-400 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Tolak Pembayaran Anggota
                    </h3>
                    <button type="button" @click="rejectModal = false" class="text-slate-400 hover:text-white">✕</button>
                </div>

                <form :action="'/payments/' + rejectPaymentId + '/verify'" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="action" value="tolak" />
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                            Alasan Penolakan <span class="text-red-400">*</span>
                        </label>
                        <textarea 
                            name="rejection_reason" 
                            x-model="rejectReason"
                            rows="3" 
                            placeholder="Contoh: Bukti transfer buram, mutasi rekening belum masuk, atau nominal tidak sesuai." 
                            class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-4 py-3 text-xs text-white placeholder-slate-500 focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                            required
                        ></textarea>
                        <p class="text-[11px] text-slate-400 mt-1">Alasan ini akan ditampilkan kepada anggota agar mereka dapat memperbaiki pengiriman bukti.</p>
                    </div>

                    <div class="flex justify-end gap-3 pt-3 border-t border-slate-700">
                        <button type="button" @click="rejectModal = false" class="px-4 py-2 text-slate-400 hover:bg-slate-800 rounded-xl text-xs font-semibold">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-500 text-white rounded-xl text-xs font-bold transition-all shadow-md">
                            Konfirmasi Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- MODAL CREATE TAGIHAN BARU (UNTUK PENGURUS)              -->
        <!-- ======================================================== -->
        <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
            <div x-show="showCreateModal" x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="showCreateModal = false"></div>
            
            <div x-show="showCreateModal" x-transition class="bg-[#0F172A] border border-emerald-500/30 rounded-3xl w-full max-w-lg p-7 relative z-10 shadow-2xl text-white max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-700/60 mb-5">
                    <h3 class="text-lg md:text-xl font-bold text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(16,185,129,0.8)]"></span>
                        Terbitkan Tagihan Baru
                    </h3>
                    <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-white transition-colors">✕</button>
                </div>
                
                <form method="POST" action="{{ route('kas.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Nama Tagihan / Judul Iuran <span class="text-emerald-400">*</span></label>
                        <input type="text" name="title" placeholder="Contoh: Iuran Kas Bulan Oktober 2026" class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-4 py-3 text-xs text-white placeholder-slate-500 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" required />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Nominal (Rp) <span class="text-emerald-400">*</span></label>
                            <input type="number" name="amount" min="1000" step="500" placeholder="5000" class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-4 py-3 text-xs text-white placeholder-slate-500 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" required />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Jenis Kas</label>
                            <select name="type" class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-4 py-3 text-xs text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                <option value="BULANAN">Bulanan</option>
                                <option value="KEGIATAN">Kegiatan / Event</option>
                                <option value="KHUSUS">Khusus / Sukarela</option>
                                <option value="MINGGUAN">Mingguan (Kas Rp 2.000)</option>
                            </select>
                        </div>
                    </div>

                    <div x-data="{ targetType: 'SEMUA' }" class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Target Anggota yang Ditagih</label>
                            <select name="target_type" x-model="targetType" class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-4 py-3 text-xs text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                <option value="SEMUA">Semua Anggota Aktif Organisasi</option>
                                <option value="DIVISI">Hanya Anggota Divisi Tertentu</option>
                                <option value="INTI">Hanya Pengurus Inti (Ketua, Wakil, Sekretaris, Bendahara)</option>
                            </select>
                        </div>

                        <div x-show="targetType === 'DIVISI'" x-transition>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Pilih Divisi Target</label>
                            <select name="target_division_id" class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-4 py-3 text-xs text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                <option value="">-- Pilih Divisi --</option>
                                @foreach($divisions as $div)
                                    <option value="{{ $div->id }}">{{ $div->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Tenggat Waktu Pembayaran (Deadline)</label>
                        <input type="date" name="deadline" class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-4 py-3 text-xs text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Deskripsi / Catatan Tambahan</label>
                        <textarea name="description" rows="2" placeholder="Tuliskan tujuan iuran kas ini..." class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white placeholder-slate-500 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-700/60 mt-6">
                        <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 text-slate-400 font-semibold hover:bg-slate-800 rounded-xl transition-colors text-xs">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold rounded-xl shadow-[0_0_20px_rgba(16,185,129,0.4)] hover:shadow-[0_0_30px_rgba(16,185,129,0.6)] transition-all text-xs">
                            Simpan & Terbitkan Tagihan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- MODAL EDIT TAGIHAN (UNTUK PENGURUS)                     -->
        <!-- ======================================================== -->
        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
            <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="showEditModal = false"></div>
            
            <div x-show="showEditModal" x-transition class="bg-[#0F172A] border border-cyan-500/30 rounded-3xl w-full max-w-lg p-7 relative z-10 shadow-2xl text-white max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-700/60 mb-5">
                    <h3 class="text-lg md:text-xl font-bold text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>
                        Edit Data Tagihan
                    </h3>
                    <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-white transition-colors">✕</button>
                </div>
                
                <form :action="'/kas/' + editDueData.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Nama Tagihan <span class="text-emerald-400">*</span></label>
                        <input type="text" name="title" x-model="editDueData.title" class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-4 py-3 text-xs text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" required />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Nominal (Rp) <span class="text-emerald-400">*</span></label>
                            <input type="number" name="amount" min="1000" x-model="editDueData.amount" class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-4 py-3 text-xs text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" required />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Jenis Kas</label>
                            <select name="type" x-model="editDueData.type" class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-4 py-3 text-xs text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all">
                                <option value="BULANAN">Bulanan</option>
                                <option value="KEGIATAN">Kegiatan / Event</option>
                                <option value="KHUSUS">Khusus / Sukarela</option>
                                <option value="MINGGUAN">Mingguan (Kas Rp 2.000)</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Target Anggota</label>
                            <select name="target_type" x-model="editDueData.target_type" class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-4 py-3 text-xs text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all">
                                <option value="SEMUA">Semua Anggota Aktif</option>
                                <option value="DIVISI">Hanya Divisi Tertentu</option>
                                <option value="INTI">Hanya Pengurus Inti</option>
                            </select>
                        </div>

                        <div x-show="editDueData.target_type === 'DIVISI'" x-transition>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Pilih Divisi</label>
                            <select name="target_division_id" x-model="editDueData.target_division_id" class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-4 py-3 text-xs text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all">
                                <option value="">-- Pilih Divisi --</option>
                                @foreach($divisions as $div)
                                    <option value="{{ $div->id }}">{{ $div->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Tenggat Waktu</label>
                        <input type="date" name="deadline" x-model="editDueData.deadline" class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-4 py-3 text-xs text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Deskripsi</label>
                        <textarea name="description" x-model="editDueData.description" rows="2" class="w-full bg-[#0B1221] border border-slate-700 rounded-xl px-4 py-2.5 text-xs text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-700/60 mt-6">
                        <button type="button" @click="showEditModal = false" class="px-5 py-2.5 text-slate-400 font-semibold hover:bg-slate-800 rounded-xl transition-colors text-xs">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-cyan-500 hover:bg-cyan-400 text-white font-bold rounded-xl shadow-lg transition-all text-xs">
                            Perbarui Tagihan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
@endsection
