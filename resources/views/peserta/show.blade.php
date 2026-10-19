@extends('layouts.admin')

@section('title', 'Peserta ' . $Event->title . ' - PIK-R SKAKMATT')

@section('header', 'Kelola Peserta & Presensi')

@section('content')
<div class="px-6 py-8" x-data="{ addModalOpen: false }">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <a href="{{ route('peserta.index') }}" class="px-4 py-2 bg-slate-700/50 text-white rounded-xl hover:bg-slate-700 transition-colors text-sm font-semibold border border-slate-600 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            @hasanyrole('ADMIN|KETUA')
            <button @click="addModalOpen = true" class="px-5 py-2.5 bg-gradient-to-r from-cyan-500 to-emerald-500 text-white rounded-xl hover:from-cyan-400 hover:to-emerald-400 transition-all text-sm font-bold shadow-[0_0_15px_rgba(6,182,212,0.3)] flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Peserta
            </button>
            @endhasanyrole
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl font-medium">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Rekap Presensi (Klik untuk Filter Langsung) -->
        @php
            $currentStatus = request('status');
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            {{-- Total Peserta (Reset Filter & Tampilkan Semua) --}}
            <a href="{{ route('peserta.show', $Event->id) }}" 
               class="block bg-slate-800/80 backdrop-blur-md rounded-2xl p-5 text-center shadow-lg transition-all duration-300 hover:scale-[1.03] group {{ !$currentStatus ? 'ring-2 ring-cyan-400 bg-slate-800 border-cyan-400 shadow-[0_0_20px_rgba(6,182,212,0.3)]' : 'border border-slate-700 hover:border-slate-500' }}">
                <p class="text-xs text-slate-400 group-hover:text-cyan-300 font-bold uppercase tracking-wider mb-2 flex items-center justify-center gap-1.5">
                    <span>Total Peserta</span>
                    @if(!$currentStatus)<span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>@endif
                </p>
                <p class="text-3xl font-black text-white group-hover:scale-105 transition-transform">{{ $stats['total'] }}</p>
                <span class="text-[10px] text-slate-400 mt-1 block">Tampilkan Semua</span>
            </a>

            {{-- Hadir --}}
            <a href="{{ route('peserta.show', ['event' => $Event->id, 'status' => 'Hadir']) }}" 
               class="block bg-emerald-900/20 backdrop-blur-md rounded-2xl p-5 text-center shadow-lg transition-all duration-300 hover:scale-[1.03] group {{ $currentStatus === 'Hadir' ? 'ring-2 ring-emerald-400 bg-emerald-900/40 border-emerald-400 shadow-[0_0_20px_rgba(16,185,129,0.3)]' : 'border border-emerald-500/30 hover:border-emerald-400/60' }}">
                <p class="text-xs text-emerald-500 font-bold uppercase tracking-wider mb-2 flex items-center justify-center gap-1.5">
                    <span>Hadir</span>
                    @if($currentStatus === 'Hadir')<span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>@endif
                </p>
                <p class="text-3xl font-black text-emerald-400 group-hover:scale-105 transition-transform">{{ $stats['hadir'] }}</p>
                <span class="text-[10px] text-emerald-400/80 mt-1 block">Filter Hadir</span>
            </a>

            {{-- Izin --}}
            <a href="{{ route('peserta.show', ['event' => $Event->id, 'status' => 'Izin']) }}" 
               class="block bg-cyan-900/20 backdrop-blur-md rounded-2xl p-5 text-center shadow-lg transition-all duration-300 hover:scale-[1.03] group {{ $currentStatus === 'Izin' ? 'ring-2 ring-cyan-400 bg-cyan-900/40 border-cyan-400 shadow-[0_0_20px_rgba(6,182,212,0.3)]' : 'border border-cyan-500/30 hover:border-cyan-400/60' }}">
                <p class="text-xs text-cyan-500 font-bold uppercase tracking-wider mb-2 flex items-center justify-center gap-1.5">
                    <span>Izin</span>
                    @if($currentStatus === 'Izin')<span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>@endif
                </p>
                <p class="text-3xl font-black text-cyan-400 group-hover:scale-105 transition-transform">{{ $stats['izin'] }}</p>
                <span class="text-[10px] text-cyan-400/80 mt-1 block">Filter Izin</span>
            </a>

            {{-- Sakit --}}
            <a href="{{ route('peserta.show', ['event' => $Event->id, 'status' => 'Sakit']) }}" 
               class="block bg-amber-900/20 backdrop-blur-md rounded-2xl p-5 text-center shadow-lg transition-all duration-300 hover:scale-[1.03] group {{ $currentStatus === 'Sakit' ? 'ring-2 ring-amber-400 bg-amber-900/40 border-amber-400 shadow-[0_0_20px_rgba(245,158,11,0.3)]' : 'border border-amber-500/30 hover:border-amber-400/60' }}">
                <p class="text-xs text-amber-500 font-bold uppercase tracking-wider mb-2 flex items-center justify-center gap-1.5">
                    <span>Sakit</span>
                    @if($currentStatus === 'Sakit')<span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>@endif
                </p>
                <p class="text-3xl font-black text-amber-400 group-hover:scale-105 transition-transform">{{ $stats['sakit'] }}</p>
                <span class="text-[10px] text-amber-400/80 mt-1 block">Filter Sakit</span>
            </a>

            {{-- Alpa --}}
            <a href="{{ route('peserta.show', ['event' => $Event->id, 'status' => 'Alpa']) }}" 
               class="block bg-red-900/20 backdrop-blur-md rounded-2xl p-5 text-center shadow-lg transition-all duration-300 hover:scale-[1.03] group {{ $currentStatus === 'Alpa' ? 'ring-2 ring-red-400 bg-red-900/40 border-red-400 shadow-[0_0_20px_rgba(239,68,68,0.3)]' : 'border border-red-500/30 hover:border-red-400/60' }}">
                <p class="text-xs text-red-500 font-bold uppercase tracking-wider mb-2 flex items-center justify-center gap-1.5">
                    <span>Alpa</span>
                    @if($currentStatus === 'Alpa')<span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>@endif
                </p>
                <p class="text-3xl font-black text-red-400 group-hover:scale-105 transition-transform">{{ $stats['alpa'] }}</p>
                <span class="text-[10px] text-red-400/80 mt-1 block">Filter Alpa</span>
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-slate-800/80 backdrop-blur-md border border-slate-700 rounded-3xl p-6 shadow-xl mb-8">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">{{ $Event->title }}</h2>
                    <p class="text-sm text-slate-400 mt-1 flex gap-4">
                        <span><i class="mr-1">📅</i>{{ \Carbon\Carbon::parse($Event->date)->translatedFormat('d M Y') }}</span>
                        @if($Event->location)<span><i class="mr-1">📍</i>{{ $Event->location }}</span>@endif
                    </p>
                </div>
                
                <form action="{{ route('peserta.show', $Event->id) }}" method="GET" class="flex flex-wrap gap-3 w-full md:w-auto">
                    <input type="text" name="search" placeholder="Cari nama..." value="{{ request('search') }}" class="bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    <input type="text" name="kelas" placeholder="Filter kelas..." value="{{ request('kelas') }}" class="bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent w-32">
                    <select name="status" class="bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="Hadir" {{ request('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Izin" {{ request('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Sakit" {{ request('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Alpa" {{ request('status') == 'Alpa' ? 'selected' : '' }}>Alpa</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-slate-700 text-white rounded-xl hover:bg-slate-600 transition-colors text-sm font-semibold border border-slate-600">Filter</button>
                    @if(request()->anyFilled(['search', 'kelas', 'status']))
                        <a href="{{ route('peserta.show', $Event->id) }}" class="px-4 py-2 bg-red-500/20 text-red-400 rounded-xl hover:bg-red-500/30 transition-colors text-sm font-semibold border border-red-500/30">Reset</a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Participants List -->
        <div class="bg-slate-800/80 backdrop-blur-md border border-slate-700 rounded-3xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900/50 border-b border-slate-700">
                            <th class="py-4 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider w-16">No</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Peserta</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Kelas</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Kehadiran</th>
                            @hasanyrole('ADMIN|KETUA')
                            <th class="py-4 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                            @endhasanyrole
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        @forelse($attendances as $index => $attendance)
                        <tr class="hover:bg-slate-800/50 transition-colors">
                            <td class="py-4 px-6 text-sm text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-4 px-6">
                                <p class="text-sm font-bold text-white">{{ $attendance->user->name }}</p>
                            </td>
                            <td class="py-4 px-6 text-sm text-slate-300">
                                {{ $attendance->user->member->kelas ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if(auth()->user()->hasRole(['ADMIN', 'KETUA']))
                                <form action="{{ route('peserta.update', [$Event->id, $attendance->id]) }}" method="POST" class="inline-flex">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="text-xs font-bold px-3 py-1.5 rounded-lg border shadow-sm outline-none transition-colors
                                        {{ $attendance->status == 'Hadir' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30 focus:border-emerald-500' : 
                                          ($attendance->status == 'Izin' ? 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30 focus:border-cyan-500' : 
                                          ($attendance->status == 'Sakit' ? 'bg-amber-500/20 text-amber-400 border-amber-500/30 focus:border-amber-500' : 
                                          'bg-red-500/20 text-red-400 border-red-500/30 focus:border-red-500')) }}">
                                        <option value="Hadir" class="bg-slate-800 text-white" {{ $attendance->status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                        <option value="Izin" class="bg-slate-800 text-white" {{ $attendance->status == 'Izin' ? 'selected' : '' }}>Izin</option>
                                        <option value="Sakit" class="bg-slate-800 text-white" {{ $attendance->status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                        <option value="Alpa" class="bg-slate-800 text-white" {{ $attendance->status == 'Alpa' ? 'selected' : '' }}>Alpa</option>
                                    </select>
                                </form>
                                @else
                                <span class="text-xs font-bold px-3 py-1.5 rounded-lg border shadow-sm outline-none transition-colors
                                        {{ $attendance->status == 'Hadir' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 
                                          ($attendance->status == 'Izin' ? 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30' : 
                                          ($attendance->status == 'Sakit' ? 'bg-amber-500/20 text-amber-400 border-amber-500/30' : 
                                          'bg-red-500/20 text-red-400 border-red-500/30')) }}">
                                    {{ $attendance->status }}
                                </span>
                                @endif
                            </td>
                            @hasanyrole('ADMIN|KETUA')
                            <td class="py-4 px-6 text-center">
                                <form action="{{ route('peserta.destroy', [$Event->id, $attendance->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus peserta ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-500/10 text-red-400 hover:bg-red-500/20 rounded-lg transition-colors" title="Hapus Peserta">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                            @endhasanyrole
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                Belum ada peserta di event ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Peserta -->
    @hasanyrole('ADMIN|KETUA')
    <div x-show="addModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="addModalOpen" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="addModalOpen = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="addModalOpen" x-transition.scale.origin.bottom class="inline-block align-bottom bg-[#0F172A] border border-slate-700 rounded-3xl text-left overflow-hidden shadow-[0_0_40px_rgba(0,0,0,0.5)] transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('peserta.store', $Event->id) }}" method="POST">
                    @csrf
                    <div class="px-6 py-6 border-b border-slate-700/50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white" id="modal-title">Tambah Peserta Baru</h3>
                            <button type="button" @click="addModalOpen = false" class="text-slate-400 hover:text-white transition-colors">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="px-6 py-6 space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Pilih Anggota</label>
                            <select name="user_id" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" required>
                                <option value="">-- Pilih Anggota --</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} {{ $user->member ? '('.$user->member->kelas.')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Status Kehadiran</label>
                            <select name="status" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" required>
                                <option value="Hadir">Hadir</option>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Alpa">Alpa</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 bg-slate-900/50 border-t border-slate-700/50 flex justify-end gap-3">
                        <button type="button" @click="addModalOpen = false" class="px-5 py-2.5 rounded-xl bg-slate-700 text-white font-bold text-sm shadow-sm hover:bg-slate-600 transition-all border border-slate-600">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-white font-bold text-sm shadow-[0_0_15px_rgba(6,182,212,0.4)] hover:shadow-[0_0_25px_rgba(6,182,212,0.6)] transition-all">
                            Simpan Peserta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endhasanyrole
</div>
@endsection
