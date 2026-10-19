@extends('layouts.admin')

@section('title', 'Galeri Kegiatan - SKAKMATT')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-emerald-400">
            Galeri Kegiatan
        </h2>
        <p class="text-gray-400 text-sm mt-1">Koleksi foto kegiatan dan dokumentasi acara PIK-R SKAKMATT.</p>
    </div>

    <!-- Gallery Link Prominent -->
    <div class="bg-gradient-to-br from-[#0B1221] to-[#121b2f] border border-cyan-500/30 rounded-3xl p-8 md:p-12 text-center max-w-4xl mx-auto mt-12 shadow-[0_0_40px_rgba(6,182,212,0.1)] relative overflow-hidden group">
        
        <!-- Decorative Background Icons -->
        <div class="absolute -top-10 -right-10 text-cyan-500/5 group-hover:text-cyan-500/10 transition-colors duration-500">
            <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"/><path d="M7 12l2.5 3 3.5-4.5 5 6H6z"/></svg>
        </div>

        <div class="relative z-10">
            <div class="w-20 h-20 bg-cyan-500/20 text-cyan-400 rounded-2xl flex items-center justify-center mx-auto mb-6 ring-2 ring-cyan-500/40 shadow-[0_0_15px_rgba(6,182,212,0.4)]">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            
            <h3 class="text-3xl font-extrabold text-white mb-4">Pusat Dokumentasi SKAKMATT</h3>
            <p class="text-gray-300 text-lg mb-8 max-w-2xl mx-auto">Seluruh arsip galeri, foto kegiatan, dan dokumentasi event PIK-R SKAKMATT kini dikelola secara terpusat melalui Google Drive untuk memudahkan akses dan kolaborasi.</p>
            
            <a href="https://drive.google.com/drive/folders/1aj0xMdVb-TigPADwO_RNf6IlL2fBScND" target="_blank" class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-bold text-lg rounded-full shadow-lg shadow-cyan-500/30 transition-all hover:scale-105">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M7.71,3.5L1.15,15L4.58,21L11.13,9.5M9.73,15L6.3,21H19.42L22.85,15M22.28,14L15.42,2H8.57L15.43,14H22.28Z"/>
                </svg>
                Buka Folder G-Drive
            </a>
        </div>
    </div>
</div>
@endsection
