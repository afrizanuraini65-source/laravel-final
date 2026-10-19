@extends('layouts.public')

@section('title', 'Pusat Informasi & Edukasi - PIK-R SKAKMATT')

@section('content')
<!-- Page Hero Header -->
<section class="relative py-24 border-b border-emerald-500/10">
    <div class="absolute inset-0 bg-pikr-dark overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-pikr-primary/15 blur-[120px] rounded-full translate-x-1/3 -translate-y-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-emerald-600/10 blur-[100px] rounded-full -translate-x-1/3 translate-y-1/3"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center justify-center px-4 py-1.5 mb-6 rounded-full bg-white/5 border border-pikr-accent/20 backdrop-blur-md">
            <span class="text-xs font-bold text-pikr-accent uppercase tracking-widest">Edukasi & Publikasi Remaja</span>
        </div>
        <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 tracking-tight drop-shadow-sm">
            Pusat Informasi & Edukasi
        </h1>
        <p class="text-xl text-gray-300 max-w-2xl mx-auto leading-relaxed font-light">
            Portal materi edukasi remaja, kesehatan reproduksi, perencanaan masa depan, serta life skills PIK-R SKAKMATT SMK Negeri 1 Purwokerto.
        </p>

        @auth
            @hasanyrole('ADMIN|KETUA|WAKIL_KETUA|PEMBINA|PDD|HUMAS')
            <div class="mt-8">
                <a href="{{ route('admin.articles.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg hover:bg-emerald-400 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Kelola Artikel di Panel Admin</span>
                </a>
            </div>
            @endhasanyrole
        @endauth
    </div>
</section>

<!-- Content Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 pb-32" x-data="{ selectedCategory: 'all' }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 border-b border-white/10 pb-6">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Daftar Artikel & Materi Edukasi</h2>
            <p class="text-sm text-gray-400 mt-1">Materi terpercaya seputar Kesehatan Reproduksi, Character Building, GenRe, dan Masa Depan</p>
        </div>
        <span class="text-xs font-semibold px-3.5 py-1.5 bg-pikr-primary/20 text-pikr-accent border border-pikr-primary/30 rounded-full self-start sm:self-auto">
            Total: {{ count($articles) }} Artikel Materi
        </span>
    </div>

    <!-- Filter Buttons -->
    <div class="flex flex-wrap gap-2 mb-10">
        <button @click="selectedCategory = 'all'" 
                :class="selectedCategory === 'all' ? 'bg-emerald-500 text-white shadow-md' : 'bg-white/5 text-gray-300 hover:bg-white/10 border border-white/10'" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
            Semua Topik
        </button>
        <button @click="selectedCategory = 'genre'" 
                :class="selectedCategory === 'genre' ? 'bg-amber-500 text-slate-950 font-black shadow-md' : 'bg-white/5 text-gray-300 hover:bg-white/10 border border-white/10'" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
            Duta GenRe & Organisasi
        </button>
        <button @click="selectedCategory = 'kesehatan'" 
                :class="selectedCategory === 'kesehatan' ? 'bg-emerald-500 text-white shadow-md' : 'bg-white/5 text-gray-300 hover:bg-white/10 border border-white/10'" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
            Kesehatan Reproduksi & Triad KRR
        </button>
        <button @click="selectedCategory = 'lifeskills'" 
                :class="selectedCategory === 'lifeskills' ? 'bg-emerald-500 text-white shadow-md' : 'bg-white/5 text-gray-300 hover:bg-white/10 border border-white/10'" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all">
            Masa Depan & Life Skills
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($articles as $article)
        @php
            $catLower = strtolower($article['category'] ?? '');
            $groupCat = 'all';
            if (str_contains($catLower, 'genre') || str_contains($catLower, 'organisasi') || str_contains($catLower, 'pemilihan')) {
                $groupCat = 'genre';
            } elseif (str_contains($catLower, 'kesehatan') || str_contains($catLower, 'napza') || str_contains($catLower, 'hiv')) {
                $groupCat = 'kesehatan';
            } else {
                $groupCat = 'lifeskills';
            }
        @endphp
        <!-- Article Card -->
        <div x-show="selectedCategory === 'all' || selectedCategory === '{{ $groupCat }}'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="group bg-pikr-darkgreen/80 backdrop-blur-md rounded-3xl overflow-hidden border border-white/10 shadow-xl flex flex-col justify-between transition-all duration-300 hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-emerald-950/40 hover:border-pikr-primary/40">
            <div>
                <!-- Cover Image & Category Container -->
                <div class="relative h-56 w-full overflow-hidden bg-slate-900">
                    <img src="{{ str_starts_with($article['image'], '/') ? $article['image'] : asset('images/' . $article['image']) }}" 
                         alt="{{ $article['title'] }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                         onerror="this.src='/images/placeholder.png'">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-pikr-darkgreen/90 via-transparent to-black/20 opacity-80 group-hover:opacity-60 transition-opacity duration-300"></div>

                    <!-- Category Badge Overlay -->
                    <div class="absolute top-4 left-4 z-20">
                        <span class="bg-pikr-dark/90 backdrop-blur-md text-pikr-accent border border-white/15 text-[10px] font-extrabold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-lg">
                            {{ $article['category'] }}
                        </span>
                    </div>
                </div>

                <!-- Card Content Body -->
                <div class="p-6">
                    <!-- Date & Author Metadata -->
                    <div class="flex items-center gap-2.5 text-xs text-gray-400 mb-3 font-medium">
                        <span class="text-emerald-400 font-semibold">{{ $article['date'] ?? '2026' }}</span>
                        <span>•</span>
                        <span>{{ $article['author'] ?? 'PIK-R SKAKMATT' }}</span>
                    </div>

                    <!-- Article Title -->
                    <h3 class="text-lg font-bold text-white mb-3 leading-snug group-hover:text-pikr-accent transition-colors line-clamp-2">
                        {{ $article['title'] }}
                    </h3>

                    <!-- Article Summary -->
                    <p class="text-gray-300 text-sm line-clamp-3 leading-relaxed font-normal">
                        {{ $article['summary'] ?? ($article['excerpt'] ?? '') }}
                    </p>
                </div>
            </div>

            <!-- Read Article Button -->
            <div class="p-6 pt-0">
                <a href="{{ url('/informasi/' . $article['slug']) }}" 
                   class="w-full bg-white/5 hover:bg-pikr-primary text-white font-semibold py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider text-center transition-all duration-300 flex items-center justify-center gap-2 group/btn border border-white/10 hover:border-pikr-primary">
                    <span>Baca Selengkapnya</span>
                    <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection
