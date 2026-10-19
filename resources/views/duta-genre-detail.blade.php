@extends('layouts.public')

@section('title', 'Profil Duta GenRe - ' . $duta['name'])

@section('content')
<div class="relative min-h-screen py-16 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <!-- Ambient Backdrop Glow -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/4 right-1/3 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 left-1/4 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-5xl mx-auto relative z-10 space-y-10">
        <!-- Navigation Breadcrumb -->
        <div>
            <a href="{{ url('/duta-genre') }}" class="inline-flex items-center gap-2 text-xs font-bold text-emerald-400 hover:text-emerald-300 transition-colors bg-white/5 hover:bg-white/10 px-4 py-2 rounded-xl border border-white/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                <span>Kembali ke Daftar Duta GenRe</span>
            </a>
        </div>

        <!-- Hero Card Profile -->
        <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-10 shadow-2xl flex flex-col md:flex-row gap-8 items-center md:items-start">
            <!-- Photo Frame -->
            <div class="w-full sm:w-72 md:w-80 shrink-0 aspect-[4/5] rounded-2xl overflow-hidden bg-slate-900 border border-emerald-500/30 shadow-xl relative group">
                <img src="{{ str_starts_with($duta['photo'] ?? $duta['image'], '/') ? ($duta['photo'] ?? $duta['image']) : asset('images/' . $duta['image']) }}" 
                     alt="{{ $duta['name'] }}" 
                     class="w-full h-full object-cover"
                     onerror="this.src='/images/all_genre.jpeg'">
                <div class="absolute inset-0 bg-gradient-to-t from-[#0B1221]/90 via-transparent to-transparent"></div>
                <span class="absolute bottom-4 left-4 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-emerald-500 text-white shadow-lg">
                    {{ $duta['badge'] ?? 'Duta GenRe' }}
                </span>
            </div>

            <!-- Profile Info -->
            <div class="flex-1 space-y-4 text-center md:text-left">
                <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">
                    {{ $duta['role'] }}
                </span>
                <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                    {{ $duta['name'] }}
                </h1>
                <p class="text-sm font-semibold text-cyan-300">
                    Fokus Utama: {{ $duta['focus'] }}
                </p>
                <p class="text-gray-300 text-sm leading-relaxed">
                    {{ $duta['summary'] }}
                </p>

                @if(!empty($duta['quote']))
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 italic text-xs sm:text-sm text-emerald-200/90 leading-relaxed">
                    "{{ $duta['quote'] }}"
                </div>
                @endif
            </div>
        </div>

        <!-- 3 Core Sections (Biografi, Prestasi, Pengalaman) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- 1. Biografi & Peran Duta -->
            <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 space-y-4">
                <div class="flex items-center gap-2.5 text-emerald-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <h2 class="text-base font-bold text-white uppercase tracking-wider">{{ 'Biografi & Peran Duta' }}</h2>
                </div>
                <p class="text-xs text-gray-300 leading-relaxed">
                    {{ $duta['bio'] }}
                </p>
            </div>

            <!-- 2. Prestasi & Capaian -->
            <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 space-y-4">
                <div class="flex items-center gap-2.5 text-amber-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    <h2 class="text-base font-bold text-white uppercase tracking-wider">{{ 'Prestasi & Capaian' }}</h2>
                </div>
                <ul class="space-y-2.5 text-xs text-gray-300">
                    @foreach($duta['achievements'] ?? [] as $ach)
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0 mt-1.5"></span>
                        <span>{{ $ach }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- 3. Pengalaman & Rekam Jejak -->
            <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 space-y-4">
                <div class="flex items-center gap-2.5 text-cyan-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <h2 class="text-base font-bold text-white uppercase tracking-wider">{{ 'Pengalaman & Rekam Jejak' }}</h2>
                </div>
                <ul class="space-y-2.5 text-xs text-gray-300">
                    @foreach($duta['experiences'] ?? [] as $exp)
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 shrink-0 mt-1.5"></span>
                        <span>{{ $exp }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Kontribusi & Aksi Nyata -->
        @if(!empty($duta['contributions']))
        <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 space-y-4">
            <h3 class="text-sm font-bold text-emerald-400 uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <span>Aksi Nyata & Kontribusi di Sekolah</span>
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-gray-300">
                @foreach($duta['contributions'] as $con)
                <div class="p-3 rounded-xl bg-white/[0.02] border border-white/5 flex items-center gap-2.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                    <span>{{ $con }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
