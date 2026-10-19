@props(['href', 'title', 'description'])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'group bg-[#121B2D]/80 backdrop-blur-xl rounded-3xl p-6 shadow-[0_10px_30px_rgba(0,0,0,0.5)] border border-cyan-500/10 hover:shadow-[0_15px_40px_rgba(6,182,212,0.15)] hover:border-cyan-400/80 hover:-translate-y-2 transition-all duration-500 flex flex-col items-center text-center relative overflow-hidden']) }}>
    <div class="absolute inset-0 bg-gradient-to-b from-cyan-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
    <div class="w-16 h-16 bg-[#070b19] border border-cyan-500/30 group-hover:border-cyan-400 text-cyan-600/50 group-hover:text-cyan-400 rounded-full flex items-center justify-center mb-4 transition-all duration-500 group-hover:shadow-[0_0_20px_rgba(6,182,212,0.4)] relative z-10">
        {{ $icon ?? '' }}
    </div>
    <h4 class="font-bold text-gray-200 mb-2 group-hover:text-cyan-300 transition-colors drop-shadow-[0_0_5px_rgba(255,255,255,0.1)] relative z-10">{{ $title }}</h4>
    <p class="text-sm text-gray-400 group-hover:text-gray-300 transition-colors relative z-10">{{ $description }}</p>
</a>
