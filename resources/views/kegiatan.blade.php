@extends('layouts.public')

@section('title', 'Kegiatan & Event - PIK-R SKAKMATT')

@section('content')
@php
    $kegiatan = \App\Data\KegiatanData::getAll();
@endphp

<!-- Page Hero Header -->
<section class="relative py-24 border-b border-emerald-500/10">
    <div class="absolute inset-0 bg-pikr-dark overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-pikr-primary/15 blur-[120px] rounded-full translate-x-1/3 -translate-y-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-emerald-600/10 blur-[100px] rounded-full -translate-x-1/3 translate-y-1/3"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center justify-center px-4 py-1.5 mb-6 rounded-full bg-white/5 border border-pikr-accent/20 backdrop-blur-md">
            <span class="text-xs font-bold text-pikr-accent uppercase tracking-widest">Jejak Langkah & Agenda</span>
        </div>
        <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 tracking-tight drop-shadow-sm">
            Kegiatan & Dokumentasi SKAKMATT
        </h1>
        <p class="text-xl text-gray-300 max-w-2xl mx-auto leading-relaxed font-light">
            Dokumentasi aksi nyata, pemilihan duta, sosialisasi edukatif, forum diskusi, serta kebersamaan pengurus PIK-R SMK Negeri 1 Purwokerto.
        </p>

        @auth
            @hasanyrole('ADMIN|KETUA|WAKIL_KETUA|PEMBINA|PDD|HUMAS')
            <div class="mt-8">
                <a href="{{ route('admin.kegiatan.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg hover:bg-emerald-400 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Kelola Kegiatan di Panel Admin</span>
                </a>
            </div>
            @endhasanyrole
        @endauth
    </div>
</section>

<!-- Content Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 pb-32" x-data="{ activeCat: 'all' }">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 border-b border-white/10 pb-6">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Dokumentasi & Agenda Kegiatan</h2>
            <p class="text-sm text-gray-400 mt-1">Daftar rekam jejak program kerja dan kebersamaan seluruh anggota</p>
        </div>
        <span class="text-xs font-semibold px-3.5 py-1.5 bg-pikr-primary/20 text-pikr-accent border border-pikr-primary/30 rounded-full self-start sm:self-auto">
            Total: {{ count($kegiatan) }} Kegiatan Terverifikasi
        </span>
    </div>

    <!-- Filter Buttons -->
    <div class="flex flex-wrap gap-2.5 mb-10">
        <button @click="activeCat = 'all'" 
                :class="activeCat === 'all' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-white/5 text-gray-300 hover:bg-white/10 border border-white/10'" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
            Semua Kegiatan
        </button>
        <button @click="activeCat = 'pildugen'" 
                :class="activeCat === 'pildugen' ? 'bg-amber-500 text-slate-950 font-black shadow-lg shadow-amber-500/20' : 'bg-white/5 text-gray-300 hover:bg-white/10 border border-white/10'" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
            Pemilihan Duta GenRe
        </button>
        <button @click="activeCat = 'edukasi'" 
                :class="activeCat === 'edukasi' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-white/5 text-gray-300 hover:bg-white/10 border border-white/10'" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
            Sosialisasi & Edukasi
        </button>
        <button @click="activeCat = 'konseling'" 
                :class="activeCat === 'konseling' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-white/5 text-gray-300 hover:bg-white/10 border border-white/10'" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
            Konseling & Forum
        </button>
        <button @click="activeCat = 'internal'" 
                :class="activeCat === 'internal' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-white/5 text-gray-300 hover:bg-white/10 border border-white/10'" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
            Kebersamaan & Organisasi
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($kegiatan as $item)
        @php
            $cat = $item['category'] ?? '';
            $filterGroup = 'all';
            if (str_contains(strtolower($cat), 'duta') || str_contains(strtolower($cat), 'pemilihan')) {
                $filterGroup = 'pildugen';
            } elseif (str_contains(strtolower($cat), 'sosialisasi') || str_contains(strtolower($cat), 'edukasi')) {
                $filterGroup = 'edukasi';
            } elseif (str_contains(strtolower($cat), 'konseling') || str_contains(strtolower($cat), 'diskusi')) {
                $filterGroup = 'konseling';
            } else {
                $filterGroup = 'internal';
            }
        @endphp
        <!-- Event Card -->
        <div x-show="activeCat === 'all' || activeCat === '{{ $filterGroup }}'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="group bg-pikr-darkgreen/80 backdrop-blur-md rounded-3xl overflow-hidden border border-white/10 shadow-xl flex flex-col justify-between transition-all duration-300 hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-emerald-950/40 hover:border-pikr-primary/40">
            <div>
                <!-- Image & Date Badge Container -->
                <div class="relative h-52 w-full overflow-hidden bg-slate-900">
                    <img src="{{ str_starts_with($item['image'], '/') ? $item['image'] : asset('images/' . $item['image']) }}" 
                         alt="{{ $item['title'] }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                         onerror="this.src='/images/placeholder.png'">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-pikr-darkgreen/90 via-transparent to-black/20 opacity-80 group-hover:opacity-60 transition-opacity duration-300"></div>

                    <!-- Date Badge Overlay -->
                    <div class="absolute top-4 left-4 bg-pikr-dark/90 backdrop-blur-md border border-white/15 rounded-2xl px-3.5 py-2 flex flex-col items-center justify-center text-center shadow-lg">
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-pikr-accent">{{ $item['month'] ?? 'KEG' }}</span>
                        <span class="text-base font-black leading-tight text-white">{{ $item['day'] ?? '•' }}</span>
                    </div>

                    <!-- Category Badge Overlay -->
                    <div class="absolute top-4 right-4 bg-emerald-500/80 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider border border-white/20">
                        {{ $item['category_label'] ?? ($item['category'] ?? 'Kegiatan') }}
                    </div>
                </div>

                <!-- Card Info Content -->
                <div class="p-6">
                    <div class="flex items-center justify-between gap-2 text-xs text-gray-400 mb-2">
                        <span class="inline-flex items-center gap-1 text-emerald-400 font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            {{ $item['status_label'] ?? 'Terlaksana' }}
                        </span>
                        @if(!empty($item['source']))
                        <span class="text-[10px] text-gray-400 truncate max-w-[150px]" title="{{ $item['source'] }}">{{ $item['source'] }}</span>
                        @endif
                    </div>

                    <h3 class="text-lg font-bold text-white mb-3 leading-snug group-hover:text-pikr-accent transition-colors">
                        {{ $item['title'] }}
                    </h3>

                    @if(!empty($item['desc']))
                    <p class="text-gray-300 text-xs sm:text-sm leading-relaxed mb-4 line-clamp-3">
                        {{ $item['desc'] }}
                    </p>
                    @endif

                    <div class="text-sm text-gray-300 space-y-2 pt-2 border-t border-white/10">
                        <div class="flex items-center gap-2.5 text-xs text-gray-300">
                            <div class="w-6 h-6 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-pikr-accent shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="font-medium">{{ $item['date_display'] }}</span>
                        </div>

                        <div class="flex items-center gap-2.5 text-xs text-gray-300">
                            <div class="w-6 h-6 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-pikr-accent shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <span class="font-medium">{{ $item['location'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 pt-0">
                <a href="{{ url('/#galeri') }}" class="w-full text-center block py-2 rounded-xl bg-white/5 hover:bg-white/10 text-xs font-semibold text-gray-300 hover:text-white border border-white/10 transition-colors">
                    Lihat Galeri Foto &rarr;
                </a>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection
