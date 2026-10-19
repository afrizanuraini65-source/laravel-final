@extends('layouts.public')

@section('title', 'Kenali Duta GenRe - PIK-R SKAKMATT')

@section('content')
<div class="relative min-h-screen py-16 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <!-- Ambient Backdrop Glow -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/4 left-1/3 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/3 right-1/4 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto relative z-10 space-y-12">
        <!-- Hero Header -->
        <div class="text-center max-w-3xl mx-auto space-y-4">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs font-bold uppercase tracking-widest shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Role Model Sebaya SMK Negeri 1 Purwokerto
            </span>
            <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight leading-tight">
                Kenali <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400">Duta GenRe</span> Sekolah
            </h1>
            <p class="text-gray-300 text-sm sm:text-base leading-relaxed">
                Sosok inspiratif generasi muda yang berani melangkah, memelopori gaya hidup sehat remaja, dan membimbing sesama untuk merencanakan masa depan dengan gemilang.
            </p>
        </div>

        <!-- 3 Duta Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($dutagenres as $slug => $duta)
            <div class="group relative bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 hover:border-emerald-500/40 rounded-3xl p-6 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_20px_50px_rgba(16,185,129,0.15)] flex flex-col justify-between">
                <div>
                    <!-- Photo Frame -->
                    <div class="relative w-full aspect-[4/5] rounded-2xl overflow-hidden mb-6 bg-slate-900 border border-white/10 group-hover:border-emerald-500/30 transition-colors">
                        <img src="{{ str_starts_with($duta['photo'] ?? $duta['image'], '/') ? ($duta['photo'] ?? $duta['image']) : asset('images/' . $duta['image']) }}" 
                             alt="{{ $duta['name'] }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             onerror="this.src='/images/all_genre.jpeg'">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0B1221] via-transparent to-transparent opacity-80"></div>
                        <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/80 text-white backdrop-blur-md shadow-md">
                            {{ $duta['badge'] ?? 'Duta GenRe' }}
                        </span>
                    </div>

                    <!-- Meta & Name -->
                    <span class="text-xs font-bold text-emerald-400 tracking-wide uppercase">{{ $duta['role'] }}</span>
                    <h2 class="text-xl font-black text-white mt-1 group-hover:text-emerald-300 transition-colors">{{ $duta['name'] }}</h2>
                    <p class="text-xs text-cyan-200/80 font-medium mt-1 mb-3">{{ $duta['focus'] }}</p>
                    <p class="text-xs text-gray-300 leading-relaxed line-clamp-3 mb-6">{{ $duta['summary'] }}</p>
                </div>

                <!-- Footer Link -->
                <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                    <span class="text-xs text-gray-400">Profil Lengkap & Prestasi</span>
                    <a href="{{ url('/duta-genre/' . $slug) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 border border-emerald-500/30 text-emerald-300 hover:text-white text-xs font-bold transition-all group-hover:shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                        <span>Lihat Profil</span>
                        <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
