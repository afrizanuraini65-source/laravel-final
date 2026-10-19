@extends('layouts.public')

@section('title', $article['title'] . ' - PIK-R SKAKMATT')

@section('content')
<!-- Detail Header Section -->
<section class="relative pt-24 pb-12 border-b border-emerald-500/10">
    <div class="absolute inset-0 bg-pikr-dark overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-pikr-primary/15 blur-[120px] rounded-full translate-x-1/3 -translate-y-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-emerald-600/10 blur-[100px] rounded-full -translate-x-1/3 translate-y-1/3"></div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Back Navigation Button -->
        <a href="{{ url('/informasi') }}" 
           class="inline-flex items-center gap-2 text-xs font-bold text-pikr-accent uppercase tracking-widest bg-white/5 hover:bg-white/10 border border-white/10 px-4 py-2 rounded-full transition-all duration-300 mb-8 hover:-translate-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali ke Pusat Informasi</span>
        </a>

        <!-- Category Badge -->
        <div class="mb-4">
            <span class="bg-pikr-primary/20 text-pikr-accent border border-pikr-primary/30 text-xs font-extrabold px-3.5 py-1.5 rounded-full uppercase tracking-wider">
                {{ $article['category'] }}
            </span>
        </div>

        <!-- Article Main Title -->
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white leading-tight tracking-tight mb-6 drop-shadow-sm">
            {{ $article['title'] }}
        </h1>

        <!-- Author Tag (NO DATE!) -->
        <div class="flex items-center gap-3 text-sm text-gray-300 font-medium pb-4 border-b border-white/10">
            <div class="w-8 h-8 rounded-full bg-pikr-primary/20 border border-pikr-primary/30 flex items-center justify-center text-pikr-accent font-bold text-xs">
                SKM
            </div>
            <span>{{ $article['author'] }}</span>
        </div>
    </div>
</section>

<!-- Article Main Content Area -->
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-28 relative z-10">
    <!-- Featured Image Box -->
    <div class="relative w-full h-[280px] sm:h-[400px] md:h-[480px] rounded-3xl overflow-hidden shadow-2xl border border-white/15 mb-12 bg-slate-900">
        <img src="{{ asset('images/' . $article['image']) }}" 
             alt="{{ $article['title'] }}" 
             class="w-full h-full object-cover"
             onerror="this.src='/images/placeholder.png'">
        <div class="absolute inset-0 bg-gradient-to-t from-pikr-background/80 via-transparent to-transparent opacity-60"></div>
    </div>

    <!-- Article Body Content Card -->
    <div class="bg-pikr-darkgreen/80 backdrop-blur-md rounded-3xl p-6 sm:p-10 border border-white/10 shadow-xl mb-16">
        <div class="prose prose-invert lg:prose-lg max-w-none text-gray-200 leading-relaxed font-sans space-y-6">
            <p class="text-lg sm:text-xl text-teal-100 font-light leading-relaxed border-l-4 border-pikr-primary pl-4 py-1 bg-white/5 rounded-r-xl">
                {{ $article['summary'] }}
            </p>

            <div class="text-base sm:text-lg text-gray-200 leading-relaxed space-y-4 pt-4">
                {{ $article['content'] }}
            </div>
        </div>
    </div>

    <!-- Related Articles / Other Topics Section -->
    @if(isset($otherArticles) && count($otherArticles) > 0)
    <div class="border-t border-white/10 pt-12">
        <h3 class="text-xl font-bold text-white mb-8 tracking-tight flex items-center gap-3">
            <span class="w-2 h-6 bg-pikr-primary rounded-full inline-block"></span>
            <span>Materi Informasi Lainnya</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach(array_slice($otherArticles, 0, 4) as $item)
            <a href="{{ url('/informasi/' . $item['slug']) }}" 
               class="group bg-pikr-darkgreen/60 hover:bg-pikr-darkgreen/90 backdrop-blur-md p-5 rounded-2xl border border-white/10 hover:border-pikr-primary/40 transition-all duration-300 flex items-start gap-4 shadow-lg hover:-translate-y-1">
                <div class="w-20 h-20 rounded-xl overflow-hidden shrink-0 bg-slate-900 border border-white/10">
                    <img src="{{ asset('images/' . $item['image']) }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="flex flex-col justify-between h-full">
                    <span class="text-[10px] font-bold text-pikr-accent uppercase tracking-wider mb-1">{{ $item['category'] }}</span>
                    <h4 class="text-sm font-bold text-white group-hover:text-pikr-accent transition-colors line-clamp-2 leading-snug">
                        {{ $item['title'] }}
                    </h4>
                    <span class="text-xs text-gray-400 mt-2 font-medium">Baca Selengkapnya &rarr;</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</section>
@endsection
