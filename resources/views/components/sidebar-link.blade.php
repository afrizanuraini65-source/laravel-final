@props(['href', 'active' => false])

<a href="{{ $href }}" class="flex items-center px-4 py-2.5 rounded-xl font-medium transition-all duration-300 group {{ $active ? 'bg-gradient-to-r from-cyan-500/20 to-transparent text-cyan-400 border-l-2 border-cyan-400 shadow-[inset_20px_0_30px_-15px_rgba(6,182,212,0.3)]' : 'text-gray-400 hover:bg-cyan-500/5 hover:text-cyan-300 border-l-2 border-transparent hover:border-cyan-500/50' }}">
    @if(isset($icon))
    <span class="mr-3 transition-transform duration-300 {{ !$active ? 'group-hover:scale-110 group-hover:text-cyan-300' : 'drop-shadow-[0_0_8px_rgba(6,182,212,0.8)]' }}">
        {{ $icon }}
    </span>
    @endif
    <span class="{{ !$active ? 'group-hover:translate-x-1 transition-transform duration-300' : 'drop-shadow-[0_0_5px_rgba(6,182,212,0.5)]' }}">
        {{ $slot }}
    </span>
</a>
