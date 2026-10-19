@props(['title', 'value', 'color' => 'cyan', 'valueId' => null])

<div {{ $attributes->merge(['class' => 'bg-[#121B2D]/80 backdrop-blur-xl rounded-3xl p-6 shadow-[0_10px_40px_rgba(0,0,0,0.5)] border border-'.$color.'-500/20 transition-all duration-500 hover:shadow-[0_15px_40px_rgba(0,0,0,0.8)] hover:shadow-'.$color.'-500/20 hover:border-'.$color.'-400 hover:-translate-y-2 group relative overflow-hidden']) }}>
    <div class="absolute top-0 right-0 w-32 h-32 bg-{{ $color }}-500/10 rounded-bl-full -mr-16 -mt-16 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
    <div class="absolute bottom-0 left-0 w-24 h-24 bg-violet-500/10 rounded-tr-full -ml-12 -mb-12 transition-transform duration-700 group-hover:scale-150 blur-2xl"></div>
    
    <div class="flex justify-between items-start relative z-10">
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 group-hover:text-{{ $color }}-300 transition-colors">{{ $title }}</p>
            <h3 @if($valueId) id="{{ $valueId }}" @endif class="text-4xl font-extrabold text-white tracking-tight group-hover:text-{{ $color }}-400 transition-colors drop-shadow-[0_0_10px_rgba(255,255,255,0.2)]">{{ $value }}</h3>
        </div>
        <div class="w-14 h-14 bg-[#070b19] border-2 border-{{ $color }}-500/50 text-{{ $color }}-400 rounded-full flex items-center justify-center shadow-[0_0_15px] shadow-{{ $color }}-500/40 group-hover:bg-{{ $color }}-500 group-hover:text-[#070b19] group-hover:shadow-[0_0_25px] group-hover:shadow-{{ $color }}-500/60 transition-all duration-300 relative">
            <div class="absolute inset-0 rounded-full border border-{{ $color }}-400/20 animate-ping opacity-20"></div>
            {{ $icon ?? '' }}
        </div>
    </div>
</div>
