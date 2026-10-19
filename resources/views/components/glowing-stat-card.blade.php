@props(['title', 'value', 'subtitle' => null, 'gradient' => 'from-emerald-600 to-teal-500'])

<div {{ $attributes->merge(['class' => 'rounded-3xl p-6 relative overflow-hidden group hover:-translate-y-2 transition-all duration-500 shadow-[0_10px_40px_rgba(16,185,129,0.3)] hover:shadow-[0_15px_50px_rgba(16,185,129,0.5)] border border-emerald-400/30']) }}>
    <!-- Base Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br {{ $gradient }} opacity-100"></div>
    
    <!-- Energy Waves (CSS generated) -->
    <div class="absolute inset-0 opacity-40 mix-blend-overlay">
        <div class="absolute top-[-50%] left-[-50%] w-[200%] h-[200%] bg-[radial-gradient(ellipse_at_center,rgba(255,255,255,0.3)_0%,transparent_50%)] animate-[spin_10s_linear_infinite]"></div>
        <div class="absolute top-[-50%] left-[-50%] w-[200%] h-[200%] bg-[radial-gradient(ellipse_at_center,rgba(255,255,255,0.2)_0%,transparent_40%)] animate-[spin_15s_linear_infinite_reverse]"></div>
    </div>
    
    <!-- Micro particles -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4IiBoZWlnaHQ9IjgiPgo8Y2lyY2xlIGN4PSI0IiBjeT0iNCIgcj0iMC41IiBmaWxsPSJyZ2JhKDI1NSwyNTUsMjU1LDAuNSkiLz4KPC9zdmc+')] opacity-30 animate-pulse"></div>

    <div class="absolute -top-24 -right-24 w-48 h-48 bg-white/20 blur-[40px] rounded-full group-hover:scale-150 transition-transform duration-1000"></div>
    
    <div class="relative z-10 flex flex-col h-full justify-between">
        <div class="flex justify-between items-start mb-4">
            <div class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-full border border-white/30 flex items-center justify-center text-white shadow-[0_0_15px_rgba(255,255,255,0.2)]">
                {{ $icon ?? '' }}
            </div>
            @if($subtitle)
                <span class="text-xs font-bold text-emerald-50 bg-white/10 px-3 py-1 rounded-full backdrop-blur-sm border border-white/20 shadow-[0_0_10px_rgba(255,255,255,0.1)]">{{ $subtitle }}</span>
            @endif
        </div>
        <div>
            <p class="text-sm font-bold text-emerald-50 mb-1 tracking-wider">{{ $title }}</p>
            <h3 class="text-3xl font-extrabold text-white tracking-tight drop-shadow-[0_0_10px_rgba(255,255,255,0.4)]">{{ $value }}</h3>
        </div>
    </div>
</div>
