@extends('layouts.admin')

@section('title', 'Peserta & Presensi - PIK-R SKAKMATT')

@section('header', 'Peserta & Presensi')

@section('content')
<div class="px-6 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Peserta & <span class="text-teal-400">Presensi</span></h1>
                <p class="text-slate-400 mt-2">Daftar kegiatan dan manajemen kehadiran peserta.</p>
            </div>
            
            @hasanyrole('ADMIN|KETUA')
            <a href="{{ route('feature.event.create', ['redirect' => 'peserta']) }}" class="px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-white font-bold text-sm shadow-[0_0_15px_rgba(6,182,212,0.4)] hover:shadow-[0_0_25px_rgba(6,182,212,0.6)] transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Kegiatan
            </a>
            @endhasanyrole
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($events as $event)
            <!-- Card -->
            <div class="group bg-slate-800/80 backdrop-blur-md rounded-3xl overflow-hidden border border-slate-700 shadow-xl flex flex-col transition-all duration-300 hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-emerald-900/20 hover:border-emerald-500/40">
                <div class="p-6 flex flex-col flex-grow relative">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-slate-900/90 border border-slate-600 rounded-2xl px-3 py-1.5 flex flex-col items-center justify-center text-center shadow-lg">
                            <span class="text-[9px] font-extrabold uppercase tracking-widest text-teal-400">{{ \Carbon\Carbon::parse($event->date)->translatedFormat('M') }}</span>
                            <span class="text-base font-black leading-tight text-white">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</span>
                        </div>
                        <div class="bg-emerald-500/20 text-emerald-400 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-emerald-500/30">
                            {{ $event->category ?? 'Event' }}
                        </div>
                    </div>

                    <h3 class="text-lg font-bold text-white mb-2 leading-snug group-hover:text-teal-400 transition-colors line-clamp-2">
                        {{ $event->title }}
                    </h3>

                    <div class="text-xs text-slate-400 space-y-2 mb-6">
                        @if($event->location)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="truncate">{{ $event->location }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="mt-auto">
                        <div class="flex justify-between items-center mb-4 px-4 py-3 bg-slate-900/50 rounded-xl border border-slate-700/50">
                            <div class="text-center">
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1">Peserta</p>
                                <p class="text-lg font-black text-white">{{ $event->attendances_count }}</p>
                            </div>
                            <div class="w-px h-8 bg-slate-700"></div>
                            <div class="text-center">
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1">Status</p>
                                <span class="text-[9px] font-bold px-2 py-1 rounded-md uppercase tracking-wider
                                    {{ $event->status == 'Selesai' ? 'bg-slate-700/80 text-gray-300' : 
                                      ($event->status == 'Berlangsung' ? 'bg-amber-500/20 text-amber-400' : 'bg-cyan-500/20 text-cyan-400') }}">
                                    {{ $event->status }}
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('peserta.show', $event->id) }}" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-white hover:from-cyan-400 hover:to-emerald-400 transition-all text-sm font-bold shadow-[0_0_15px_rgba(6,182,212,0.3)] group-hover:shadow-[0_0_20px_rgba(6,182,212,0.5)]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Kelola Peserta & Presensi
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-12 text-center border border-slate-700/50 rounded-3xl bg-slate-800/30">
                <p class="text-slate-400">Belum ada kegiatan yang tersedia.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
