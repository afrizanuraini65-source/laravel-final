@extends('layouts.admin')

@section('title', urldecode(request()->segment(2)) . ' - Sedang Dikembangkan')
@section('header', urldecode(request()->segment(2)))

@section('content')
<div class="flex flex-col items-center justify-center min-h-[70vh] text-center px-4">
    <div class="w-32 h-32 mb-8 relative">
        <div class="absolute inset-0 bg-emerald-500/20 rounded-full animate-ping"></div>
        <div class="relative w-full h-full bg-white rounded-full flex items-center justify-center shadow-lg border border-gray-100">
            <svg class="w-16 h-16 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
        </div>
    </div>
    
    <h2 class="text-3xl font-extrabold text-pikr-dark mb-4 tracking-tight">Sedang Dalam Pengembangan</h2>
    <p class="text-gray-500 max-w-lg mb-8 text-lg">
        Modul <span class="font-bold text-emerald-600">{{ urldecode(request()->segment(2)) }}</span> saat ini sedang diracik oleh tim pengembang kami. Fitur ini akan segera hadir dengan fungsionalitas penuh!
    </p>
    
    <button onclick="window.history.back()" class="px-6 py-3 bg-white border-2 border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all duration-300 flex items-center gap-2 shadow-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali Sebelumnya
    </button>
</div>
@endsection
