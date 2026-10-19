@props(['role', 'members', 'level' => 'primary'])

@php
    // Level determines the prominence and translucence of the card
    $cardClasses = $level === 'primary' 
        ? 'bg-white/10 backdrop-blur-xl border-t border-l border-white/20 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] hover:shadow-[0_0_30px_rgba(16,185,129,0.3)] hover:border-emerald-400/50'
        : 'bg-white/[0.03] backdrop-blur-lg border-t border-l border-white/10 shadow-[0_4px_24px_0_rgba(0,0,0,0.2)] hover:shadow-[0_0_20px_rgba(20,184,166,0.2)] hover:border-teal-500/30';
        
    $imagePlaceholderClasses = $level === 'primary'
        ? 'border-2 border-emerald-500/40 shadow-[0_0_20px_rgba(16,185,129,0.2)] bg-slate-900/60'
        : 'border border-teal-500/30 shadow-[0_0_15px_rgba(20,184,166,0.15)] bg-slate-900/40';
@endphp

<div x-data="{ currentIndex: 0, count: {{ count($members) }} }" class="{{ $cardClasses }} rounded-[2.5rem] p-6 md:p-8 flex flex-col items-center text-center transition-all duration-700 relative overflow-hidden group w-full h-full">
    
    <!-- Deep Reflective Edge / Soft Green Pulsating Glow -->
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700 rounded-[2.5rem] pointer-events-none mix-blend-overlay"></div>
    @if($level === 'primary')
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/20 blur-[50px] rounded-full pointer-events-none animate-pulse"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-teal-500/20 blur-[60px] rounded-full pointer-events-none"></div>
    @endif

    <!-- Slider Container -->
    <div class="w-full relative flex-1 flex flex-col justify-center z-10">
        <!-- Division / Role Title at the very top -->
        <div class="w-full text-center mb-6 border-b border-white/5 pb-4">
            <h3 class="text-sm md:text-base font-bold text-teal-300 tracking-[0.2em] uppercase drop-shadow-[0_0_10px_rgba(20,184,166,0.5)]">
                {{ $role }}
            </h3>
        </div>
        
        <div class="overflow-hidden">
            <div class="flex transition-transform duration-700 ease-out" :style="`transform: translateX(-${currentIndex * 100}%)`">
                @foreach($members as $idx => $member)
                    <div class="w-full flex-shrink-0 flex flex-col items-center justify-center px-2">
                        <!-- Holographic Photo Frame -->
                        <div class="w-48 h-48 md:w-56 md:h-56 {{ $imagePlaceholderClasses }} rounded-[2rem] mb-6 overflow-hidden relative mx-auto transition-colors duration-500 flex items-center justify-center backdrop-blur-sm group-hover:bg-slate-800/60">
                            <!-- Tech UI Corner Accents -->
                            <div class="absolute top-2 left-2 w-4 h-4 border-t-2 border-l-2 border-emerald-500/50"></div>
                            <div class="absolute top-2 right-2 w-4 h-4 border-t-2 border-r-2 border-emerald-500/50"></div>
                            <div class="absolute bottom-2 left-2 w-4 h-4 border-b-2 border-l-2 border-emerald-500/50"></div>
                            <div class="absolute bottom-2 right-2 w-4 h-4 border-b-2 border-r-2 border-emerald-500/50"></div>
                            
                            @if(isset($member['photo']) && $member['photo'])
                                <img src="{{ $member['photo'] }}" alt="{{ $member['name'] }}" class="w-full h-full object-cover mix-blend-luminosity hover:mix-blend-normal transition-all duration-500" />
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-emerald-100/40">
                                    <svg class="w-12 h-12 mb-3 opacity-50 group-hover:text-emerald-400 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500 drop-shadow-[0_0_10px_rgba(16,185,129,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-[10px] font-medium uppercase tracking-[0.3em] opacity-80 group-hover:text-emerald-300 transition-colors">{{ $member['label'] ?? 'Foto' }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Brushed Metallic Capsule Label -->
                        <span class="text-[10px] md:text-xs font-semibold text-emerald-400 bg-gradient-to-b from-slate-700 to-slate-800 border border-slate-600/60 px-6 py-2 rounded-full mb-5 tracking-[0.2em] uppercase shadow-[inset_0_1px_0_rgba(255,255,255,0.1),0_4px_10px_rgba(0,0,0,0.5)] drop-shadow-[0_0_8px_rgba(16,185,129,0.2)]">
                            {{ $member['jabatan'] ?? $role }}
                        </span>
                        
                        <!-- Futuristic Clean Name -->
                        <h3 class="text-white tracking-wide font-sans {{ strlen($member['name']) > 30 ? 'text-sm md:text-base leading-relaxed font-light text-slate-200' : 'text-xl md:text-2xl font-bold drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)]' }}">
                            {{ $member['name'] }}
                        </h3>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Sleek Glowing Chevron Navigation -->
        @if(count($members) > 1)
            <button @click="currentIndex = currentIndex === 0 ? count - 1 : currentIndex - 1" class="absolute top-1/2 left-0 -translate-y-1/2 -ml-2 w-10 h-10 flex items-center justify-center text-white/50 hover:text-white transition-all opacity-0 group-hover:opacity-100 focus:opacity-100 z-10 hover:scale-110 drop-shadow-[0_0_8px_rgba(255,255,255,0.6)]">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button @click="currentIndex = currentIndex === count - 1 ? 0 : currentIndex + 1" class="absolute top-1/2 right-0 -translate-y-1/2 -mr-2 w-10 h-10 flex items-center justify-center text-white/50 hover:text-white transition-all opacity-0 group-hover:opacity-100 focus:opacity-100 z-10 hover:scale-110 drop-shadow-[0_0_8px_rgba(255,255,255,0.6)]">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"></path></svg>
            </button>
        @endif
    </div>

    <!-- Active Indicator Lines -->
    @if(count($members) > 1)
        <div class="flex items-center justify-center gap-3 mt-8 h-2 z-10">
            @foreach($members as $idx => $member)
                <button 
                    @click="currentIndex = {{ $idx }}"
                    :class="currentIndex === {{ $idx }} ? 'w-8 bg-emerald-400 shadow-[0_0_10px_rgba(16,185,129,0.8)]' : 'w-4 bg-white/20 hover:bg-white/40'"
                    class="h-[3px] transition-all duration-500 rounded-full"
                    aria-label="Go to slide {{ $idx + 1 }}"
                ></button>
            @endforeach
        </div>
    @endif
</div>
