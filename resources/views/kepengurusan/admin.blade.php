@extends('layouts.admin')

@section('title', 'Manajemen Kepengurusan - PIK-R SKAKMATT')

@section('header', 'Manajemen Kepengurusan')

@section('content')
<!-- Menerapkan background custom HANYA untuk section ini tanpa merusak sidebar admin yang dark mode -->
<div class="rounded-3xl overflow-hidden shadow-2xl bg-slate-900/40 border border-slate-700/50 backdrop-blur-md mb-8">
    <div class="max-w-7xl mx-auto p-6 md:p-10 space-y-8 relative">
        
        <!-- Header & Stats -->
        <div class="flex flex-col lg:flex-row gap-6 justify-between items-start lg:items-center relative z-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight drop-shadow-md">Data <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">Kepengurusan</span></h1>
                <p class="text-slate-400 mt-2">Daftar struktur {{ $totalPengurus }} pengurus SK PIK-R SKAKMATT 2025/2026.</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 w-full lg:w-auto">
                <div class="bg-slate-800/60 rounded-2xl p-4 shadow-[0_0_15px_rgba(16,185,129,0.1)] border border-slate-700 hover:border-emerald-500/50 transition-all flex flex-col items-center justify-center text-center group">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider group-hover:text-emerald-300 transition-colors">Total</span>
                    <span class="text-3xl font-black text-white group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-emerald-400 group-hover:to-teal-300 transition-all">{{ $totalPengurus }}</span>
                </div>
                <div class="bg-slate-800/60 rounded-2xl p-4 shadow-[0_0_15px_rgba(245,158,11,0.1)] border border-slate-700 hover:border-amber-500/50 transition-all flex flex-col items-center justify-center text-center group">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider group-hover:text-amber-300 transition-colors">Pengurus Inti</span>
                    <span class="text-3xl font-black text-white group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-amber-400 group-hover:to-orange-400 transition-all">{{ $totalInti }}</span>
                </div>
                <div class="bg-slate-800/60 rounded-2xl p-4 shadow-[0_0_15px_rgba(6,182,212,0.1)] border border-slate-700 hover:border-cyan-500/50 transition-all flex flex-col items-center justify-center text-center group">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider group-hover:text-cyan-300 transition-colors">Anggota Divisi</span>
                    <span class="text-3xl font-black text-white group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-cyan-400 group-hover:to-blue-400 transition-all">{{ $totalDivisi }}</span>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="bg-slate-800/40 p-5 rounded-2xl shadow-lg border border-slate-700 backdrop-blur-sm relative z-10">
            <form action="{{ route('feature.kepengurusan') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1 relative group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pengurus..." class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none text-white bg-slate-900/50 transition-all placeholder-slate-400">
                    <svg class="w-5 h-5 absolute left-4 top-3.5 text-slate-400 group-focus-within:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                
                <!-- Filter Divisi -->
                <div class="w-full md:w-56 relative group">
                    <select name="divisi" class="w-full px-4 py-3 rounded-xl border border-slate-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none text-white bg-slate-900/50 appearance-none transition-all cursor-pointer">
                        <option value="" class="bg-slate-800">Semua Bagian</option>
                        @foreach($divisiList as $div)
                            <option value="{{ $div }}" {{ request('divisi') == $div ? 'selected' : '' }} class="bg-slate-800">{{ $div }}</option>
                        @endforeach
                    </select>
                    <svg class="w-5 h-5 absolute right-4 top-3.5 text-slate-400 pointer-events-none group-focus-within:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>

                <!-- Filter Jabatan -->
                <div class="w-full md:w-48 relative group">
                    <select name="jabatan" class="w-full px-4 py-3 rounded-xl border border-slate-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none text-white bg-slate-900/50 appearance-none transition-all cursor-pointer">
                        <option value="" class="bg-slate-800">Semua Jabatan</option>
                        <option value="inti" {{ request('jabatan') == 'inti' ? 'selected' : '' }} class="bg-slate-800">Pengurus Inti</option>
                        <option value="koordinator" {{ request('jabatan') == 'koordinator' ? 'selected' : '' }} class="bg-slate-800">Koordinator</option>
                        <option value="anggota" {{ request('jabatan') == 'anggota' ? 'selected' : '' }} class="bg-slate-800">Anggota Divisi</option>
                    </select>
                    <svg class="w-5 h-5 absolute right-4 top-3.5 text-slate-400 pointer-events-none group-focus-within:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white rounded-xl font-bold transition-all shadow-[0_0_15px_rgba(16,185,129,0.3)] hover:shadow-[0_0_25px_rgba(16,185,129,0.5)] flex items-center justify-center transform hover:-translate-y-0.5">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'divisi', 'jabatan']))
                        <a href="{{ route('feature.kepengurusan') }}" class="px-4 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl font-semibold transition-all border border-slate-600 hover:border-slate-500 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Grid Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 relative z-10">
            @forelse($pengurus as $index => $orang)
                @php
                    // Determine styling based on role
                    $isPembina = $orang['divisi'] == 'Pembina';
                    $isInti = $orang['divisi'] == 'Inti';
                    
                    $cardStyle = $isPembina 
                        ? 'from-purple-900/40 to-slate-900/60 hover:border-purple-500/50 hover:shadow-[0_0_20px_rgba(168,85,247,0.2)] group-hover:from-purple-500 group-hover:to-fuchsia-400' 
                        : ($isInti 
                            ? 'from-amber-900/40 to-slate-900/60 hover:border-amber-500/50 hover:shadow-[0_0_20px_rgba(245,158,11,0.2)] group-hover:from-amber-500 group-hover:to-orange-400'
                            : 'from-emerald-900/30 to-slate-900/60 hover:border-emerald-500/50 hover:shadow-[0_0_20px_rgba(16,185,129,0.2)] group-hover:from-emerald-500 group-hover:to-teal-400');
                    
                    $badgeStyle = $isPembina 
                        ? 'bg-purple-900/30 text-purple-300 border-purple-700/50' 
                        : ($isInti 
                            ? 'bg-amber-900/30 text-amber-300 border-amber-700/50'
                            : 'bg-teal-900/30 text-teal-300 border-teal-700/50');
                            
                    $jabatanStyle = $isPembina
                        ? 'text-purple-400 font-bold'
                        : (in_array($orang['jabatan'], ['Ketua', 'Wakil Ketua']) 
                            ? 'text-amber-400 font-bold'
                            : (in_array($orang['jabatan'], ['Koordinator', 'Sekretaris', 'Bendahara 1', 'Bendahara 2', 'Sekretaris 1', 'Sekretaris 2'])
                                ? 'text-emerald-400 font-bold'
                                : 'text-slate-400 font-medium'));

                    // Calculate absolute number based on pagination
                    $number = ($pengurus->currentPage() - 1) * $pengurus->perPage() + $index + 1;
                @endphp
                <div class="relative group p-1 rounded-2xl bg-gradient-to-b {{ $cardStyle }} transition-all duration-500">
                    <div class="absolute inset-0 bg-white/5 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-2xl"></div>
                    <div class="relative flex items-center gap-4 p-5 bg-slate-900 rounded-xl h-full border border-slate-700/80 group-hover:border-transparent transition-all overflow-hidden">
                        
                        <!-- Decorative glow -->
                        <div class="absolute -right-8 -top-8 w-24 h-24 {{ $isPembina ? 'bg-purple-500/20' : ($isInti ? 'bg-amber-500/20' : 'bg-emerald-500/20') }} blur-2xl rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                        
                        <!-- Number box -->
                        <div class="w-14 h-14 flex-shrink-0 bg-slate-800 border border-slate-700 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:border-transparent relative overflow-hidden z-10 shadow-lg">
                            <div class="absolute inset-0 bg-gradient-to-br {{ $cardStyle }} opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="text-slate-300 group-hover:text-white font-black text-xl relative z-10">{{ $number }}</span>
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0 relative z-10">
                            <h3 class="text-lg font-bold text-white truncate group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-white group-hover:to-slate-300 transition-all" title="{{ $orang['nama'] }}">
                                {{ $orang['nama'] }}
                            </h3>
                            <div class="flex flex-col gap-1.5 mt-2">
                                <span class="inline-flex w-fit items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border {{ $badgeStyle }} group-hover:bg-white/10 group-hover:border-white/20 group-hover:text-white transition-colors shadow-sm">
                                    {{ $orang['divisi'] == 'Pembina' || $orang['divisi'] == 'Inti' ? $orang['divisi'] : 'DIVISI ' . strtoupper($orang['divisi']) }}
                                </span>
                                <span class="text-sm truncate {{ $jabatanStyle }} group-hover:text-white/90 transition-colors">
                                    {{ $orang['jabatan'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-slate-800/40 rounded-3xl p-16 flex flex-col items-center justify-center text-center border border-dashed border-slate-600 backdrop-blur-sm">
                    <div class="w-20 h-20 bg-slate-900/80 rounded-full flex items-center justify-center mb-6 shadow-[0_0_30px_rgba(0,0,0,0.5)] border border-slate-700">
                        <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white">Tidak ada pengurus ditemukan</h3>
                    <p class="text-slate-400 mt-2 max-w-md">Coba sesuaikan kata kunci atau filter pencarian Anda untuk menemukan data yang tepat.</p>
                    @if(request()->hasAny(['search', 'divisi', 'jabatan']))
                        <a href="{{ route('feature.kepengurusan') }}" class="mt-8 px-6 py-3 bg-gradient-to-r from-slate-700 to-slate-600 text-white rounded-xl font-bold hover:from-slate-600 hover:to-slate-500 transition-all shadow-lg border border-slate-500">Reset Filter</a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($pengurus->hasPages())
            <div class="mt-8 bg-slate-800/40 p-4 rounded-2xl border border-slate-700 shadow-lg backdrop-blur-sm relative z-10">
                {{ $pengurus->links() }}
            </div>
        @endif
        
    </div>
</div>
@endsection
