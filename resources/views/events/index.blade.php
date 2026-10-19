@extends('layouts.admin')

@section('title', 'Event Management - PIK-R SKAKMATT')

@section('header', 'Event Management Workspace')

@section('content')
<div class="px-6 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Rancang <span class="text-teal-400">Kegiatan</span></h1>
                <p class="text-slate-400 mt-2">Workspace pengelolaan dan perancangan acara dari awal hingga akhir.</p>
            </div>
            
            @hasanyrole('ADMIN|KETUA')
            <a href="{{ route('feature.event.create') }}" class="px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-white font-bold text-sm shadow-[0_0_15px_rgba(6,182,212,0.4)] hover:shadow-[0_0_25px_rgba(6,182,212,0.6)] transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Rancang Kegiatan Baru
            </a>
            @endhasanyrole
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <!-- Dashboard Stats -->
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8">
            <div class="bg-slate-800/80 backdrop-blur-md border border-slate-700 rounded-2xl p-4 text-center shadow-lg">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2">Total</p>
                <p class="text-2xl font-black text-white">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-slate-700/50 backdrop-blur-md border border-slate-600/50 rounded-2xl p-4 text-center shadow-lg">
                <p class="text-[10px] text-slate-300 font-bold uppercase tracking-wider mb-2">Draft</p>
                <p class="text-2xl font-black text-slate-200">{{ $stats['draft'] }}</p>
            </div>
            <div class="bg-cyan-900/20 backdrop-blur-md border border-cyan-500/30 rounded-2xl p-4 text-center shadow-lg">
                <p class="text-[10px] text-cyan-500 font-bold uppercase tracking-wider mb-2">Persiapan</p>
                <p class="text-2xl font-black text-cyan-400">{{ $stats['persiapan'] }}</p>
            </div>
            <div class="bg-violet-900/20 backdrop-blur-md border border-violet-500/30 rounded-2xl p-4 text-center shadow-lg">
                <p class="text-[10px] text-violet-400 font-bold uppercase tracking-wider mb-2">Siap</p>
                <p class="text-2xl font-black text-violet-400">{{ $stats['siap'] }}</p>
            </div>
            <div class="bg-amber-900/20 backdrop-blur-md border border-amber-500/30 rounded-2xl p-4 text-center shadow-lg">
                <p class="text-[10px] text-amber-500 font-bold uppercase tracking-wider mb-2">Berlangsung</p>
                <p class="text-2xl font-black text-amber-400">{{ $stats['berlangsung'] }}</p>
            </div>
            <div class="bg-emerald-900/20 backdrop-blur-md border border-emerald-500/30 rounded-2xl p-4 text-center shadow-lg">
                <p class="text-[10px] text-emerald-500 font-bold uppercase tracking-wider mb-2">Selesai</p>
                <p class="text-2xl font-black text-emerald-400">{{ $stats['selesai'] }}</p>
            </div>
        </div>

        <h3 class="text-lg font-bold text-white mb-4">Event yang Sedang Dirancang & Dikelola</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($events as $event)
            <!-- Workspace Card -->
            <div class="bg-[#121B2D] border border-cyan-500/20 rounded-3xl overflow-hidden shadow-[0_4px_20px_rgba(0,0,0,0.3)] hover:border-cyan-500/40 transition-all flex flex-col">
                <div class="p-6 flex-grow">
                    <div class="flex justify-between items-start mb-4">
                        <div class="px-2.5 py-1 bg-cyan-900/50 text-cyan-300 text-xs font-bold rounded-md uppercase tracking-wider border border-cyan-500/30">
                            {{ $event->phase }}
                        </div>
                        
                        @hasanyrole('ADMIN|KETUA')
                        <div class="flex gap-2">
                            <form action="{{ route('feature.event.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rancangan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-500 hover:text-red-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                        @endhasanyrole
                    </div>

                    <h3 class="text-xl font-bold text-white mb-2 leading-tight">{{ $event->title }}</h3>
                    <p class="text-sm text-slate-400 mb-6 line-clamp-2">{{ $event->description }}</p>

                    <div class="space-y-4">
                        <!-- Progress -->
                        @php
                            $totalTasks = $event->tasks()->count();
                            $doneTasks = $event->tasks()->where('status', 'Done')->count();
                            $progress = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between text-xs mb-1.5">
                                <span class="text-slate-400 font-medium">Progress Rancangan</span>
                                <span class="text-cyan-400 font-bold">{{ $progress }}%</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-2">
                                <div class="bg-cyan-500 h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-slate-800/50 rounded-xl p-3 border border-slate-700/50">
                                <p class="text-[10px] text-slate-400 font-semibold mb-1">PANITIA</p>
                                <p class="text-base font-bold text-white">{{ \App\Models\EventCommitteeMember::whereHas('committee', function($q) use($event) { $q->where('event_id', $event->id); })->count() }} <span class="text-xs font-normal text-slate-500">Orang</span></p>
                            </div>
                            <div class="bg-slate-800/50 rounded-xl p-3 border border-slate-700/50">
                                <p class="text-[10px] text-slate-400 font-semibold mb-1">TUGAS Selesai</p>
                                <p class="text-base font-bold text-white">{{ $doneTasks }}/{{ $totalTasks }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/50 p-4 border-t border-slate-800 flex justify-between items-center gap-3">
                    <a href="{{ route('feature.event.show', $event->id) }}" class="flex-1 text-center py-2.5 bg-cyan-600 hover:bg-cyan-500 text-white text-sm font-bold rounded-xl transition-all shadow-[0_0_10px_rgba(6,182,212,0.3)]">
                        Buka Workspace
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-12 text-center border border-slate-700/50 rounded-3xl bg-slate-800/30">
                <p class="text-slate-400">Belum ada kegiatan yang sedang dirancang.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
