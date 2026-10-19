@extends('layouts.admin')

@section('title', 'Verifikasi & Administrasi Anggota - PIK-R SKAKMATT')
@section('header', 'Verifikasi & Administrasi Anggota')

@section('content')
<div class="space-y-6" x-data="{ 
    activeTab: '{{ request('active_tab', 'permohonan') }}', 
    modalRejectAppOpen: false, 
    selectedAppId: null, 
    selectedAppName: '',
    modalRevisionAppOpen: false,
    selectedRevisionAppId: null,
    selectedRevisionAppName: '',
    modalRejectUserOpen: false, 
    selectedUserId: null, 
    selectedUserName: '', 
    modalAddMasterOpen: false,
    modalImportMasterOpen: false
}">

    <!-- Flash Notification -->
    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-300 text-sm flex items-center justify-between shadow-[0_0_20px_rgba(16,185,129,0.15)]">
            <div class="flex items-center gap-3">
                <span class="p-2 bg-emerald-500/20 rounded-xl text-emerald-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </span>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-500/10 border border-rose-500/30 rounded-2xl text-rose-300 text-sm flex items-center justify-between shadow-[0_0_20px_rgba(244,63,94,0.15)]">
            <div class="flex items-center gap-3">
                <span class="p-2 bg-rose-500/20 rounded-xl text-rose-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </span>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-white">&times;</button>
        </div>
    @endif

    <!-- Header Description & Tab Switcher -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-[#0B1221]/80 border border-cyan-500/20 p-6 rounded-2xl backdrop-blur-xl shadow-lg">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 bg-cyan-500/10 text-cyan-400 text-[10px] font-black uppercase tracking-widest rounded-md border border-cyan-500/30">
                    Pusat Administrasi & Validasi
                </span>
                <span class="text-gray-400 text-xs">Akses Khusus: Ketua, Sekretaris & Admin</span>
            </div>
            <h1 class="text-2xl font-black text-white mt-1">Verifikasi Pendaftaran & Buku Induk Anggota</h1>
            <p class="text-gray-400 text-sm mt-0.5">Kelola persetujuan permohonan anggota baru, pencocokan identitas Buku Induk, dan status akun.</p>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex items-center bg-black/40 p-1.5 rounded-xl border border-white/5 shrink-0 flex-wrap gap-1">
            <button 
                @click="activeTab = 'permohonan'" 
                :class="activeTab === 'permohonan' ? 'bg-gradient-to-r from-cyan-500 to-emerald-500 text-white font-bold shadow-[0_0_15px_rgba(6,182,212,0.3)]' : 'text-gray-400 hover:text-white'"
                class="px-4 py-2 rounded-lg text-xs transition-all duration-200 flex items-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Permohonan Anggota Baru
                @if(($totalAppPending + $totalAppReview) > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-cyan-400 text-black animate-pulse">
                        {{ $totalAppPending + $totalAppReview }}
                    </span>
                @endif
            </button>
            <button 
                @click="activeTab = 'buku_induk'" 
                :class="activeTab === 'buku_induk' ? 'bg-gradient-to-r from-cyan-500 to-emerald-500 text-white font-bold shadow-[0_0_15px_rgba(6,182,212,0.3)]' : 'text-gray-400 hover:text-white'"
                class="px-4 py-2 rounded-lg text-xs transition-all duration-200 flex items-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                Buku Induk Master Siswa
                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-white/10 text-cyan-300">
                    {{ $totalMaster }}
                </span>
            </button>
            <button 
                @click="activeTab = 'pendaftar'" 
                :class="activeTab === 'pendaftar' ? 'bg-gradient-to-r from-cyan-500 to-emerald-500 text-white font-bold shadow-[0_0_15px_rgba(6,182,212,0.3)]' : 'text-gray-400 hover:text-white'"
                class="px-4 py-2 rounded-lg text-xs transition-all duration-200 flex items-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                Pendaftar Akun
                @if($totalPending > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-400 text-black">
                        {{ $totalPending }}
                    </span>
                @endif
            </button>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- TAB 1: PERMOHONAN ANGGOTA BARU                           --}}
    {{-- ======================================================== --}}
    <div x-show="activeTab === 'permohonan'" class="space-y-6">
        <!-- Statistik Permohonan -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="bg-[#0B1221]/70 border border-cyan-500/30 p-5 rounded-2xl backdrop-blur-xl relative overflow-hidden shadow-lg">
                <div class="text-[11px] font-bold text-cyan-400 uppercase tracking-widest">Menunggu Persetujuan</div>
                <div class="text-3xl font-black text-white mt-1">{{ $totalAppPending }}</div>
                <p class="text-[11px] text-gray-400 mt-2">Identitas cocok otomatis & siap disetujui</p>
            </div>
            <div class="bg-[#0B1221]/70 border border-amber-500/30 p-5 rounded-2xl backdrop-blur-xl relative overflow-hidden shadow-lg">
                <div class="text-[11px] font-bold text-amber-400 uppercase tracking-widest">Perlu Pemeriksaan</div>
                <div class="text-3xl font-black text-white mt-1">{{ $totalAppReview }}</div>
                <p class="text-[11px] text-gray-400 mt-2">Memerlukan cek manual pengurus</p>
            </div>
            <div class="bg-[#0B1221]/70 border border-emerald-500/30 p-5 rounded-2xl backdrop-blur-xl relative overflow-hidden shadow-lg">
                <div class="text-[11px] font-bold text-emerald-400 uppercase tracking-widest">Disetujui / Aktif</div>
                <div class="text-3xl font-black text-white mt-1">{{ $totalAppApproved }}</div>
                <p class="text-[11px] text-gray-400 mt-2">Resmi tercatat di daftar anggota</p>
            </div>
            <div class="bg-[#0B1221]/70 border border-rose-500/30 p-5 rounded-2xl backdrop-blur-xl relative overflow-hidden shadow-lg">
                <div class="text-[11px] font-bold text-rose-400 uppercase tracking-widest">Ditolak</div>
                <div class="text-3xl font-black text-white mt-1">{{ $totalAppRejected }}</div>
                <p class="text-[11px] text-gray-400 mt-2">Permohonan belum memenuhi syarat</p>
            </div>
        </div>

        <!-- Filter Bar Permohonan -->
        <div class="bg-[#0B1221]/80 border border-white/10 p-4 rounded-2xl">
            <form method="GET" action="{{ route('feature.verifikasi_anggota') }}" class="flex flex-col sm:flex-row gap-3">
                <input type="hidden" name="active_tab" value="permohonan">
                <div class="relative flex-1">
                    <input type="text" name="app_search" value="{{ request('app_search') }}" 
                           placeholder="Cari nama pemohon, kelas, atau NISN..." 
                           class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50">
                </div>
                <div class="w-full sm:w-48">
                    <select name="app_status" class="w-full bg-[#070B19] border border-white/10 rounded-xl px-3 py-2.5 text-xs text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="MENUNGGU_PERSETUJUAN" {{ request('app_status') == 'MENUNGGU_PERSETUJUAN' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="PERLU_PEMERIKSAAN" {{ request('app_status') == 'PERLU_PEMERIKSAAN' ? 'selected' : '' }}>Perlu Pemeriksaan</option>
                        <option value="DISETUJUI" {{ request('app_status') == 'DISETUJUI' ? 'selected' : '' }}>Disetujui</option>
                        <option value="DITOLAK" {{ request('app_status') == 'DITOLAK' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs uppercase tracking-wider transition-all">
                    Filter
                </button>
            </form>
        </div>

        <!-- Tabel Permohonan Keanggotaan -->
        <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-300">
                    <thead class="bg-black/40 border-b border-white/10 text-gray-400 uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="py-4 px-4">Calon Anggota</th>
                            <th class="py-4 px-3">Kelas & NISN</th>
                            <th class="py-4 px-4">Validasi Identitas</th>
                            <th class="py-4 px-4">Alasan Bergabung</th>
                            <th class="py-4 px-3 text-center">Status</th>
                            <th class="py-4 px-4 text-right">Aksi Pengurus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($applications as $app)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-4 px-4">
                                <div class="font-bold text-white text-sm">{{ $app->name }}</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">{{ $app->user?->email ?: 'Tanpa Akun' }}</div>
                                <span class="text-[10px] text-gray-500 block mt-1">Diajukan: {{ $app->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="py-4 px-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded bg-white/5 border border-white/10 text-cyan-300 font-semibold">{{ $app->kelas }}</span>
                                <div class="text-[10px] text-gray-400 mt-1 font-mono">NISN: {{ $app->nisn ?: '-' }}</div>
                            </td>
                            <td class="py-4 px-4 max-w-xs">
                                @if($app->validation_status === 'VALID_AUTOMATIS')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[10px] font-bold">
                                        <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Valid Otomatis
                                    </span>
                                @elseif($app->validation_status === 'PERLU_PEMERIKSAAN')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[10px] font-bold">
                                        <svg class="w-3 h-3 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        Perlu Cek Manual
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-gray-500/20 text-gray-400 border border-white/10 text-[10px] font-bold">
                                        Belum di Buku Induk
                                    </span>
                                @endif
                                <p class="text-[10px] text-gray-400 mt-1 leading-snug line-clamp-2" title="{{ $app->validation_notes }}">
                                    {{ $app->validation_notes }}
                                </p>
                            </td>
                            <td class="py-4 px-4 max-w-xs">
                                <p class="text-[11px] text-gray-300 line-clamp-2 italic" title="{{ $app->reason }}">
                                    "{{ $app->reason }}"
                                </p>
                            </td>
                            <td class="py-4 px-3 text-center whitespace-nowrap">
                                @if($app->status === 'DISETUJUI')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[10px] font-bold uppercase">Disetujui</span>
                                @elseif($app->status === 'DITOLAK')
                                    <span class="px-2.5 py-1 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/30 text-[10px] font-bold uppercase" title="{{ $app->rejection_reason }}">Ditolak</span>
                                @elseif($app->status === 'PERLU_PEMERIKSAAN')
                                    <span class="px-2.5 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[10px] font-bold uppercase">Cek Manual</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 text-[10px] font-bold uppercase">Menunggu</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-right whitespace-nowrap">
                                @if($app->status !== 'DISETUJUI')
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- Setujui -->
                                    <form action="{{ route('verifikasi.application.approve', $app->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[11px] shadow-sm transition-all" onclick="return confirm('Setujui permohonan keanggotaan {{ addslashes($app->name) }} dan masukkan ke daftar anggota aktif?')">
                                            Setujui
                                        </button>
                                    </form>

                                    <!-- Minta Revisi -->
                                    <button type="button" 
                                            @click="selectedRevisionAppId = {{ $app->id }}; selectedRevisionAppName = '{{ addslashes($app->name) }}'; modalRevisionAppOpen = true"
                                            class="px-2.5 py-1.5 rounded-lg bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-500/30 text-[11px] font-semibold transition-all"
                                            title="Beri catatan untuk perbaikan data siswa">
                                        Revisi
                                    </button>

                                    <!-- Tolak -->
                                    <button type="button" 
                                            @click="selectedAppId = {{ $app->id }}; selectedAppName = '{{ addslashes($app->name) }}'; modalRejectAppOpen = true"
                                            class="px-2.5 py-1.5 rounded-lg bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/30 text-[11px] font-semibold transition-all">
                                        Tolak
                                    </button>
                                </div>
                                @else
                                <span class="text-[11px] text-gray-500 italic">Telah disetujui oleh {{ $app->reviewer?->name ?? 'Pengurus' }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500 italic">
                                Belum ada permohonan keanggotaan baru yang diajukan siswa.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($applications->hasPages())
            <div class="p-4 border-t border-white/5">
                {{ $applications->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- TAB 2: BUKU INDUK MASTER SISWA                           --}}
    {{-- ======================================================== --}}
    <div x-show="activeTab === 'buku_induk'" class="space-y-6">
        <!-- Toolbar Buku Induk -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-[#0B1221]/80 border border-white/10 p-4 rounded-2xl">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('verifikasi.master.export', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 text-xs font-bold border border-emerald-500/30 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Ekspor Excel</span>
                </a>
                <a href="{{ route('verifikasi.master.export_pdf', request()->query()) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 text-xs font-bold border border-rose-500/30 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    <span>Unduh PDF</span>
                </a>
                <button type="button" @click="modalImportMasterOpen = true" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 text-xs font-bold border border-white/10 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span>Impor Excel (.xlsx)</span>
                </button>
            </div>
            <div>
                <button type="button" @click="modalAddMasterOpen = true" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 hover:from-cyan-400 hover:to-emerald-400 text-white font-bold text-xs uppercase tracking-wider shadow-lg transition-all">
                    + Tambah Siswa Manual
                </button>
            </div>
        </div>

        <!-- Tabel Buku Induk Siswa -->
        <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-300">
                    <thead class="bg-black/40 border-b border-white/10 text-gray-400 uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="py-4 px-4">Nama Siswa</th>
                            <th class="py-4 px-3">Kelas</th>
                            <th class="py-4 px-3">NISN</th>
                            <th class="py-4 px-4">Tempat & Tanggal Lahir</th>
                            <th class="py-4 px-3">No HP / WA</th>
                            <th class="py-4 px-3 text-center">Status Akun</th>
                            <th class="py-4 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($masterMembers as $mm)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-3 px-4 font-bold text-white">
                                {{ $mm->name }}
                            </td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded bg-white/5 text-cyan-300 font-semibold">{{ $mm->kelas }}</span>
                            </td>
                            <td class="py-3 px-3 font-mono text-[11px] text-gray-400">
                                {{ $mm->nisn ?: '-' }}
                            </td>
                            <td class="py-3 px-4 text-gray-400">
                                {{ $mm->tempat_lahir ?: '-' }}, {{ $mm->tanggal_lahir ? $mm->tanggal_lahir->format('d/m/Y') : '-' }}
                            </td>
                            <td class="py-3 px-3 text-gray-400">
                                {{ $mm->no_hp ?: '-' }}
                            </td>
                            <td class="py-3 px-3 text-center">
                                @if($mm->is_registered)
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold">Terdaftar</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-white/5 text-gray-400 text-[10px]">Belum Akun</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <form action="{{ route('verifikasi.master.destroy', $mm->id) }}" method="POST" onsubmit="return confirm('Hapus siswa {{ addslashes($mm->name) }} dari Buku Induk?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-400 hover:text-rose-300 text-[11px] font-semibold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-500 italic">
                                Buku Induk siswa masih kosong. Silakan tambah manual atau impor data melalui Excel.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($masterMembers->hasPages())
            <div class="p-4 border-t border-white/5">
                {{ $masterMembers->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- TAB 3: PENDAFTAR AKUN PENGGUNA (APPROVAL AKUN ADMIN)     --}}
    {{-- ======================================================== --}}
    <div x-show="activeTab === 'pendaftar'" class="space-y-6">
        <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
            <div class="p-5 border-b border-white/10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        Daftar Pendaftaran Akun Siswa (Tahap 3: Approval Akun)
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Pencocokan identitas dilakukan secara otomatis oleh sistem dengan database pendaftaran anggota & Buku Induk. Admin menentukan approval akhir sebelum akun aktif.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs font-bold">
                        Menunggu Approval: {{ $totalPending }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-300">
                    <thead class="bg-black/40 border-b border-white/10 text-gray-400 uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="py-4 px-4">Email & Akun Diajukan</th>
                            <th class="py-4 px-4">Data Database Keanggotaan</th>
                            <th class="py-4 px-3 text-center">Status Keanggotaan</th>
                            <th class="py-4 px-4">Hasil Pencocokan Otomatis</th>
                            <th class="py-4 px-3 text-center">Status Akun</th>
                            <th class="py-4 px-4 text-right">Aksi Approval</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($pendaftar as $p)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-4 px-4">
                                <div class="font-bold text-white">{{ $p->name }}</div>
                                <div class="text-[11px] text-cyan-400 font-mono">{{ $p->email }}</div>
                                <div class="text-[10px] text-gray-400 mt-0.5">WA: {{ $p->phone ?: '-' }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-white">
                                    {{ $p->matched_name ?: $p->name }}
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 font-semibold text-[10px]">
                                        Kelas: {{ $p->matched_kelas ?: ($p->kelas ?: '-') }}
                                    </span>
                                    <span class="text-[10px] text-gray-400 font-mono">
                                        NISN: {{ $p->matched_nisn ?: ($p->nisn ?: '-') }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-3 text-center">
                                @if($p->membership_status === 'TERVERIFIKASI' || $p->match_status === 'TERVERIFIKASI')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[10px] font-bold">
                                        Terverifikasi
                                    </span>
                                @elseif($p->membership_status === 'MENUNGGU' || $p->match_status === 'MENUNGGU_VERIFIKASI_ANGGOTA')
                                    <span class="px-2.5 py-1 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[10px] font-bold">
                                        Menunggu Persetujuan
                                    </span>
                                @elseif($p->membership_status === 'TIDAK_DITEMUKAN' || $p->match_status === 'TIDAK_DITEMUKAN')
                                    <span class="px-2.5 py-1 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/30 text-[10px] font-bold">
                                        Tidak Ditemukan
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-white/5 text-gray-300 text-[10px] font-semibold">
                                        {{ $p->membership_status ?: 'Belum Ada' }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                @if($p->match_status === 'TERVERIFIKASI')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-[10px] font-bold">
                                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Data Siswa Terverifikasi
                                    </span>
                                @elseif($p->match_status === 'MENUNGGU_VERIFIKASI_ANGGOTA')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[10px] font-bold">
                                        <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Menunggu Verifikasi Keanggotaan
                                    </span>
                                @elseif($p->match_status === 'AMBIGU')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-500/20 text-purple-300 border border-purple-500/30 text-[10px] font-bold">
                                        <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        Data Ambigu / Perlu Pemeriksaan
                                    </span>
                                @elseif($p->match_status === 'SUDAH_TERHUBUNG')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-500/20 text-rose-300 border border-rose-500/30 text-[10px] font-bold">
                                        Sudah Terhubung Akun Lain
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-500/20 text-rose-300 border border-rose-500/30 text-[10px] font-bold">
                                        Data Belum Ditemukan
                                    </span>
                                @endif
                                <div class="text-[10px] text-gray-400 mt-1 max-w-xs leading-relaxed">
                                    {{ $p->match_notes ?: '-' }}
                                </div>
                            </td>
                            <td class="py-4 px-3 text-center">
                                @if($p->status_verifikasi === 'APPROVED')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold border border-emerald-500/30">Approved</span>
                                @elseif($p->status_verifikasi === 'REJECTED')
                                    <span class="px-2.5 py-1 rounded-full bg-rose-500/20 text-rose-300 text-[10px] font-bold border border-rose-500/30">Rejected</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-amber-500/20 text-amber-300 text-[10px] font-bold border border-amber-500/30">Menunggu Approval</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-right">
                                @if($p->status_verifikasi !== 'APPROVED')
                                <div class="inline-flex items-center justify-end gap-1.5 flex-wrap">
                                    <form action="{{ route('verifikasi.approve', $p->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[11px] shadow-sm transition-all flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Setujui Akun
                                        </button>
                                    </form>
                                    <button type="button" @click="selectedUserId = {{ $p->id }}; selectedUserName = '{{ addslashes($p->name) }}'; modalRejectUserOpen = true" class="px-2.5 py-1.5 rounded-lg bg-rose-500/20 text-rose-300 hover:bg-rose-500/30 border border-rose-500/30 text-[11px] font-semibold transition-all">
                                        Tolak Akun
                                    </button>
                                </div>
                                @else
                                <span class="text-emerald-400 text-[11px] font-semibold flex items-center justify-end gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Akun Aktif
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500 italic">
                                Belum ada pendaftaran akun yang memerlukan persetujuan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pendaftar->hasPages())
            <div class="p-4 border-t border-white/5">
                {{ $pendaftar->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- MODAL TOLAK PERMOHONAN ANGGOTA -->
    <div x-show="modalRejectAppOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm" style="display: none;">
        <div class="bg-[#0B1221] border border-rose-500/30 rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl">
            <h3 class="text-base font-bold text-white">Tolak Permohonan Keanggotaan</h3>
            <p class="text-xs text-gray-300">Tolak pengajuan keanggotaan <span class="font-bold text-white" x-text="selectedAppName"></span>. Akun siswa tidak akan dihapus.</p>
            <form :action="'/feature/Verifikasi-Anggota/application/' + selectedAppId + '/reject'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Alasan Penolakan *</label>
                    <textarea name="rejection_reason" rows="3" required placeholder="Contoh: Belum memenuhi syarat komitmen atau NISN tidak terdaftar..." class="w-full bg-black/40 border border-white/10 rounded-xl p-3 text-xs text-white focus:outline-none focus:ring-2 focus:ring-rose-500"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="modalRejectAppOpen = false" class="px-4 py-2 rounded-xl bg-white/5 text-gray-300 text-xs font-semibold">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold">Konfirmasi Tolak</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL MINTA REVISI PERMOHONAN ANGGOTA -->
    <div x-show="modalRevisionAppOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm" style="display: none;">
        <div class="bg-[#0B1221] border border-amber-500/30 rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl">
            <h3 class="text-base font-bold text-white">Minta Perbaikan / Revisi Data</h3>
            <p class="text-xs text-gray-300">Kirim catatan pemeriksaan manual kepada <span class="font-bold text-white" x-text="selectedRevisionAppName"></span> agar dapat memperbaiki datanya.</p>
            <form :action="'/feature/Verifikasi-Anggota/application/' + selectedRevisionAppId + '/revision'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Catatan Perbaikan *</label>
                    <textarea name="notes" rows="3" required placeholder="Contoh: Mohon periksa kembali ejaan nama lengkap dan pastikan kelas sesuai rapor..." class="w-full bg-black/40 border border-white/10 rounded-xl p-3 text-xs text-white focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="modalRevisionAppOpen = false" class="px-4 py-2 rounded-xl bg-white/5 text-gray-300 text-xs font-semibold">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold">Kirim Catatan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TOLAK AKUN PENGGUNA (LEGACY) -->
    <div x-show="modalRejectUserOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm" style="display: none;">
        <div class="bg-[#0B1221] border border-red-500/30 rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl">
            <h3 class="text-base font-bold text-white">Tolak Akun Siswa</h3>
            <form :action="'/feature/Verifikasi-Anggota/' + selectedUserId + '/reject'" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Alasan Penolakan</label>
                    <textarea name="rejection_reason" rows="3" required class="w-full bg-black/40 border border-white/10 rounded-xl p-3 text-xs text-white"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="modalRejectUserOpen = false" class="px-4 py-2 rounded-xl bg-white/5 text-gray-300 text-xs">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 text-white text-xs font-bold">Tolak Akun</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH MANUAL MASTER BUKU INDUK -->
    <div x-show="modalAddMasterOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm" style="display: none;">
        <div class="bg-[#0B1221] border border-cyan-500/30 rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <h3 class="text-base font-bold text-white">Tambah Siswa ke Buku Induk</h3>
                <button @click="modalAddMasterOpen = false" class="text-gray-400 hover:text-white">&times;</button>
            </div>
            <form action="{{ route('verifikasi.master.store') }}" method="POST" class="space-y-3.5 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-gray-300 mb-1">Nama Lengkap Siswa *</label>
                    <input type="text" name="name" required class="w-full bg-black/40 border border-white/10 rounded-xl px-3.5 py-2 text-white" />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">Kelas *</label>
                        <input type="text" name="kelas" required class="w-full bg-black/40 border border-white/10 rounded-xl px-3.5 py-2 text-white" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">NISN (Opsional)</label>
                        <input type="text" name="nisn" class="w-full bg-black/40 border border-white/10 rounded-xl px-3.5 py-2 text-white" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="w-full bg-black/40 border border-white/10 rounded-xl px-3.5 py-2 text-white" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="w-full bg-black/40 border border-white/10 rounded-xl px-3.5 py-2 text-white" />
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-gray-300 mb-1">No HP / WhatsApp</label>
                    <input type="text" name="no_hp" class="w-full bg-black/40 border border-white/10 rounded-xl px-3.5 py-2 text-white" />
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-white/5">
                    <button type="button" @click="modalAddMasterOpen = false" class="px-4 py-2 rounded-xl bg-white/5 text-gray-300">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold">Simpan ke Buku Induk</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL IMPOR EXCEL BUKU INDUK -->
    <div x-show="modalImportMasterOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm" style="display: none;">
        <div class="bg-[#0B1221] border border-cyan-500/30 rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <h3 class="text-base font-bold text-white">Impor Buku Induk Siswa via Excel (.xlsx)</h3>
                <button @click="modalImportMasterOpen = false" class="text-gray-400 hover:text-white">&times;</button>
            </div>
            <div class="p-3 rounded-xl bg-cyan-950/40 border border-cyan-500/30 text-xs text-cyan-200 flex items-center justify-between">
                <span>Gunakan format template standar untuk deteksi data yang akurat.</span>
                <a href="{{ route('verifikasi.master.template') }}" class="font-bold underline text-cyan-400 hover:text-cyan-300">
                    Unduh Template &darr;
                </a>
            </div>
            <form action="{{ route('verifikasi.master.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-gray-300 mb-1">Pilih File Excel (.xlsx) *</label>
                    <input type="file" name="excel_file" accept=".xlsx" required class="w-full bg-black/40 border border-white/10 rounded-xl p-2.5 text-white" />
                </div>
                <div>
                    <label class="block font-semibold text-gray-300 mb-1">Penanganan Data Ganda (Berdasarkan NISN/Nama)</label>
                    <div class="space-y-2 mt-1 text-gray-300">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="duplicate_mode" value="skip" checked class="text-cyan-500 focus:ring-cyan-400" />
                            <span>Lewati baris jika siswa sudah ada (Skip)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="duplicate_mode" value="update" class="text-cyan-500 focus:ring-cyan-400" />
                            <span>Perbarui data siswa yang cocok (Update)</span>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-white/5">
                    <button type="button" @click="modalImportMasterOpen = false" class="px-4 py-2 rounded-xl bg-white/5 text-gray-300">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-white font-bold">Mulai Impor</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
