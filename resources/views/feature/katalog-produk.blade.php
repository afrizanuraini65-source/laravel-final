@extends('layouts.admin')

@section('title', 'Katalog Produk - SKAKMATT')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-red-400">
                Katalog Produk & Jajanan
            </h2>
            <p class="text-gray-400 text-sm mt-1">Kelola stok dan harga jajanan hasil karya divisi PKWU SKAKMATT.</p>
        </div>
        <button class="px-4 py-2 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-400 hover:to-red-400 text-white rounded-xl font-semibold shadow-lg shadow-orange-500/30 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Produk
        </button>
    </div>

    <!-- Filter/Tabs -->
    <div class="flex gap-2 overflow-x-auto pb-2 [scrollbar-width:none]">
        <button class="px-4 py-2 rounded-full bg-orange-500/20 text-orange-300 border border-orange-500/30 font-semibold text-sm whitespace-nowrap">Semua Jajanan</button>
        <button class="px-4 py-2 rounded-full bg-white/5 text-gray-400 hover:text-orange-300 hover:bg-white/10 font-semibold text-sm whitespace-nowrap transition-all">Makanan Pedas</button>
        <button class="px-4 py-2 rounded-full bg-white/5 text-gray-400 hover:text-orange-300 hover:bg-white/10 font-semibold text-sm whitespace-nowrap transition-all">Minuman</button>
        <button class="px-4 py-2 rounded-full bg-white/5 text-gray-400 hover:text-orange-300 hover:bg-white/10 font-semibold text-sm whitespace-nowrap transition-all">Manis</button>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Product 1 -->
        <div class="bg-[#0B1221] border border-gray-800 rounded-2xl p-4 hover:border-orange-500/50 transition-all duration-300 group shadow-lg">
            <div class="aspect-square rounded-xl overflow-hidden bg-gray-900 mb-4 relative">
                <img src="https://images.unsplash.com/photo-1599490659213-e2b9527bd087?auto=format&fit=crop&q=80&w=400" alt="Basreng Pedas Daun Jeruk" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500 opacity-90">
                <div class="absolute top-2 right-2 px-2 py-1 bg-green-500/80 backdrop-blur-md text-white text-[10px] font-bold rounded-lg tracking-wider">Tersedia</div>
            </div>
            <div>
                <div class="flex justify-between items-start mb-1">
                    <h3 class="font-bold text-white text-lg leading-tight">Basreng Pedas Daun Jeruk</h3>
                </div>
                <p class="text-xs text-gray-400 mb-3">Kemasan 100gr. Gurih, pedas, renyah mantap!</p>
                
                <div class="flex items-center justify-between mt-auto">
                    <span class="text-lg font-black text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-yellow-400">Rp 5.000</span>
                    <button class="w-8 h-8 rounded-full bg-white/5 hover:bg-orange-500/20 text-gray-400 hover:text-orange-400 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </button>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-800 flex justify-between items-center text-xs">
                    <span class="text-gray-500">Terjual: 124 pcs</span>
                    <span class="text-gray-500">Stok: <span class="text-green-400 font-bold">45</span></span>
                </div>
            </div>
        </div>

        <!-- Product 2 -->
        <div class="bg-[#0B1221] border border-gray-800 rounded-2xl p-4 hover:border-orange-500/50 transition-all duration-300 group shadow-lg">
            <div class="aspect-square rounded-xl overflow-hidden bg-gray-900 mb-4 relative">
                <img src="https://images.unsplash.com/photo-1621939514649-280e2ee25f60?auto=format&fit=crop&q=80&w=400" alt="Makaroni Bantet" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500 opacity-90">
                <div class="absolute top-2 right-2 px-2 py-1 bg-yellow-500/80 backdrop-blur-md text-white text-[10px] font-bold rounded-lg tracking-wider">Sisa Sedikit</div>
            </div>
            <div>
                <div class="flex justify-between items-start mb-1">
                    <h3 class="font-bold text-white text-lg leading-tight">Makaroni Bantet Level 5</h3>
                </div>
                <p class="text-xs text-gray-400 mb-3">Kemasan pouch. Pedas gila bikin ketagihan.</p>
                
                <div class="flex items-center justify-between mt-auto">
                    <span class="text-lg font-black text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-yellow-400">Rp 6.000</span>
                    <button class="w-8 h-8 rounded-full bg-white/5 hover:bg-orange-500/20 text-gray-400 hover:text-orange-400 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </button>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-800 flex justify-between items-center text-xs">
                    <span class="text-gray-500">Terjual: 89 pcs</span>
                    <span class="text-gray-500">Stok: <span class="text-yellow-400 font-bold">12</span></span>
                </div>
            </div>
        </div>

        <!-- Product 3 -->
        <div class="bg-[#0B1221] border border-gray-800 rounded-2xl p-4 hover:border-orange-500/50 transition-all duration-300 group shadow-lg">
            <div class="aspect-square rounded-xl overflow-hidden bg-gray-900 mb-4 relative">
                <img src="https://images.unsplash.com/photo-1600803907087-f56d462fd26b?auto=format&fit=crop&q=80&w=400" alt="Keripik Kaca" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500 opacity-90">
                <div class="absolute top-2 right-2 px-2 py-1 bg-red-500/80 backdrop-blur-md text-white text-[10px] font-bold rounded-lg tracking-wider">Habis</div>
                <div class="absolute inset-0 bg-black/40 backdrop-blur-[2px] flex items-center justify-center">
                    <span class="px-3 py-1.5 bg-red-500 text-white font-bold rounded-lg rotate-12 text-sm shadow-xl border border-red-400">SOLD OUT</span>
                </div>
            </div>
            <div>
                <div class="flex justify-between items-start mb-1">
                    <h3 class="font-bold text-white text-lg leading-tight">Keripik Kaca Original</h3>
                </div>
                <p class="text-xs text-gray-400 mb-3">Keripik singkong tipis tembus pandang.</p>
                
                <div class="flex items-center justify-between mt-auto">
                    <span class="text-lg font-black text-transparent bg-clip-text bg-gradient-to-r from-gray-400 to-gray-500">Rp 4.000</span>
                    <button class="w-8 h-8 rounded-full bg-white/5 hover:bg-orange-500/20 text-gray-400 hover:text-orange-400 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </button>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-800 flex justify-between items-center text-xs">
                    <span class="text-gray-500">Terjual: 210 pcs</span>
                    <span class="text-gray-500">Stok: <span class="text-red-400 font-bold">0</span></span>
                </div>
            </div>
        </div>

        <!-- Product 4 -->
        <div class="bg-[#0B1221] border border-gray-800 rounded-2xl p-4 hover:border-orange-500/50 transition-all duration-300 group shadow-lg">
            <div class="aspect-square rounded-xl overflow-hidden bg-gray-900 mb-4 relative">
                <img src="https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&q=80&w=400" alt="Es Teh Kampul" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500 opacity-90">
                <div class="absolute top-2 right-2 px-2 py-1 bg-green-500/80 backdrop-blur-md text-white text-[10px] font-bold rounded-lg tracking-wider">Tersedia</div>
            </div>
            <div>
                <div class="flex justify-between items-start mb-1">
                    <h3 class="font-bold text-white text-lg leading-tight">Es Teh Kampul Segar</h3>
                </div>
                <p class="text-xs text-gray-400 mb-3">Es teh manis dengan irisan jeruk peras asli.</p>
                
                <div class="flex items-center justify-between mt-auto">
                    <span class="text-lg font-black text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-yellow-400">Rp 3.500</span>
                    <button class="w-8 h-8 rounded-full bg-white/5 hover:bg-orange-500/20 text-gray-400 hover:text-orange-400 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </button>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-800 flex justify-between items-center text-xs">
                    <span class="text-gray-500">Terjual: 45 cup</span>
                    <span class="text-gray-500">Stok: <span class="text-green-400 font-bold">PO</span></span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
