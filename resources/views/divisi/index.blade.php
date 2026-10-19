@extends('layouts.admin')

@section('title', 'Divisi Organisasi - PIK-R SKAKMATT')

@section('header', 'Divisi Organisasi')

@section('content')
<div class="px-6 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Manajemen <span class="text-teal-400">Divisi</span></h1>
            <p class="text-slate-400 mt-2">Daftar Divisi dan Program Kerja PIK-R SKAKMATT Periode 2025/2026</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
            <!-- Kegiatan -->
            <a href="{{ route('divisi.show', 'kegiatan') }}" class="block relative group rounded-3xl overflow-hidden bg-slate-900/50 border border-slate-700 hover:border-emerald-500/50 transition-all duration-500 shadow-xl hover:shadow-[0_0_30px_rgba(16,185,129,0.15)] flex flex-col h-full">
                <div class="h-48 overflow-hidden relative shrink-0">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent z-10"></div>
                    <img src="/images/Divisi Kegiatan.jpeg" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Divisi Kegiatan">
                </div>
                <div class="p-6 relative z-20 -mt-10 flex-1 flex flex-col">
                    <div class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs font-bold px-3 py-1 rounded-full inline-block self-start mb-3">DIVISI</div>
                    <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-emerald-400 transition-colors">Kegiatan</h3>
                    <div class="space-y-2 mb-6 flex-1">
                        <p class="text-sm flex flex-col"><span class="text-slate-400 text-xs uppercase tracking-wider mb-0.5">Koordinator</span><span class="text-slate-200 font-medium">Salsa Friscila Putri R</span></p>
                        <p class="text-sm flex flex-col mt-2"><span class="text-slate-400 text-xs uppercase tracking-wider mb-0.5">Sekretaris</span><span class="text-slate-200 font-medium">Anggun Syaikhan Zalfa N</span></p>
                    </div>
                    <div class="flex items-center text-sm font-bold text-emerald-400 group-hover:text-emerald-300 transition-colors mt-auto">
                        Lihat Data Lengkap <svg class="w-4 h-4 ml-2 group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </div>
                </div>
            </a>

            <!-- Humas -->
            <a href="{{ route('divisi.show', 'humas') }}" class="block relative group rounded-3xl overflow-hidden bg-slate-900/50 border border-slate-700 hover:border-emerald-500/50 transition-all duration-500 shadow-xl hover:shadow-[0_0_30px_rgba(16,185,129,0.15)] flex flex-col h-full">
                <div class="h-48 overflow-hidden relative shrink-0">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent z-10"></div>
                    <img src="/images/Divisi Hubungan Masyarakat.jpeg" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Divisi Humas">
                </div>
                <div class="p-6 relative z-20 -mt-10 flex-1 flex flex-col">
                    <div class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs font-bold px-3 py-1 rounded-full inline-block self-start mb-3">DIVISI</div>
                    <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-emerald-400 transition-colors">Humas</h3>
                    <div class="space-y-2 mb-6 flex-1">
                        <p class="text-sm flex flex-col"><span class="text-slate-400 text-xs uppercase tracking-wider mb-0.5">Koordinator</span><span class="text-slate-200 font-medium">Sekarwangi Putri</span></p>
                        <p class="text-sm flex flex-col mt-2"><span class="text-slate-400 text-xs uppercase tracking-wider mb-0.5">Sekretaris</span><span class="text-slate-200 font-medium">Fadila Atut Khosiah</span></p>
                    </div>
                    <div class="flex items-center text-sm font-bold text-emerald-400 group-hover:text-emerald-300 transition-colors mt-auto">
                        Lihat Data Lengkap <svg class="w-4 h-4 ml-2 group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </div>
                </div>
            </a>

            <!-- PDD -->
            <a href="{{ route('divisi.show', 'pdd') }}" class="block relative group rounded-3xl overflow-hidden bg-slate-900/50 border border-slate-700 hover:border-emerald-500/50 transition-all duration-500 shadow-xl hover:shadow-[0_0_30px_rgba(16,185,129,0.15)] flex flex-col h-full">
                <div class="h-48 overflow-hidden relative shrink-0">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent z-10"></div>
                    <img src="/images/Divisi Publikasi Dokumentasi & Desain.jpeg" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Divisi PDD">
                </div>
                <div class="p-6 relative z-20 -mt-10 flex-1 flex flex-col">
                    <div class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs font-bold px-3 py-1 rounded-full inline-block self-start mb-3">DIVISI</div>
                    <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-emerald-400 transition-colors">PDD</h3>
                    <div class="space-y-2 mb-6 flex-1">
                        <p class="text-sm flex flex-col"><span class="text-slate-400 text-xs uppercase tracking-wider mb-0.5">Koordinator</span><span class="text-slate-200 font-medium">Hanumara Arya Pramadi</span></p>
                        <p class="text-sm flex flex-col mt-2"><span class="text-slate-400 text-xs uppercase tracking-wider mb-0.5">Sekretaris</span><span class="text-slate-200 font-medium">Aqiilah Mufiida Zahraa</span></p>
                    </div>
                    <div class="flex items-center text-sm font-bold text-emerald-400 group-hover:text-emerald-300 transition-colors mt-auto">
                        Lihat Data Lengkap <svg class="w-4 h-4 ml-2 group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </div>
                </div>
            </a>
        </div>

        <!-- Bottom Row (Centered) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 max-w-4xl mx-auto gap-8">
            <!-- PKWU -->
            <a href="{{ route('divisi.show', 'pkwu') }}" class="block relative group rounded-3xl overflow-hidden bg-slate-900/50 border border-slate-700 hover:border-emerald-500/50 transition-all duration-500 shadow-xl hover:shadow-[0_0_30px_rgba(16,185,129,0.15)] flex flex-col h-full">
                <div class="h-48 overflow-hidden relative shrink-0">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent z-10"></div>
                    <img src="/images/Divisi Prakarya dan Kewirausahaan.jpeg" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Divisi PKWU">
                </div>
                <div class="p-6 relative z-20 -mt-10 flex-1 flex flex-col">
                    <div class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs font-bold px-3 py-1 rounded-full inline-block self-start mb-3">DIVISI</div>
                    <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-emerald-400 transition-colors">PKWU</h3>
                    <div class="space-y-2 mb-6 flex-1">
                        <p class="text-sm flex flex-col"><span class="text-slate-400 text-xs uppercase tracking-wider mb-0.5">Koordinator</span><span class="text-slate-200 font-medium">Syarifah Tazqiah</span></p>
                        <p class="text-sm flex flex-col mt-2"><span class="text-slate-400 text-xs uppercase tracking-wider mb-0.5">Sekretaris</span><span class="text-slate-200 font-medium">Rovikoh</span></p>
                    </div>
                    <div class="flex items-center text-sm font-bold text-emerald-400 group-hover:text-emerald-300 transition-colors mt-auto">
                        Lihat Data Lengkap <svg class="w-4 h-4 ml-2 group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </div>
                </div>
            </a>

            <!-- Konselor Sebaya -->
            <a href="{{ route('divisi.show', 'konselor-sebaya') }}" class="block relative group rounded-3xl overflow-hidden bg-slate-900/50 border border-slate-700 hover:border-emerald-500/50 transition-all duration-500 shadow-xl hover:shadow-[0_0_30px_rgba(16,185,129,0.15)] flex flex-col h-full">
                <div class="h-48 overflow-hidden relative shrink-0">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent z-10"></div>
                    <img src="/images/Divisi Konselor Sebaya.jpeg" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Divisi Konselor Sebaya">
                </div>
                <div class="p-6 relative z-20 -mt-10 flex-1 flex flex-col">
                    <div class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs font-bold px-3 py-1 rounded-full inline-block self-start mb-3">DIVISI</div>
                    <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-emerald-400 transition-colors">Konselor Sebaya</h3>
                    <div class="space-y-2 mb-6 flex-1">
                        <p class="text-sm flex flex-col"><span class="text-slate-400 text-xs uppercase tracking-wider mb-0.5">Koordinator</span><span class="text-slate-200 font-medium">Benaya Iskha Ghifari</span></p>
                        <p class="text-sm flex flex-col mt-2"><span class="text-slate-400 text-xs uppercase tracking-wider mb-0.5">Sekretaris</span><span class="text-slate-200 font-medium">Zein Vies Airinn Nabila Y</span></p>
                    </div>
                    <div class="flex items-center text-sm font-bold text-emerald-400 group-hover:text-emerald-300 transition-colors mt-auto">
                        Lihat Data Lengkap <svg class="w-4 h-4 ml-2 group-hover:translate-x-1.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
