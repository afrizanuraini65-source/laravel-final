@extends('layouts.admin')

@section('title', 'Pengaturan Periode Pendaftaran - PIK-R SKAKMATT')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                Pengaturan Periode Pendaftaran
            </h1>
            <p class="text-xs sm:text-sm text-gray-400 mt-1">
                Atur jadwal buka/tutup pendaftaran akun dan penerimaan anggota baru PIK-R SKAKMATT secara independen. (Zona Waktu: Asia/Jakarta - WIB)
            </p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-200 text-xs sm:text-sm flex items-center gap-3">
        <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Realtime Status & Quick Toggle Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- 1. Status Pendaftaran Akun -->
        <div class="bg-[#0B1221]/90 backdrop-blur-xl border {{ $isAccountOpen ? 'border-emerald-500/30' : 'border-amber-500/30' }} rounded-3xl p-6 shadow-xl flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Pendaftaran Akun Pengguna</span>
                    <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider {{ $isAccountOpen ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                        {{ $isAccountOpen ? 'DIBUKA' : 'DITUTUP' }}
                    </span>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">
                    {{ $isAccountOpen ? 'Siswa Dapat Mendaftar Akun' : 'Pendaftaran Akun Baru Nonaktif' }}
                </h3>
                <p class="text-xs text-gray-300 leading-relaxed mb-4">
                    @if($settings->account_registration_start || $settings->account_registration_end)
                        Jadwal: {{ $settings->account_registration_start ? $settings->account_registration_start->timezone('Asia/Jakarta')->format('d M Y, H:i') : 'Kapan saja' }} s/d {{ $settings->account_registration_end ? $settings->account_registration_end->timezone('Asia/Jakarta')->format('d M Y, H:i') : 'Tanpa batas' }} WIB.
                    @else
                        Mengikuti toggle status manual pengurus.
                    @endif
                </p>
            </div>
            <form action="{{ route('admin.settings.registration.toggle-account') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold uppercase tracking-wider transition-all {{ $settings->is_account_registration_open ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30 hover:bg-rose-500/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 hover:bg-emerald-500/30' }}">
                    {{ $settings->is_account_registration_open ? 'Tutup Pendaftaran Akun Manual' : 'Buka Pendaftaran Akun Manual' }}
                </button>
            </form>
        </div>

        <!-- 2. Status Penerimaan Anggota Baru -->
        <div class="bg-[#0B1221]/90 backdrop-blur-xl border {{ $isMemberOpen ? 'border-cyan-500/30' : 'border-amber-500/30' }} rounded-3xl p-6 shadow-xl flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Penerimaan Anggota Ekstrakurikuler</span>
                    <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider {{ $isMemberOpen ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                        {{ $isMemberOpen ? 'DIBUKA' : 'DITUTUP' }}
                    </span>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">
                    {{ $isMemberOpen ? 'Formulir Keanggotaan Terbuka' : 'Penerimaan Anggota Baru Nonaktif' }}
                </h3>
                <p class="text-xs text-gray-300 leading-relaxed mb-4">
                    @if($settings->member_registration_start || $settings->member_registration_end)
                        Jadwal: {{ $settings->member_registration_start ? $settings->member_registration_start->timezone('Asia/Jakarta')->format('d M Y, H:i') : 'Kapan saja' }} s/d {{ $settings->member_registration_end ? $settings->member_registration_end->timezone('Asia/Jakarta')->format('d M Y, H:i') : 'Tanpa batas' }} WIB.
                    @else
                        Mengikuti toggle status manual pengurus.
                    @endif
                </p>
            </div>
            <form action="{{ route('admin.settings.registration.toggle-member') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold uppercase tracking-wider transition-all {{ $settings->is_member_registration_open ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30 hover:bg-rose-500/30' : 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 hover:bg-cyan-500/30' }}">
                    {{ $settings->is_member_registration_open ? 'Tutup Penerimaan Anggota Manual' : 'Buka Penerimaan Anggota Manual' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Configuration Form -->
    <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
        <h2 class="text-lg font-bold text-white border-b border-white/10 pb-4">
            Form Detail Parameter & Jadwal Periode
        </h2>

        <form action="{{ route('admin.settings.registration.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Periode Pendaftaran Akun -->
            <div class="p-5 rounded-2xl bg-white/[0.02] border border-white/10 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-emerald-300 uppercase tracking-wider">
                        1. Pengaturan Pendaftaran Akun Aplikasi
                    </h3>
                    <label class="flex items-center gap-2 cursor-pointer text-xs text-white">
                        <input type="checkbox" name="is_account_registration_open" value="1" {{ $settings->is_account_registration_open ? 'checked' : '' }}
                               class="rounded bg-white/10 border-white/20 text-emerald-500 focus:ring-emerald-400">
                        <span>Aktifkan Pendaftaran Akun</span>
                    </label>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-300 mb-1">Tanggal & Jam Mulai (WIB)</label>
                        <input type="datetime-local" name="account_registration_start" 
                               value="{{ $settings->account_registration_start ? $settings->account_registration_start->timezone('Asia/Jakarta')->format('Y-m-d\TH:i') : '' }}"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-300 mb-1">Tanggal & Jam Berakhir (WIB)</label>
                        <input type="datetime-local" name="account_registration_end" 
                               value="{{ $settings->account_registration_end ? $settings->account_registration_end->timezone('Asia/Jakarta')->format('Y-m-d\TH:i') : '' }}"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Periode Penerimaan Anggota Baru -->
            <div class="p-5 rounded-2xl bg-white/[0.02] border border-white/10 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-cyan-300 uppercase tracking-wider">
                        2. Pengaturan Penerimaan Anggota Baru
                    </h3>
                    <label class="flex items-center gap-2 cursor-pointer text-xs text-white">
                        <input type="checkbox" name="is_member_registration_open" value="1" {{ $settings->is_member_registration_open ? 'checked' : '' }}
                               class="rounded bg-white/10 border-white/20 text-cyan-500 focus:ring-cyan-400">
                        <span>Aktifkan Penerimaan Anggota</span>
                    </label>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-300 mb-1">Tanggal & Jam Mulai (WIB)</label>
                        <input type="datetime-local" name="member_registration_start" 
                               value="{{ $settings->member_registration_start ? $settings->member_registration_start->timezone('Asia/Jakarta')->format('Y-m-d\TH:i') : '' }}"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:ring-2 focus:ring-cyan-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-300 mb-1">Tanggal & Jam Berakhir (WIB)</label>
                        <input type="datetime-local" name="member_registration_end" 
                               value="{{ $settings->member_registration_end ? $settings->member_registration_end->timezone('Asia/Jakarta')->format('Y-m-d\TH:i') : '' }}"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-white/10 text-white text-xs focus:ring-2 focus:ring-cyan-400 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Judul, Deskripsi & Persyaratan -->
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Judul Pengumuman Pendaftaran</label>
                    <input type="text" name="announcement_title" value="{{ old('announcement_title', $settings->announcement_title) }}"
                           class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white text-xs focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Deskripsi Pengumuman</label>
                    <textarea name="announcement_description" rows="3"
                              class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white text-xs focus:ring-2 focus:ring-emerald-400 focus:outline-none">{{ old('announcement_description', $settings->announcement_description) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">Persyaratan & Ketentuan Pendaftaran (Satu per baris)</label>
                    <textarea name="requirements" rows="4"
                              class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white text-xs focus:ring-2 focus:ring-emerald-400 focus:outline-none font-mono">{{ old('requirements', $settings->requirements) }}</textarea>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-white/10">
                <button type="submit" class="py-2.5 px-6 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg transition-all">
                    Simpan Semua Pengaturan
                </button>
            </div>
        </form>
    </div>

    <!-- Riwayat Perubahan Status (Audit Logs) -->
    <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-4">
        <h3 class="text-base font-bold text-white flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>Riwayat Perubahan Status & Pengurus yang Melakukan</span>
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-300">
                <thead class="bg-black/30 border-b border-white/10 text-gray-400 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Waktu (WIB)</th>
                        <th class="py-3 px-4">Pengurus</th>
                        <th class="py-3 px-4">Aksi</th>
                        <th class="py-3 px-4">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($logs as $log)
                    <tr class="hover:bg-white/[0.02]">
                        <td class="py-3 px-4 whitespace-nowrap text-gray-400">
                            {{ $log->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i:s') }}
                        </td>
                        <td class="py-3 px-4 whitespace-nowrap font-medium text-white">
                            {{ $log->user?->name ?? 'Sistem' }} ({{ $log->user?->role ?? '-' }})
                        </td>
                        <td class="py-3 px-4 whitespace-nowrap text-emerald-300 font-semibold">
                            {{ $log->action }}
                        </td>
                        <td class="py-3 px-4 text-gray-400">
                            {{ $log->details }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500 italic">Belum ada catatan riwayat perubahan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
