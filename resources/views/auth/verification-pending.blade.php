@extends('layouts.guest')

@section('title', 'Status Verifikasi Akun')

@section('content')
<div class="text-center mb-6">
    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl flex items-center justify-center {{ Auth::user()->status_verifikasi === 'REJECTED' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-amber-500/20 text-amber-400 border border-amber-500/30' }} shadow-[0_0_20px_rgba(245,158,11,0.2)]">
        @if(Auth::user()->status_verifikasi === 'REJECTED')
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        @else
            <svg class="w-8 h-8 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        @endif
    </div>

    @if(Auth::user()->status_verifikasi === 'REJECTED')
        <h2 class="text-2xl font-black text-white tracking-tight">Pendaftaran Ditolak</h2>
        <p class="text-red-400/90 text-sm mt-1">Pengajuan akun belum dapat disetujui oleh pengurus</p>
    @else
        <h2 class="text-2xl font-black text-white tracking-tight">Menunggu Verifikasi Pengurus</h2>
        <p class="text-gray-400 text-sm mt-1">Data Anda sedang ditinjau oleh Ketua & Sekretaris PIK-R SKAKMATT</p>
    @endif
</div>

<!-- Informasi Status Card -->
<div class="bg-black/30 border border-white/10 rounded-2xl p-5 mb-6 backdrop-blur-md space-y-4 shadow-xl">
    <!-- Status Badge -->
    <div class="flex items-center justify-between border-b border-white/5 pb-3">
        <span class="text-xs uppercase font-bold tracking-wider text-gray-400">Status Akun</span>
        @if(Auth::user()->status_verifikasi === 'REJECTED')
            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-red-500/20 text-red-300 border border-red-500/30 shadow-[0_0_10px_rgba(239,68,68,0.2)]">
                DITOLAK
            </span>
        @else
            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-amber-500/20 text-amber-300 border border-amber-500/30 shadow-[0_0_10px_rgba(245,158,11,0.2)] flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                MENUNGGU PERSETUJUAN
            </span>
        @endif
    </div>

    <!-- Smart Matching Info -->
    @php
        $cleanName = strtolower(trim(Auth::user()->name));
        $master = \App\Models\MasterMember::where('user_id', Auth::id())
            ->orWhereRaw('LOWER(TRIM(name)) = ?', [$cleanName])
            ->orWhere('name', 'LIKE', '%' . trim(Auth::user()->name) . '%')
            ->first();
    @endphp

    @if(Auth::user()->status_verifikasi === 'REJECTED')
        <div class="p-3.5 bg-red-500/10 border border-red-500/20 rounded-xl text-xs text-red-300">
            <span class="font-bold block mb-1">Alasan Penolakan:</span>
            {{ Auth::user()->rejection_reason ?: 'Data tidak sesuai dengan persyaratan keanggotaan PIK-R SKAKMATT.' }}
        </div>
    @else
        @if(Auth::user()->match_status === 'TERVERIFIKASI')
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-xs text-emerald-200 space-y-2">
                <div class="flex items-center gap-2 text-emerald-400 font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Data Siswa Terverifikasi Otomatis</span>
                </div>
                <div class="text-[11px] text-emerald-300/90 leading-relaxed">
                    Identitas Anda cocok dengan basis data resmi keanggotaan PIK-R SKAKMATT:
                </div>
                <div class="bg-black/30 p-2.5 rounded-lg border border-emerald-500/20 text-[11px] space-y-1">
                    <div class="flex justify-between"><span class="text-gray-400">Nama di Database:</span> <span class="font-bold text-white">{{ Auth::user()->matched_name ?: Auth::user()->name }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-400">Kelas di Database:</span> <span class="font-bold text-emerald-300">{{ Auth::user()->matched_kelas ?: Auth::user()->kelas }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-400">Status Keanggotaan:</span> <span class="font-bold text-emerald-400">Terverifikasi</span></div>
                    <div class="flex justify-between"><span class="text-gray-400">Status Akun:</span> <span class="font-bold text-amber-300">Menunggu Approval Admin</span></div>
                </div>
            </div>
        @elseif(Auth::user()->match_status === 'MENUNGGU_VERIFIKASI_ANGGOTA')
            <div class="p-3.5 bg-amber-500/10 border border-amber-500/30 rounded-xl text-xs text-amber-200 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <span class="font-bold block text-amber-300">Keanggotaan Menunggu Verifikasi Pengurus</span>
                    <p class="text-amber-200/80 text-[11px] mt-0.5">Permohonan pendaftaran anggota Anda telah masuk, namun pengurus belum menyetujuinya. Akun login akan diproses setelah pendaftaran anggota disetujui.</p>
                </div>
            </div>
        @elseif(Auth::user()->match_status === 'TIDAK_DITEMUKAN')
            <div class="p-3.5 bg-rose-500/10 border border-rose-500/30 rounded-xl text-xs text-rose-200 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div>
                    <span class="font-bold block text-rose-300">Data Keanggotaan Belum Ditemukan</span>
                    <p class="text-rose-200/80 text-[11px] mt-0.5">Data pendaftaran anggota belum ditemukan atau belum memenuhi syarat. Pastikan Anda telah mengisi formulir "Daftar Menjadi Anggota" terlebih dahulu.</p>
                </div>
            </div>
        @elseif(Auth::user()->match_status === 'AMBIGU' || Auth::user()->match_status === 'SUDAH_TERHUBUNG')
            <div class="p-3.5 bg-purple-500/10 border border-purple-500/30 rounded-xl text-xs text-purple-200 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-purple-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <span class="font-bold block text-purple-300">Pemeriksaan Khusus Admin Diperlukan</span>
                    <p class="text-purple-200/80 text-[11px] mt-0.5">{{ Auth::user()->match_notes ?: 'Data pendaftaran Anda ditandai untuk pemeriksaan manual oleh pengurus/admin.' }}</p>
                </div>
            </div>
        @else
            <div class="p-3.5 bg-cyan-500/10 border border-cyan-500/20 rounded-xl text-xs text-cyan-300 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-cyan-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <span class="font-bold">Pemeriksaan Pengurus</span>
                    <p class="text-cyan-400/80 text-[11px] mt-0.5">Admin dan Pengurus PIK-R SKAKMATT sedang meninjau kelengkapan akun Anda.</p>
                </div>
            </div>
        @endif
    @endif

    <!-- Data Diri Siswa -->
    <div class="space-y-2 text-xs pt-1">
        <div class="flex justify-between py-1 border-b border-white/5">
            <span class="text-gray-400">Nama Lengkap</span>
            <span class="font-semibold text-white text-right">{{ Auth::user()->name }}</span>
        </div>
        <div class="flex justify-between py-1 border-b border-white/5">
            <span class="text-gray-400">Kelas</span>
            <span class="font-semibold text-cyan-300 text-right">{{ Auth::user()->kelas ?: '-' }}</span>
        </div>
        <div class="flex justify-between py-1 border-b border-white/5">
            <span class="text-gray-400">Tempat, Tgl Lahir</span>
            <span class="font-semibold text-white text-right">
                {{ Auth::user()->birth_place ?: '-' }}, 
                {{ Auth::user()->birth_date ? \Carbon\Carbon::parse(Auth::user()->birth_date)->format('d M Y') : '-' }}
            </span>
        </div>
        <div class="flex justify-between py-1 border-b border-white/5">
            <span class="text-gray-400">WhatsApp / HP</span>
            <span class="font-semibold text-white text-right">{{ Auth::user()->phone ?: '-' }}</span>
        </div>
        <div class="flex justify-between py-1">
            <span class="text-gray-400">Email</span>
            <span class="font-semibold text-gray-300 text-right">{{ Auth::user()->email }}</span>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="space-y-3">
    <a 
        href="{{ route('verification.pending') }}" 
        class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-cyan-500 to-emerald-600 hover:from-cyan-400 hover:to-emerald-500 text-white font-bold py-3 px-4 rounded-xl text-sm transition-all duration-300 shadow-[0_0_15px_rgba(6,182,212,0.3)] hover:shadow-[0_0_25px_rgba(6,182,212,0.5)] transform hover:-translate-y-0.5 active:translate-y-0 text-center"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
        Muat Ulang / Periksa Status
    </a>

    @php
        $rawWa = env('WHATSAPP_ADMIN_NUMBER', '6281234567890');
        $cleanWa = preg_replace('/[^0-9]/', '', (string)$rawWa);
        if (str_starts_with($cleanWa, '0')) {
            $cleanWa = '62' . substr($cleanWa, 1);
        }
    @endphp
    <a 
        href="https://wa.me/{{ $cleanWa }}?text=Halo%20Sekretariat%20PIK-R%20SKAKMATT,%20saya%20sudah%20mendaftar%20akun%20dengan%20nama%20{{ urlencode(Auth::user()->name) }}%20({{ urlencode(Auth::user()->kelas) }}),%20mohon%20bantuannya%20untuk%20verifikasi." 
        target="_blank"
        class="w-full inline-flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 text-emerald-300 hover:text-emerald-200 border border-emerald-500/30 font-semibold py-2.5 px-4 rounded-xl text-xs transition-all duration-300 text-center"
    >
        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
        Hubungi Pengurus via WhatsApp
    </a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs text-gray-400 hover:text-white hover:bg-white/5 transition-all duration-300 text-center font-medium">
            Keluar dari Akun (Logout)
        </button>
    </form>
</div>
@endsection
