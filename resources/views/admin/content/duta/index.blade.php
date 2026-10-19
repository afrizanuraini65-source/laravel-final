@extends('layouts.admin')

@section('title', 'Kelola Profil Duta GenRe - PIK-R SKAKMATT')
@section('header', 'Kelola Profil Duta GenRe')

@section('content')
<div class="space-y-6">

    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white">&times;</button>
    </div>
    @endif

    <!-- Control Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-[#0B1221]/90 backdrop-blur-xl border border-amber-500/30 p-6 rounded-3xl shadow-xl">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-[10px] font-bold uppercase tracking-wider mb-2 border border-amber-500/30">
                Role Model & Tauladan
            </div>
            <h3 class="text-xl font-extrabold text-white">Profil Tiga Duta GenRe Sekolah</h3>
            <p class="text-xs text-gray-400 mt-1">Perbarui biografi, prestasi terverifikasi, pengalaman, aksi nyata, dan pesan inspiratif</p>
        </div>
        <div>
            <a href="{{ url('/duta-genre') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white border border-white/10 text-xs font-semibold transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                <span>Lihat Halaman Publik</span>
            </a>
        </div>
    </div>

    <!-- 3 Duta Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($dutagenres as $slug => $duta)
        <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-amber-500/20 rounded-3xl overflow-hidden shadow-2xl flex flex-col justify-between group hover:border-amber-400/50 transition-all duration-300">
            <div>
                <!-- Photo Container -->
                <div class="relative h-72 w-full overflow-hidden bg-slate-900">
                    <img src="{{ str_starts_with($duta['photo'] ?? $duta['image'], '/') ? ($duta['photo'] ?? $duta['image']) : asset('images/' . $duta['image']) }}" 
                         alt="{{ $duta['name'] }}" 
                         class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500" 
                         onerror="this.src='/images/placeholder.png'">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0B1221] via-transparent to-transparent"></div>
                    <span class="absolute top-3 left-3 bg-black/70 backdrop-blur-md text-amber-300 text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full border border-amber-400/30">
                        {{ $duta['badge'] ?? 'Duta GenRe' }}
                    </span>
                    <div class="absolute bottom-3 left-4 right-4">
                        <h4 class="text-lg font-bold text-white">{{ $duta['name'] }}</h4>
                        <p class="text-xs text-amber-300 font-medium">{{ $duta['role'] }}</p>
                    </div>
                </div>

                <!-- Info Body -->
                <div class="p-6 space-y-4">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Fokus Advokasi:</span>
                        <p class="text-xs text-gray-200 font-semibold bg-white/5 p-2 rounded-xl border border-white/5">
                            {{ $duta['focus'] }}
                        </p>
                    </div>

                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Pesan Inspiratif:</span>
                        <p class="text-xs text-gray-300 italic line-clamp-2">
                            "{{ $duta['quote'] ?? '-' }}"
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-2 pt-2 border-t border-white/10 text-center">
                        <div class="p-2 rounded-xl bg-white/[0.02]">
                            <span class="text-[10px] text-gray-400 block">Kontribusi</span>
                            <span class="text-xs font-bold text-emerald-400">{{ count($duta['contributions'] ?? []) }}</span>
                        </div>
                        <div class="p-2 rounded-xl bg-white/[0.02]">
                            <span class="text-[10px] text-gray-400 block">Prestasi</span>
                            <span class="text-xs font-bold text-amber-400">{{ count($duta['achievements'] ?? []) }}</span>
                        </div>
                        <div class="p-2 rounded-xl bg-white/[0.02]">
                            <span class="text-[10px] text-gray-400 block">Pengalaman</span>
                            <span class="text-xs font-bold text-cyan-400">{{ count($duta['experiences'] ?? []) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="p-6 pt-0 flex gap-3">
                <a href="{{ route('admin.duta-genre.edit', $slug) }}" 
                   class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-300 hover:to-yellow-400 text-slate-950 font-black text-xs uppercase tracking-wider text-center transition-all shadow-md">
                    Edit Profil Lengkap
                </a>
                <a href="{{ url('/duta-genre/' . $slug) }}" target="_blank" 
                   class="p-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white border border-white/10 transition-colors" title="Lihat Profil Publik">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
