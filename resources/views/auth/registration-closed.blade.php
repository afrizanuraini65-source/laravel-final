@extends('layouts.public')

@section('title', 'Pendaftaran Akun Ditutup - PIK-R SKAKMATT')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-[#0B1221]/90 backdrop-blur-2xl p-8 sm:p-10 rounded-3xl border border-white/10 shadow-2xl text-center space-y-6">
        <div class="w-16 h-16 rounded-2xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center mx-auto text-amber-400">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>

        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30 mb-3">
                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                Pendaftaran Akun Ditutup
            </span>
            <h2 class="text-2xl font-black text-white tracking-tight">
                Pendaftaran Belum Dibuka
            </h2>
            <p class="text-sm text-gray-300 mt-2 leading-relaxed">
                Mohon maaf, pendaftaran akun baru siswa saat ini sedang ditutup atau belum memasuki jadwal yang ditentukan oleh pengurus PIK-R SKAKMATT.
            </p>
        </div>

        @if($settings->account_registration_start || $settings->account_registration_end)
        <div class="p-4 rounded-2xl bg-white/5 border border-white/10 text-left text-xs text-gray-300 space-y-1.5">
            <div class="font-bold text-amber-300 uppercase tracking-wider text-[10px]">Jadwal Pendaftaran Akun:</div>
            @if($settings->account_registration_start)
            <div>Mulai: <span class="text-white font-medium">{{ $settings->account_registration_start->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span></div>
            @endif
            @if($settings->account_registration_end)
            <div>Berakhir: <span class="text-white font-medium">{{ $settings->account_registration_end->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span></div>
            @endif
        </div>
        @else
        <div class="p-4 rounded-2xl bg-white/5 border border-white/10 text-xs text-gray-400">
            Informasi pembukaan periode pendaftaran selanjutnya akan diumumkan secara resmi oleh pengurus melalui media sosial dan mading sekolah.
        </div>
        @endif

        <div class="pt-4 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('login') }}" class="flex-1 py-3 px-4 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-emerald-900/40 transition-all text-center">
                Masuk ke Akun
            </a>
            <a href="{{ url('/') }}" class="flex-1 py-3 px-4 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white font-semibold text-xs border border-white/10 transition-colors text-center">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
