@extends('layouts.admin')

@section('title', 'Pendaftaran Kegiatan & Acara - PIK-R SKAKMATT')
@section('header', 'Pendaftaran Kegiatan')

@section('content')
<div class="space-y-8" x-data="{ activeTab: 'available' }">

    <!-- Header Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#0B1221] via-[#0D182E] to-[#081226] border border-cyan-500/20 p-6 md:p-8 shadow-[0_10px_40px_rgba(0,0,0,0.5)]">
        <div class="absolute top-0 right-0 w-80 h-80 bg-gradient-to-br from-cyan-500/15 to-emerald-500/10 rounded-full blur-[90px] pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/25 text-cyan-300 text-xs font-semibold mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                    Portal Kegiatan Anggota PIK-R
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">
                    Pendaftaran & <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-emerald-400">Tiket Kegiatan</span>
                </h1>
                <p class="text-gray-400 text-xs md:text-sm mt-1 max-w-2xl">
                    Daftarkan dirimu dalam setiap agenda, workshop, dan seminar PIK-R SKAKMATT yang sedang dibuka.
                </p>
            </div>

            <!-- Tab Buttons -->
            <div class="flex items-center p-1 bg-black/40 border border-white/10 rounded-2xl shrink-0">
                <button 
                    @click="activeTab = 'available'" 
                    :class="activeTab === 'available' ? 'bg-gradient-to-r from-cyan-500 to-emerald-500 text-white shadow-lg' : 'text-gray-400 hover:text-white'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Buka Pendaftaran ({{ count($availableEvents) }})
                </button>
                <button 
                    @click="activeTab = 'mine'" 
                    :class="activeTab === 'mine' ? 'bg-gradient-to-r from-cyan-500 to-emerald-500 text-white shadow-lg' : 'text-gray-400 hover:text-white'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    Tiket Saya ({{ $myRegistrations->count() }})
                </button>
            </div>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="p-4 bg-emerald-500/15 border border-emerald-500/30 rounded-2xl text-emerald-300 text-xs flex items-center gap-3 shadow-[0_0_20px_rgba(16,185,129,0.15)]">
            <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-500/15 border border-red-500/30 rounded-2xl text-red-300 text-xs flex items-center gap-3 shadow-[0_0_20px_rgba(239,68,68,0.15)]">
            <svg class="w-5 h-5 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- TAB 1: EVENT BUKA PENDAFTARAN -->
    <div x-show="activeTab === 'available'" class="space-y-6">
        @if(count($availableEvents) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($availableEvents as $event)
                    <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 hover:border-cyan-500/40 rounded-3xl overflow-hidden shadow-xl hover:shadow-[0_10px_30px_rgba(6,182,212,0.2)] transition-all duration-300 flex flex-col group">
                        
                        <!-- Banner / Category Header -->
                        <div class="h-36 bg-gradient-to-br from-cyan-900/40 via-slate-900 to-black relative p-5 flex flex-col justify-between overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0B1221] to-transparent"></div>
                            
                            <div class="relative z-10 flex items-center justify-between">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                                    {{ $event['category'] }}
                                </span>
                                @if($event['is_registered'])
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center gap-1">
                                        <svg class="w-3 h-3 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        Terdaftar
                                    </span>
                                @elseif($event['is_full'])
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-500/20 text-red-300 border border-red-500/30">
                                        Kuota Penuh
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 animate-pulse">
                                        Buka Pendaftaran
                                    </span>
                                @endif
                            </div>

                            <div class="relative z-10">
                                <p class="text-xs text-gray-400 font-semibold flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ \Carbon\Carbon::parse($event['date'])->translatedFormat('l, d F Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div>
                                <h3 class="text-base font-bold text-white group-hover:text-cyan-300 transition-colors line-clamp-1">
                                    {{ $event['title'] }}
                                </h3>
                                <p class="text-xs text-gray-400 mt-2 line-clamp-2 leading-relaxed">
                                    {{ $event['description'] ?? 'Tidak ada keterangan tambahan.' }}
                                </p>

                                <div class="mt-4 pt-3 border-t border-white/5 space-y-2 text-xs">
                                    @if($event['location'])
                                    <div class="flex items-center gap-2 text-gray-300">
                                        <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span class="truncate">{{ $event['location'] }}</span>
                                    </div>
                                    @endif

                                    <div class="flex items-center justify-between text-gray-400 pt-1">
                                        <span>Kuota Pendaftar:</span>
                                        <span class="font-bold text-white">
                                            {{ $event['registered_count'] }} / {{ $event['quota'] ?? '∞' }} Peserta
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action -->
                            <div class="pt-2">
                                @if($event['is_registered'])
                                    <button 
                                        @click="activeTab = 'mine'"
                                        class="w-full py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-emerald-300 font-semibold text-xs border border-emerald-500/30 transition-all flex items-center justify-center gap-1.5"
                                    >
                                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                        Lihat Tiket Saya
                                    </button>
                                @elseif($event['is_full'])
                                    <button 
                                        disabled
                                        class="w-full py-2.5 rounded-xl bg-gray-800 text-gray-500 font-bold text-xs cursor-not-allowed text-center"
                                    >
                                        Kuota Telah Penuh
                                    </button>
                                @else
                                    <form action="{{ route('pendaftaran.store', $event['id']) }}" method="POST">
                                        @csrf
                                        <button 
                                            type="submit"
                                            onclick="return confirm('Apakah Anda yakin ingin mendaftar ke kegiatan ini?')"
                                            class="w-full py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 hover:from-cyan-400 hover:to-emerald-400 text-white font-bold text-xs shadow-[0_0_15px_rgba(6,182,212,0.3)] hover:shadow-[0_0_20px_rgba(6,182,212,0.5)] transition-all flex items-center justify-center gap-1.5"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                            Daftar Sekarang
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-16 text-center bg-[#0B1221]/50 border border-white/5 rounded-3xl p-8">
                <div class="w-16 h-16 rounded-full bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center mx-auto mb-4 text-cyan-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-white">Belum Ada Kegiatan Terbuka</h3>
                <p class="text-xs text-gray-400 mt-1 max-w-md mx-auto">Saat ini belum ada pendaftaran kegiatan yang dibuka oleh pengurus. Informasi kegiatan baru akan otomatis muncul di sini begitu dirilis.</p>
            </div>
        @endif
    </div>

    <!-- TAB 2: TIKET SAYA -->
    <div x-show="activeTab === 'mine'" style="display: none;" class="space-y-6">
        @if($myRegistrations->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($myRegistrations as $reg)
                    <div class="bg-[#0B1221] border border-cyan-500/30 rounded-3xl p-6 shadow-xl relative overflow-hidden flex flex-col justify-between">
                        <!-- Neon accent -->
                        <div class="absolute -top-12 -right-12 w-36 h-36 bg-cyan-500/10 rounded-full blur-2xl pointer-events-none"></div>

                        <div>
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                    {{ $reg->status }}
                                </span>
                                <span class="text-[11px] font-mono text-cyan-400 bg-cyan-500/10 px-2.5 py-1 rounded-lg border border-cyan-500/20 font-bold">
                                    {{ $reg->reg_code }}
                                </span>
                            </div>

                            <h3 class="text-lg font-extrabold text-white">
                                {{ $reg->event->title ?? 'Kegiatan PIK-R' }}
                            </h3>

                            <div class="mt-4 space-y-2 text-xs text-gray-300">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-cyan-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span>{{ $reg->event ? \Carbon\Carbon::parse($reg->event->date)->translatedFormat('d F Y') : '-' }}</span>
                                </div>
                                @if($reg->event && $reg->event->location)
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span>{{ $reg->event->location }}</span>
                                </div>
                                @endif
                                <div class="flex items-center gap-2 text-gray-500 text-[11px] pt-1">
                                    <span>Waktu Daftar: {{ \Carbon\Carbon::parse($reg->registered_at)->translatedFormat('d M Y H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Ticket Card Footer -->
                        <div class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-gray-400 text-xs">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Tiket Resmi Terverifikasi</span>
                            </div>

                            <form action="{{ route('pendaftaran.cancel', $reg->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran kegiatan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-300 font-semibold transition-colors">
                                    Batalkan Tiket
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-16 text-center bg-[#0B1221]/50 border border-white/5 rounded-3xl p-8">
                <div class="w-16 h-16 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center mx-auto mb-4 text-emerald-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-white">Belum Ada Tiket Kegiatan</h3>
                <p class="text-xs text-gray-400 mt-1 max-w-md mx-auto">Anda belum mendaftar kegiatan apapun. Buka tab <strong>Buka Pendaftaran</strong> di atas untuk melihat acara yang tersedia dan mendaftar.</p>
            </div>
        @endif
    </div>

</div>
@endsection
