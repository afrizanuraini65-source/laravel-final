<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-200 bg-[#070b19] overflow-x-hidden relative">
    <!-- Background glowing orbs -->
    <div class="fixed top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-cyan-600/10 blur-[120px] pointer-events-none z-0"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-violet-600/10 blur-[120px] pointer-events-none z-0"></div>

    <div class="min-h-screen flex relative z-10" x-data="{ userMenuOpen: false }">
        <!-- Sidebar -->
        <aside class="w-72 bg-[#0B1221]/90 backdrop-blur-xl border-r border-cyan-500/20 hidden md:flex flex-col sticky top-0 h-screen shadow-[4px_0_24px_rgba(6,182,212,0.05)] z-50">
            <div class="h-20 flex items-center px-6 border-b border-cyan-500/20 gap-3 bg-black/20">
                <div class="flex gap-1 items-center">
                    <img src="/images/lgo_genre.png" alt="GenRe Logo" class="h-6 w-auto object-contain drop-shadow-[0_0_8px_rgba(255,255,255,0.3)]" />
                    <img src="/images/lgo_smc.png" alt="SMC Logo" class="h-6 w-auto object-contain drop-shadow-[0_0_8px_rgba(255,255,255,0.3)]" />
                    <img src="/images/lgo_pikr.png" alt="PIK-R Logo" class="h-6 w-auto object-contain drop-shadow-[0_0_8px_rgba(255,255,255,0.3)]" />
                </div>
                <span class="font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-emerald-400 text-lg tracking-wider drop-shadow-[0_0_10px_rgba(6,182,212,0.5)]">SKAKMATT</span>
            </div>
            
            <div class="flex-1 overflow-y-auto py-8 px-4 space-y-8 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none]">
                <div>
                    <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <x-slot name="icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        </x-slot>
                        Dashboard
                    </x-sidebar-link>
                </div>

                {{-- ======================================================== --}}
                {{-- 1. AGENDA, FORUM & P2KS (TAMPIL UNTUK SELURUH PENGURUS/USER) --}}
                {{-- ======================================================== --}}
                <div>
                    <h3 class="px-4 text-[10px] font-bold text-cyan-500/70 uppercase tracking-[0.2em] mb-3">Agenda & Forum</h3>
                    <div class="space-y-1">
                        <x-sidebar-link :href="route('feature.kegiatan')" :active="request()->routeIs('feature.kegiatan')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg></x-slot>
                            Kegiatan & Forum
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('feature.kalender')" :active="request()->routeIs('feature.kalender')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></x-slot>
                            Kalender Agenda
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('p2ks.index')" :active="request()->routeIs('p2ks.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></x-slot>
                            Piket P2KS
                        </x-sidebar-link>
                    </div>
                </div>

                {{-- ======================================================== --}}
                {{-- 2. PUSAT KONTROL: ADMIN SKAKMATT, KETUA, WAKIL, PEMBINA  --}}
                {{-- Bisa mengontrol SEMUA divisi secara rapi dan efisien     --}}
                {{-- ======================================================== --}}
                @hasanyrole('ADMIN|KETUA|WAKIL_KETUA|PEMBINA|SEKRETARIS')
                <div class="border-t border-cyan-500/10 pt-4">
                    <h3 class="px-4 text-[10px] font-bold text-emerald-400 uppercase tracking-[0.2em] mb-3 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Pusat Kontrol Organisasi
                    </h3>
                    <div class="space-y-1">
                        <x-sidebar-link :href="route('admin.settings.registration.index')" :active="request()->routeIs('admin.settings.registration.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></x-slot>
                            Pengaturan Pendaftaran
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('feature.verifikasi_anggota')" :active="request()->routeIs('feature.verifikasi_anggota*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg></x-slot>
                            Verifikasi Calon Anggota
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('anggota.index')" :active="request()->routeIs('anggota.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></x-slot>
                            Data Seluruh Anggota
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('feature.kepengurusan')" :active="request()->routeIs('feature.kepengurusan')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg></x-slot>
                            Struktur Kepengurusan
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('feature.task_board')" :active="request()->routeIs('feature.task_board*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg></x-slot>
                            Task Board Organisasi
                        </x-sidebar-link>
                    </div>

                    {{-- Kontrol 5 Divisi (Dropdown Modern & Rapi) --}}
                    <div x-data="{ kontrolDivisiOpen: {{ request()->routeIs('divisi.show') || request()->routeIs('feature.divisi') ? 'true' : 'false' }} }" class="mt-2">
                        <button @click="kontrolDivisiOpen = !kontrolDivisiOpen" class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-300 {{ request()->routeIs('feature.divisi') || request()->routeIs('divisi.show') ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/20 text-emerald-300 border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)] font-bold' : 'text-gray-400 hover:text-emerald-300 hover:bg-white/5 border border-transparent' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                <span class="text-sm font-semibold">Kontrol 5 Divisi</span>
                            </div>
                            <svg :class="kontrolDivisiOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="kontrolDivisiOpen" x-transition.opacity.duration.300ms class="pl-8 pr-2 py-2 space-y-1 mt-1 bg-black/20 rounded-xl border border-white/5" style="display: none;">
                            <a href="{{ route('feature.divisi') }}" class="block px-3 py-1.5 text-xs rounded-lg transition-all {{ request()->routeIs('feature.divisi') ? 'text-cyan-300 bg-cyan-500/10 font-bold' : 'text-gray-400 hover:text-cyan-300 hover:bg-white/5' }}">
                                🏢 Ringkasan Semua Divisi
                            </a>
                            <a href="{{ route('divisi.show', 'kegiatan') }}" class="block px-3 py-1.5 text-xs rounded-lg transition-all {{ request()->is('divisi/kegiatan') ? 'text-emerald-300 bg-emerald-500/10 font-bold' : 'text-gray-400 hover:text-emerald-300 hover:bg-white/5' }}">
                                🎯 Divisi Kegiatan
                            </a>
                            <a href="{{ route('divisi.show', 'humas') }}" class="block px-3 py-1.5 text-xs rounded-lg transition-all {{ request()->is('divisi/humas') ? 'text-amber-300 bg-amber-500/10 font-bold' : 'text-gray-400 hover:text-amber-300 hover:bg-white/5' }}">
                                📢 Divisi Humas
                            </a>
                            <a href="{{ route('divisi.show', 'pdd') }}" class="block px-3 py-1.5 text-xs rounded-lg transition-all {{ request()->is('divisi/pdd') ? 'text-violet-300 bg-violet-500/10 font-bold' : 'text-gray-400 hover:text-violet-300 hover:bg-white/5' }}">
                                📸 Divisi PDD
                            </a>
                            <a href="{{ route('divisi.show', 'pkwu') }}" class="block px-3 py-1.5 text-xs rounded-lg transition-all {{ request()->is('divisi/pkwu') ? 'text-pink-300 bg-pink-500/10 font-bold' : 'text-gray-400 hover:text-pink-300 hover:bg-white/5' }}">
                                💼 Divisi PKWU
                            </a>
                            <a href="{{ route('divisi.show', 'konselor-sebaya') }}" class="block px-3 py-1.5 text-xs rounded-lg transition-all {{ request()->is('divisi/konselor-sebaya') ? 'text-sky-300 bg-sky-500/10 font-bold' : 'text-gray-400 hover:text-sky-300 hover:bg-white/5' }}">
                                🤝 Divisi Konselor Sebaya
                            </a>
                        </div>
                    </div>

                    {{-- Event & Kas Master Control --}}
                    <div class="mt-3 pt-3 border-t border-white/5 space-y-1">
                        <x-sidebar-link :href="route('feature.event.index')" :active="request()->routeIs('feature.event.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path></svg></x-slot>
                            Kelola Seluruh Event
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('peserta.index')" :active="request()->routeIs('peserta.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg></x-slot>
                            Peserta & Presensi
                        </x-sidebar-link>
                        @can('manage_finance')
                        <x-sidebar-link :href="route('kas.index')" :active="request()->routeIs('kas.*') || request()->routeIs('payments.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></x-slot>
                            Tagihan/Kas
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('admin.settings.payment.index')" :active="request()->routeIs('admin.settings.payment.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h.01M11 15h2m-9 5h16a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></x-slot>
                            Pengaturan Pembayaran
                        </x-sidebar-link>
                        @endcan
                        <x-sidebar-link :href="route('feature.surat_dokumen')" :active="request()->routeIs('feature.surat_dokumen*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></x-slot>
                            Surat & Dokumen Resmi
                        </x-sidebar-link>
                    </div>

                    {{-- Content Management System (Artikel, Duta GenRe, Kegiatan) --}}
                    <div class="mt-3 pt-3 border-t border-white/5 space-y-1">
                        <div x-data="{ cmsMenuOpen: {{ request()->routeIs('admin.*') ? 'true' : 'false' }} }">
                            <button @click="cmsMenuOpen = !cmsMenuOpen" class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.*') ? 'bg-gradient-to-r from-cyan-500/20 to-emerald-500/20 text-cyan-300 border border-cyan-500/30 font-bold' : 'text-gray-400 hover:text-cyan-300 hover:bg-white/5 border border-transparent' }}">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path></svg>
                                    <span class="text-sm font-semibold">Kelola Konten</span>
                                </div>
                                <svg :class="cmsMenuOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="cmsMenuOpen" x-transition.opacity.duration.300ms class="pl-8 pr-2 py-2 space-y-1 mt-1 bg-black/20 rounded-xl border border-white/5">
                                <a href="{{ route('admin.articles.index') }}" class="block px-3 py-1.5 text-xs rounded-lg transition-all {{ request()->routeIs('admin.articles.*') ? 'text-cyan-300 bg-cyan-500/10 font-bold' : 'text-gray-400 hover:text-cyan-300 hover:bg-white/5' }}">
                                    📰 Artikel Edukasi
                                </a>
                                <a href="{{ route('admin.duta-genre.index') }}" class="block px-3 py-1.5 text-xs rounded-lg transition-all {{ request()->routeIs('admin.duta-genre.*') ? 'text-amber-300 bg-amber-500/10 font-bold' : 'text-gray-400 hover:text-amber-300 hover:bg-white/5' }}">
                                    👑 Duta GenRe
                                </a>
                                <a href="{{ route('admin.kegiatan.index') }}" class="block px-3 py-1.5 text-xs rounded-lg transition-all {{ request()->routeIs('admin.kegiatan.*') ? 'text-emerald-300 bg-emerald-500/10 font-bold' : 'text-gray-400 hover:text-emerald-300 hover:bg-white/5' }}">
                                    📅 Kegiatan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endhasanyrole

                {{-- ======================================================== --}}
                {{-- 3. TAMPILAN KHUSUS SEKRETARIS (ZALFA & SHAYLA)          --}}
                {{-- ======================================================== --}}
                @hasrole('SEKRETARIS')
                @unlessrole('ADMIN|KETUA|WAKIL_KETUA|PEMBINA')
                <div class="border-t border-cyan-500/10 pt-4">
                    <h3 class="px-4 text-[10px] font-bold text-teal-400 uppercase tracking-[0.2em] mb-3 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-400 animate-pulse"></span>
                        Kesekretariatan
                    </h3>
                    <div class="space-y-1">
                        <x-sidebar-link :href="route('feature.surat_dokumen')" :active="request()->routeIs('feature.surat_dokumen*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></x-slot>
                            Arsip Surat & Dokumen
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('anggota.index')" :active="request()->routeIs('anggota.index')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></x-slot>
                            Data Anggota
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('feature.verifikasi_anggota')" :active="request()->routeIs('feature.verifikasi_anggota*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg></x-slot>
                            Verifikasi Calon Anggota
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('feature.daftar_ulang')" :active="request()->routeIs('feature.daftar_ulang*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg></x-slot>
                            Verifikasi Daftar Ulang
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('peserta.index')" :active="request()->routeIs('peserta.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg></x-slot>
                            Rekap Presensi & Peserta
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('feature.task_board')" :active="request()->routeIs('feature.task_board*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg></x-slot>
                            Task Board Organisasi
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('kas.index')" :active="request()->routeIs('kas.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></x-slot>
                            Tagihan Saya
                        </x-sidebar-link>
                    </div>
                </div>
                @endunlessrole
                @endhasrole

                {{-- ======================================================== --}}
                {{-- 4. TAMPILAN KHUSUS BENDAHARA (DILA & ALYA)               --}}
                {{-- ======================================================== --}}
                @hasrole('BENDAHARA')
                @unlessrole('ADMIN|KETUA|WAKIL_KETUA|PEMBINA')
                <div class="border-t border-cyan-500/10 pt-4">
                    <h3 class="px-4 text-[10px] font-bold text-amber-400 uppercase tracking-[0.2em] mb-3 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                        Kebendaharaan & Kas
                    </h3>
                    <div class="space-y-1">
                        <x-sidebar-link :href="route('kas.index')" :active="request()->routeIs('kas.*') || request()->routeIs('payments.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></x-slot>
                            Tagihan/Kas
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('payments.index')" :active="request()->routeIs('payments.index')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></x-slot>
                            Verifikasi Pembayaran
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('admin.settings.payment.index')" :active="request()->routeIs('admin.settings.payment.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h.01M11 15h2m-9 5h16a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></x-slot>
                            Pengaturan Pembayaran
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('anggota.index')" :active="request()->routeIs('anggota.index')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></x-slot>
                            Daftar Anggota
                        </x-sidebar-link>
                    </div>
                </div>
                @endunlessrole
                @endhasrole

                {{-- ======================================================== --}}
                {{-- 5. TAMPILAN KHUSUS ANGGOTA DIVISI KEGIATAN               --}}
                {{-- ======================================================== --}}
                @hasrole('KEGIATAN')
                @unlessrole('ADMIN|KETUA|WAKIL_KETUA|PEMBINA|SEKRETARIS|BENDAHARA')
                <div class="border-t border-cyan-500/10 pt-4">
                    <h3 class="px-4 text-[10px] font-bold text-emerald-400 uppercase tracking-[0.2em] mb-3 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Tugas Divisi Kegiatan
                    </h3>
                    <div class="space-y-1">
                        <x-sidebar-link :href="route('divisi.show', 'kegiatan')" :active="request()->is('divisi/kegiatan')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg></x-slot>
                            Program Kerja Divisi
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('feature.event.index')" :active="request()->routeIs('feature.event.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path></svg></x-slot>
                            Kelola Event & Acara
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('peserta.index')" :active="request()->routeIs('peserta.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg></x-slot>
                            Presensi & Peserta
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('feature.task_board')" :active="request()->routeIs('feature.task_board*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg></x-slot>
                            Task Board Panitia
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('feature.daftar_ulang')" :active="request()->routeIs('feature.daftar_ulang*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg></x-slot>
                            Daftar Ulang Anggota
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('kas.index')" :active="request()->routeIs('kas.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></x-slot>
                            Tagihan Saya
                        </x-sidebar-link>
                    </div>
                </div>
                @endunlessrole
                @endhasrole

                {{-- ======================================================== --}}
                {{-- 6. TAMPILAN KHUSUS ANGGOTA DIVISI HUMAS                  --}}
                {{-- ======================================================== --}}
                @hasrole('HUMAS')
                @unlessrole('ADMIN|KETUA|WAKIL_KETUA|PEMBINA|SEKRETARIS|BENDAHARA')
                <div class="border-t border-cyan-500/10 pt-4">
                    <h3 class="px-4 text-[10px] font-bold text-amber-400 uppercase tracking-[0.2em] mb-3 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                        Tugas Divisi Humas
                    </h3>
                    <div class="space-y-1">
                        <x-sidebar-link :href="route('divisi.show', 'humas')" :active="request()->is('divisi/humas')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></x-slot>
                            Program Kerja Humas
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('feature.surat_dokumen', ['tab' => 'dispensasi'])" :active="request()->routeIs('feature.surat_dokumen*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></x-slot>
                            Surat & Dokumen Kerjasama
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('kas.index')" :active="request()->routeIs('kas.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></x-slot>
                            Tagihan Saya
                        </x-sidebar-link>
                    </div>
                </div>
                @endunlessrole
                @endhasrole

                {{-- ======================================================== --}}
                {{-- 7. TAMPILAN KHUSUS ANGGOTA DIVISI PDD                    --}}
                {{-- ======================================================== --}}
                @hasrole('PDD')
                @unlessrole('ADMIN|KETUA|WAKIL_KETUA|PEMBINA|SEKRETARIS|BENDAHARA')
                <div class="border-t border-cyan-500/10 pt-4">
                    <h3 class="px-4 text-[10px] font-bold text-violet-400 uppercase tracking-[0.2em] mb-3 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-violet-400 animate-pulse"></span>
                        Tugas Divisi PDD
                    </h3>
                    <div class="space-y-1">
                        <x-sidebar-link :href="route('divisi.show', 'pdd')" :active="request()->is('divisi/pdd')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></x-slot>
                            Program Kerja PDD
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('feature.pending', 'Galeri')" :active="request()->is('feature/Galeri*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></x-slot>
                            Galeri Dokumentasi
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('feature.pending', 'Desain Konten')" :active="request()->is('feature/Desain*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></x-slot>
                            Desain Konten & Media
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('kas.index')" :active="request()->routeIs('kas.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></x-slot>
                            Tagihan Saya
                        </x-sidebar-link>
                    </div>
                </div>
                @endunlessrole
                @endhasrole

                {{-- ======================================================== --}}
                {{-- 8. TAMPILAN KHUSUS ANGGOTA DIVISI PKWU                   --}}
                {{-- ======================================================== --}}
                @hasrole('PKWU')
                @unlessrole('ADMIN|KETUA|WAKIL_KETUA|PEMBINA|SEKRETARIS|BENDAHARA')
                <div class="border-t border-cyan-500/10 pt-4">
                    <h3 class="px-4 text-[10px] font-bold text-pink-400 uppercase tracking-[0.2em] mb-3 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-pink-400 animate-pulse"></span>
                        Tugas Divisi PKWU
                    </h3>
                    <div class="space-y-1">
                        <x-sidebar-link :href="route('divisi.show', 'pkwu')" :active="request()->is('divisi/pkwu')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg></x-slot>
                            Program Kerja PKWU
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('feature.pending', 'Katalog Produk')" :active="request()->is('feature/Katalog*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg></x-slot>
                            Katalog Produk & Karya
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('feature.pending', 'Laporan Penjualan')" :active="request()->is('feature/Laporan*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></x-slot>
                            Laporan Penjualan & Modal
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('kas.index')" :active="request()->routeIs('kas.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></x-slot>
                            Tagihan Saya
                        </x-sidebar-link>
                    </div>
                </div>
                @endunlessrole
                @endhasrole

                {{-- ======================================================== --}}
                {{-- 9. TAMPILAN KHUSUS ANGGOTA DIVISI KONSELOR SEBAYA        --}}
                {{-- ======================================================== --}}
                @hasrole('KONSELOR_SEBAYA')
                @unlessrole('ADMIN|KETUA|WAKIL_KETUA|PEMBINA|SEKRETARIS|BENDAHARA')
                <div class="border-t border-cyan-500/10 pt-4">
                    <h3 class="px-4 text-[10px] font-bold text-sky-400 uppercase tracking-[0.2em] mb-3 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-pulse"></span>
                        Tugas Konselor Sebaya
                    </h3>
                    <div class="space-y-1">
                        <x-sidebar-link :href="route('divisi.show', 'konselor-sebaya')" :active="request()->is('divisi/konselor-sebaya')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></x-slot>
                            Program Kerja Konselor
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('feature.pending', 'Jadwal Konseling')" :active="request()->is('feature/Jadwal*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></x-slot>
                            Jadwal Sesi Konseling
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('feature.pending', 'Data Klien')" :active="request()->is('feature/Data*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></x-slot>
                            Data Klien & Catatan Sesi
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('kas.index')" :active="request()->routeIs('kas.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></x-slot>
                            Tagihan Saya
                        </x-sidebar-link>
                    </div>
                </div>
                @endunlessrole
                @endhasrole

                {{-- ======================================================== --}}
                {{-- 10. TAMPILAN ANGGOTA BIASA (USER NON-PENGURUS)          --}}
                {{-- ======================================================== --}}
                @hasrole('USER')
                @unlessrole('ADMIN|KETUA|WAKIL_KETUA|PEMBINA|SEKRETARIS|BENDAHARA|KEGIATAN|HUMAS|PDD|PKWU|KONSELOR_SEBAYA')
                <div class="border-t border-cyan-500/10 pt-4">
                    <h3 class="px-4 text-[10px] font-bold text-cyan-500/70 uppercase tracking-[0.2em] mb-3">Personal Anggota</h3>
                    <div class="space-y-1">
                        <x-sidebar-link :href="route('kas.index')" :active="request()->routeIs('kas.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></x-slot>
                            Tagihan Saya
                        </x-sidebar-link>
                        <x-sidebar-link :href="route('pendaftaran.index')" :active="request()->routeIs('pendaftaran.*')">
                            <x-slot name="icon"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg></x-slot>
                            Pendaftaran Saya
                        </x-sidebar-link>
                    </div>
                </div>
                @endunlessrole
                @endhasrole
</div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen w-full overflow-hidden">
            <!-- Topbar -->
            <header class="h-20 bg-[#0B1221]/70 backdrop-blur-xl border-b border-cyan-500/10 flex items-center justify-between px-8 sticky top-0 z-40 shadow-[0_4px_30px_rgba(0,0,0,0.5)]">
                <div class="flex items-center">
                    <h2 class="font-extrabold text-2xl text-white tracking-tight drop-shadow-[0_0_8px_rgba(255,255,255,0.2)]">@yield('header')</h2>
                    @if(Auth::user()->roles->count() > 0)
                        <span class="ml-4 px-3.5 py-1.5 bg-[#0B1221] border border-cyan-500/30 text-cyan-400 text-xs font-bold rounded-full shadow-[0_0_10px_rgba(6,182,212,0.2)]">
                            {{ Auth::user()->roles->first()->name }}
                        </span>
                    @endif
                </div>
                
                <div class="flex items-center gap-5">
                    <!-- Notifications -->
                    <button class="text-cyan-500/70 hover:text-cyan-400 transition-all relative group p-2 rounded-full hover:bg-cyan-500/10 hover:shadow-[0_0_15px_rgba(6,182,212,0.3)]">
                        <svg class="w-6 h-6 group-hover:scale-110 transition-transform drop-shadow-[0_0_5px_rgba(6,182,212,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-violet-500 border-2 border-[#0B1221] rounded-full shadow-[0_0_8px_rgba(139,92,246,0.8)]"></span>
                    </button>
                    
                    <div class="relative" @click.away="userMenuOpen = false">
                        <button @click="userMenuOpen = !userMenuOpen" class="flex items-center gap-3 text-sm font-semibold text-gray-300 hover:text-white transition-all bg-[#121B2D] px-4 py-2 rounded-2xl border border-cyan-500/20 shadow-[0_0_15px_rgba(6,182,212,0.1)] hover:shadow-[0_0_20px_rgba(6,182,212,0.3)] hover:border-cyan-400/50 group">
                            <div class="w-8 h-8 rounded-full border-2 border-emerald-400/50 shadow-[0_0_10px_rgba(52,211,153,0.5)] p-[2px] relative">
                                <div class="w-full h-full bg-gradient-to-br from-cyan-600 to-violet-600 rounded-full flex items-center justify-center text-xs font-bold text-white">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-[#121B2D] shadow-[0_0_5px_rgba(52,211,153,0.8)]"></div>
                            </div>
                            <span class="group-hover:text-cyan-300 transition-colors" style="color: #eab308; text-shadow: 0 0 10px rgba(234,179,8,0.3);">{{ Auth::user()->name }}</span>
                            <svg class="fill-current h-4 w-4 text-cyan-600/50 group-hover:text-cyan-400 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="userMenuOpen" x-transition class="absolute right-0 mt-2 w-48 bg-[#121B2D] rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.8)] py-1 z-50 border border-cyan-500/20 backdrop-blur-xl" style="display: none;">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-cyan-500/10 hover:text-cyan-300 transition-colors">Profile Settings</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-red-500/10 hover:text-red-400 transition-colors">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-8 md:p-10 flex-1 relative z-10">
                @yield('content')
            </main>
        </div>
    </div>

    @php
        $todaySchedule = null;
        if(Auth::check()) {
            $todaySchedule = \App\Models\P2ksSchedule::where('user_id', Auth::id())
                ->whereDate('scheduled_date', '<=', \Carbon\Carbon::today())
                ->where('status', 'menunggu')
                ->orderBy('scheduled_date', 'desc')
                ->first();
        }
    @endphp

    @if($todaySchedule)
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Waktunya Piket P2KS! 🧹',
                    html: `
                        <div class="text-left space-y-3 mt-2">
                            <p class="text-sm text-slate-300">
                                Halo <b class="text-white">{{ Auth::user()->name }}</b>, kamu memiliki jadwal piket P2KS aktif yang <b>belum dikonfirmasi selesai</b>.
                            </p>
                            <div class="bg-slate-900/90 border border-teal-500/40 rounded-2xl p-3.5 text-center shadow-[0_0_15px_rgba(20,184,166,0.15)]">
                                <span class="text-[10px] text-teal-400 font-extrabold uppercase tracking-widest block mb-0.5">Jadwal Piket</span>
                                <span class="text-base font-black text-white">{{ \Carbon\Carbon::parse($todaySchedule->scheduled_date)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                            </div>
                            <div class="p-3 bg-amber-500/10 border border-amber-500/30 rounded-xl text-amber-300 text-xs flex items-center gap-2.5">
                                <svg class="w-4 h-4 shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <span>Status kamu masih <b>Menunggu</b>. Notifikasi ini akan terus muncul sampai piket selesai dan diverifikasi oleh Ketua atau Admin.</span>
                            </div>
                        </div>
                    `,
                    icon: 'warning',
                    iconColor: '#2dd4bf',
                    background: '#0F172A',
                    color: '#ffffff',
                    confirmButtonColor: '#0d9488',
                    confirmButtonText: 'Siap Laksanakan! 🫡',
                    customClass: {
                        popup: 'rounded-3xl border border-teal-500/40 shadow-[0_0_50px_rgba(20,184,166,0.3)]',
                        confirmButton: 'rounded-xl font-bold px-6 py-3 shadow-[0_0_15px_rgba(20,184,166,0.5)]'
                    }
                });
            });
        </script>
    @endif
</body>
</html>
