@extends('layouts.admin')

@section('title', $event->title . ' - PIK-R SKAKMATT')

@section('header', 'Workspace Event')

@section('content')
<div class="px-6 py-8" x-data="{ tab: 'informasi', committeeModalOpen: false, taskModalOpen: false, memberModalOpen: false, activeCommitteeId: null }">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <a href="{{ route('feature.event.index') }}" class="px-4 py-2 bg-slate-700/50 text-white rounded-xl hover:bg-slate-700 transition-colors text-sm font-semibold border border-slate-600 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>

            @hasanyrole('ADMIN|KETUA')
            <div class="flex gap-2">
                <a href="{{ route('feature.event.edit', $event->id) }}" class="px-4 py-2 bg-cyan-500/10 text-cyan-400 rounded-xl hover:bg-cyan-500/20 transition-colors text-sm font-semibold border border-cyan-500/30">
                    Pengaturan
                </a>
            </div>
            @endhasanyrole
        </div>

        <div class="bg-slate-800/80 backdrop-blur-md rounded-3xl border border-slate-700 shadow-xl overflow-hidden mb-8">
            <div class="w-full bg-slate-900 relative flex flex-col justify-end min-h-[16rem] md:min-h-[20rem]">
                <!-- Background Image -->
                <div class="absolute inset-0 overflow-hidden">
                    @if($event->banner_image && Storage::disk('public')->exists($event->banner_image))
                        <img src="{{ Storage::url($event->banner_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                    @elseif($event->banner_image && file_exists(public_path('images/' . $event->banner_image)))
                        <img src="{{ asset('images/' . $event->banner_image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-700 bg-slate-800">
                            <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
                </div>
                
                <!-- Content -->
                <div class="relative z-10 p-6 md:p-8 mt-auto w-full">
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="bg-emerald-500/90 text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-lg">
                            {{ $event->category ?? 'Event' }}
                        </span>
                        <span class="text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-lg backdrop-blur-md bg-cyan-500/80 text-white border border-cyan-400/50">
                            Fase: {{ $event->phase }}
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-white leading-tight drop-shadow-lg">{{ $event->title }}</h1>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="flex overflow-x-auto border-b border-slate-700 bg-slate-900/50 scrollbar-hide">
                <button @click="tab = 'informasi'" :class="{'text-cyan-400 border-b-2 border-cyan-400': tab === 'informasi', 'text-slate-400 hover:text-slate-200': tab !== 'informasi'}" class="px-6 py-4 text-sm font-bold whitespace-nowrap transition-colors">
                    Informasi
                </button>
                <button @click="tab = 'kepanitiaan'" :class="{'text-cyan-400 border-b-2 border-cyan-400': tab === 'kepanitiaan', 'text-slate-400 hover:text-slate-200': tab !== 'kepanitiaan'}" class="px-6 py-4 text-sm font-bold whitespace-nowrap transition-colors">
                    Kepanitiaan
                </button>
                <button @click="tab = 'tugas'" :class="{'text-cyan-400 border-b-2 border-cyan-400': tab === 'tugas', 'text-slate-400 hover:text-slate-200': tab !== 'tugas'}" class="px-6 py-4 text-sm font-bold whitespace-nowrap transition-colors">
                    Tugas Panitia
                </button>
                <button @click="tab = 'pendaftaran'" :class="{'text-cyan-400 border-b-2 border-cyan-400': tab === 'pendaftaran', 'text-slate-400 hover:text-slate-200': tab !== 'pendaftaran'}" class="px-6 py-4 text-sm font-bold whitespace-nowrap transition-colors">
                    Pendaftaran & Presensi
                </button>
            </div>
            
            <div class="p-6">
                <!-- TAB: INFORMASI -->
                <div x-show="tab === 'informasi'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Info details -->
                    <div class="md:col-span-1 space-y-6 bg-slate-900/50 p-6 rounded-2xl border border-slate-700/50">
                        <div>
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Tanggal Pelaksanaan</p>
                            <p class="text-white font-medium flex items-center gap-2">
                                <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                        @if($event->time)
                        <div>
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Waktu</p>
                            <p class="text-white font-medium flex items-center gap-2">
                                <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ \Carbon\Carbon::parse($event->time)->format('H:i') }} WIB
                            </p>
                        </div>
                        @endif
                        @if($event->location)
                        <div>
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Tempat</p>
                            <p class="text-white font-medium flex items-start gap-2">
                                <svg class="w-5 h-5 text-teal-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>{{ $event->location }}</span>
                            </p>
                        </div>
                        @endif
                        @if($event->docs_link)
                        <div class="pt-4 border-t border-slate-700/50">
                            <a href="{{ $event->docs_link }}" target="_blank" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500 hover:text-white border border-indigo-500/20 transition-all text-sm font-bold shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                                Link Dokumentasi & LPJ
                            </a>
                        </div>
                        @endif
                    </div>
                    <!-- Description -->
                    <div class="md:col-span-2">
                        <h3 class="text-xl font-bold text-white mb-4 border-b border-slate-700 pb-2">Deskripsi & Konsep</h3>
                        <div class="text-slate-300 leading-relaxed whitespace-pre-line text-sm md:text-base text-justify">
                            {{ $event->description }}
                        </div>
                    </div>
                </div>

                <!-- TAB: KEPANITIAAN -->
                <div x-show="tab === 'kepanitiaan'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-white">Struktur Kepanitiaan Khusus</h2>
                        @hasanyrole('ADMIN|KETUA')
                        <button @click="committeeModalOpen = true" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-400 text-white text-sm font-bold rounded-xl shadow-[0_0_10px_rgba(6,182,212,0.4)] transition-all">
                            + Tambah Seksi/Posisi
                        </button>
                        @endhasanyrole
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($event->committees as $committee)
                            <div class="bg-slate-900/50 border border-slate-700 rounded-2xl p-5 relative group">
                                <h3 class="text-lg font-bold text-cyan-400 mb-4 border-b border-slate-700 pb-2">{{ $committee->name }}</h3>
                                
                                <div class="space-y-3 mb-4">
                                    @forelse($committee->members as $member)
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-cyan-500 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                                            {{ substr($member->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-200">{{ $member->user->name }}</p>
                                            <p class="text-[10px] font-semibold text-emerald-400 uppercase tracking-wider">{{ $member->role }}</p>
                                        </div>
                                        @hasanyrole('ADMIN|KETUA')
                                        <form action="{{ route('event.committee.member.destroy', [$event->id, $member->id]) }}" method="POST" class="ml-auto" onsubmit="return confirm('Keluarkan anggota dari seksi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-500 hover:text-red-400 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </form>
                                        @endhasanyrole
                                    </div>
                                    @empty
                                    <p class="text-xs text-slate-500 italic">Belum ada anggota.</p>
                                    @endforelse
                                </div>
                                @hasanyrole('ADMIN|KETUA')
                                <div class="mt-4 flex gap-2 border-t border-slate-800 pt-3">
                                    <button @click="activeCommitteeId = {{ $committee->id }}; memberModalOpen = true" class="flex-1 py-1.5 bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-400 text-xs font-bold rounded-lg border border-cyan-500/30 transition-colors">
                                        + Anggota
                                    </button>
                                    <form action="{{ route('event.committee.destroy', [$event->id, $committee->id]) }}" method="POST" onsubmit="return confirm('Hapus seksi ini beserta semua anggotanya?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 text-xs font-bold rounded-lg border border-red-500/30 transition-colors" title="Hapus Seksi">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                                @endhasanyrole
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center border border-slate-700/50 rounded-2xl bg-slate-800/30">
                                <p class="text-slate-400">Belum ada struktur kepanitiaan yang dibentuk untuk event ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- TAB: TUGAS PANITIA -->
                <div x-show="tab === 'tugas'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-white">Jobdesk & Progress Panitia</h2>
                        <div class="flex gap-2">
                            @hasanyrole('ADMIN|KETUA')
                            <button @click="taskModalOpen = true" class="px-4 py-2 bg-cyan-500 hover:bg-cyan-400 text-white text-sm font-bold rounded-xl shadow-[0_0_10px_rgba(6,182,212,0.4)] transition-all">
                                + Tambah Tugas
                            </button>
                            @endhasanyrole
                            <a href="{{ route('feature.task_board') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded-xl transition-all border border-slate-600">
                                Buka Task Board
                            </a>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @php
                            $statuses = ['To Do' => 'bg-slate-700', 'In Progress' => 'bg-amber-600', 'Done' => 'bg-emerald-600'];
                        @endphp
                        @foreach($statuses as $status => $color)
                        <div class="bg-slate-900/40 rounded-2xl p-4 border border-slate-700/50 h-min">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-3 h-3 rounded-full {{ $color }}"></div>
                                <h3 class="font-bold text-slate-300 uppercase tracking-wider text-sm">{{ $status }}</h3>
                                <span class="ml-auto bg-slate-800 text-slate-400 text-xs px-2 py-0.5 rounded-full">{{ $event->tasks->where('status', $status)->count() }}</span>
                            </div>
                            <div class="space-y-3">
                                @forelse($event->tasks->where('status', $status) as $task)
                                    <div class="bg-slate-800 rounded-xl p-3 border border-slate-700 hover:border-cyan-500/30 transition-colors">
                                        <p class="font-bold text-white text-sm mb-1">{{ $task->title }}</p>
                                        <p class="text-xs text-slate-400 mb-2 line-clamp-2">{{ $task->description }}</p>
                                        <div class="flex justify-between items-center text-[10px] font-semibold">
                                            <span class="text-cyan-400">{{ $task->eventCommittee ? $task->eventCommittee->name : ($task->division ? $task->division->name : 'Umum') }}</span>
                                            <span class="{{ \Carbon\Carbon::parse($task->deadline)->isPast() && $task->status != 'Done' ? 'text-red-400' : 'text-slate-500' }}">
                                                {{ \Carbon\Carbon::parse($task->deadline)->format('d M') }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 border border-dashed border-slate-700 rounded-xl text-slate-500 text-xs">Kosong</div>
                                @endforelse
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- TAB: PENDAFTARAN & PRESENSI -->
                <div x-show="tab === 'pendaftaran'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-white">Kelola Pendaftaran & Presensi</h2>
                        <a href="{{ route('peserta.index') }}" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-white text-sm font-bold rounded-xl shadow-[0_0_10px_rgba(16,185,129,0.4)] transition-all">
                            Buka Modul Presensi
                        </a>
                    </div>
                    <div class="py-12 text-center border border-slate-700/50 rounded-2xl bg-slate-900/50">
                        <svg class="w-16 h-16 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <p class="text-slate-400 mb-2">Manajemen peserta (konfirmasi pendaftaran, check-in, dan presensi) dilakukan di Modul Peserta & Presensi.</p>
                        <p class="text-sm text-slate-500">Klik tombol di atas untuk mengelola daftar hadir kegiatan ini.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modals -->
    @hasanyrole('ADMIN|KETUA')
    <!-- Modal Tambah Seksi/Posisi -->
    <div x-show="committeeModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="committeeModalOpen = false"></div>
        <div class="relative bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-md p-6 shadow-2xl" x-transition>
            <h3 class="text-xl font-bold text-white mb-4">Tambah Seksi/Posisi Baru</h3>
            <form action="{{ route('event.committee.store', $event->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Nama Seksi / Posisi</label>
                    <input type="text" name="name" required placeholder="Contoh: Seksi Acara, Ketua Pelaksana" class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="committeeModalOpen = false" class="px-4 py-2 text-slate-400 hover:text-white font-medium transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-xl shadow-[0_0_15px_rgba(6,182,212,0.4)] transition-all">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Anggota -->
    <div x-show="memberModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="memberModalOpen = false"></div>
        <div class="relative bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-md p-6 shadow-2xl" x-transition>
            <h3 class="text-xl font-bold text-white mb-4">Tambahkan Anggota Kepanitiaan</h3>
            <form :action="`/feature/Event/{{ $event->id }}/committee/${activeCommitteeId}/member`" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Pilih Anggota</label>
                    <select name="user_id" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none">
                        <option value="">-- Pilih Anggota --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Peran / Jabatan (Role)</label>
                    <input type="text" name="role" required placeholder="Contoh: Koordinator, Anggota" value="Anggota" class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="memberModalOpen = false" class="px-4 py-2 text-slate-400 hover:text-white font-medium transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow-[0_0_15px_rgba(16,185,129,0.4)] transition-all">Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Tambah Tugas -->
    <div x-show="taskModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="taskModalOpen = false"></div>
        <div class="relative bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-lg p-6 shadow-2xl max-h-[90vh] overflow-y-auto" x-transition>
            <h3 class="text-xl font-bold text-white mb-4">Buat Tugas Baru</h3>
            <form action="{{ route('event.task.store', $event->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Nama Tugas</label>
                    <input type="text" name="title" required placeholder="Contoh: Membuat Desain Poster Event" class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 focus:border-cyan-500 outline-none">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Deskripsi Detail</label>
                    <textarea name="description" required rows="3" placeholder="Jelaskan detail yang perlu dikerjakan..." class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 focus:border-cyan-500 outline-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Seksi Panitia (Opsional)</label>
                        <select name="event_committee_id" class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 focus:border-cyan-500 outline-none">
                            <option value="">-- Umum / Semua --</option>
                            @foreach($event->committees as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Penanggung Jawab (PIC)</label>
                        <select name="assigned_to_user_id" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 focus:border-cyan-500 outline-none">
                            <option value="">-- Pilih PIC --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Deadline</label>
                        <input type="date" name="deadline" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 focus:border-cyan-500 outline-none [color-scheme:dark]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Prioritas</label>
                        <select name="priority" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 focus:border-cyan-500 outline-none">
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                            <option value="Rendah">Rendah</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="taskModalOpen = false" class="px-4 py-2 text-slate-400 hover:text-white font-medium transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-xl shadow-[0_0_15px_rgba(6,182,212,0.4)] transition-all">Tambahkan Tugas</button>
                </div>
            </form>
        </div>
    </div>
    @endhasanyrole

</div>
@endsection
