@extends('layouts.admin')

@section('title', 'Laporan Penjualan - SKAKMATT')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500">
                Laporan Penjualan
            </h2>
            <p class="text-gray-400 text-sm mt-1">Rekapitulasi transaksi dan pemasukan dari penjualan divisi PKWU.</p>
        </div>
        <button class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-400 hover:to-orange-400 text-white rounded-xl font-semibold shadow-lg shadow-orange-500/30 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Unduh Laporan (PDF)
        </button>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#0B1221] border border-gray-800 rounded-2xl p-6 relative overflow-hidden group hover:border-yellow-500/50 transition-colors">
            <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-500/5 rounded-bl-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 bg-yellow-500/20 text-yellow-400 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-3xl font-bold text-white">Rp 82.500</h3>
                    <p class="text-gray-400 text-sm">Pemasukan Hari Ini</p>
                </div>
            </div>
        </div>
        
        <div class="bg-[#0B1221] border border-gray-800 rounded-2xl p-6 relative overflow-hidden group hover:border-orange-500/50 transition-colors">
            <div class="absolute top-0 right-0 w-32 h-32 bg-orange-500/5 rounded-bl-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 bg-orange-500/20 text-orange-400 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <div>
                    <h3 class="text-3xl font-bold text-white">16</h3>
                    <p class="text-gray-400 text-sm">Barang Terjual</p>
                </div>
            </div>
        </div>

        <div class="bg-[#0B1221] border border-gray-800 rounded-2xl p-6 relative overflow-hidden group hover:border-green-500/50 transition-colors">
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/5 rounded-bl-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 bg-green-500/20 text-green-400 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-3xl font-bold text-white">3</h3>
                    <p class="text-gray-400 text-sm">Total Transaksi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-[#0B1221] border border-gray-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="p-6 border-b border-gray-800 flex justify-between items-center bg-gray-900/30">
            <h3 class="text-lg font-bold text-white">Riwayat Transaksi Terbaru</h3>
            <span class="px-3 py-1 bg-white/5 text-gray-300 text-xs font-semibold rounded-lg border border-gray-700">Hari ini, {{ \Carbon\Carbon::now()->format('d M Y') }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-4">ID Transaksi</th>
                        <th scope="col" class="px-6 py-4">Nama Pembeli</th>
                        <th scope="col" class="px-6 py-4">Detail Produk</th>
                        <th scope="col" class="px-6 py-4 text-center">Qty</th>
                        <th scope="col" class="px-6 py-4 text-right">Total Bayar</th>
                        <th scope="col" class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    
                    <!-- Transaksi 1 -->
                    <tr class="hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs text-yellow-400/80">#TRX-9821</td>
                        <td class="px-6 py-4 font-semibold text-white">Siswa X PPLG 2 (Koperasi)</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded bg-gray-800 overflow-hidden shrink-0">
                                    <img src="https://images.unsplash.com/photo-1599490659213-e2b9527bd087?auto=format&fit=crop&q=80&w=100" class="w-full h-full object-cover opacity-80" alt="Basreng">
                                </div>
                                <div>
                                    <div class="text-gray-300 font-medium">Basreng Pedas Daun Jeruk</div>
                                    <div class="text-xs text-gray-500">Rp 5.000 / pcs</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-white">10</td>
                        <td class="px-6 py-4 text-right font-bold text-orange-400">Rp 50.000</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-md bg-green-500/10 text-green-400 border border-green-500/20">LUNAS</span>
                        </td>
                    </tr>

                    <!-- Transaksi 2 -->
                    <tr class="hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs text-yellow-400/80">#TRX-9822</td>
                        <td class="px-6 py-4 font-semibold text-white">Ibu Guru BK</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded bg-gray-800 overflow-hidden shrink-0">
                                    <img src="https://images.unsplash.com/photo-1621939514649-280e2ee25f60?auto=format&fit=crop&q=80&w=100" class="w-full h-full object-cover opacity-80" alt="Makaroni">
                                </div>
                                <div>
                                    <div class="text-gray-300 font-medium">Makaroni Bantet Level 5</div>
                                    <div class="text-xs text-gray-500">Rp 6.000 / pcs</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-white">2</td>
                        <td class="px-6 py-4 text-right font-bold text-orange-400">Rp 12.000</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-md bg-green-500/10 text-green-400 border border-green-500/20">LUNAS</span>
                        </td>
                    </tr>

                    <!-- Transaksi 3 -->
                    <tr class="hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs text-yellow-400/80">#TRX-9823</td>
                        <td class="px-6 py-4 font-semibold text-white">Pengurus BEM</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded bg-gray-800 overflow-hidden shrink-0 flex gap-1 relative">
                                    <img src="https://images.unsplash.com/photo-1600803907087-f56d462fd26b?auto=format&fit=crop&q=80&w=100" class="w-full h-full object-cover opacity-80 absolute left-0" alt="Kaca">
                                    <img src="https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&q=80&w=100" class="w-full h-full object-cover opacity-80 absolute left-3 border-l-2 border-[#0B1221]" alt="Es">
                                </div>
                                <div>
                                    <div class="text-gray-300 font-medium">Paket Kombo (Keripik + Es Teh)</div>
                                    <div class="text-xs text-gray-500">Rp 7.500 / paket</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-white">4</td>
                        <td class="px-6 py-4 text-right font-bold text-orange-400">Rp 30.000</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-md bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">NGASBON</span>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        <!-- Pagination Dummy -->
        <div class="p-4 border-t border-gray-800 flex justify-between items-center text-sm text-gray-400">
            <span>Menampilkan 3 transaksi hari ini</span>
        </div>
    </div>
</div>
@endsection
