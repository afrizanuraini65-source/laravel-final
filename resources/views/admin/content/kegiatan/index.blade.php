@extends('layouts.admin')

@section('title', 'Kelola Kegiatan & Dokumentasi - PIK-R SKAKMATT')
@section('header', 'Kelola Kegiatan & Dokumentasi')

@section('content')
<div class="space-y-6">

    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white">&times;</button>
    </div>
    @endif

    <!-- Control Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-[#0B1221]/90 backdrop-blur-xl border border-cyan-500/20 p-6 rounded-3xl shadow-xl">
        <div>
            <h3 class="text-xl font-extrabold text-white">Dokumentasi & Agenda Organisasi</h3>
            <p class="text-xs text-gray-400 mt-1">Kelola rekam jejak kegiatan, pemilihan duta, sosialisasi, dan kebersamaan</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.kegiatan.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 hover:from-cyan-400 hover:to-emerald-400 text-slate-950 font-bold text-xs uppercase tracking-wider shadow-lg transition-all hover:scale-105">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Kegiatan Baru</span>
            </a>
            <a href="{{ url('/kegiatan') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white border border-white/10 text-xs font-semibold transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                <span>Lihat Publik</span>
            </a>
        </div>
    </div>

    <!-- Table of Kegiatan -->
    <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-300">
                <thead class="bg-black/30 border-b border-white/10 text-gray-400 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-4 px-6">Kegiatan</th>
                        <th class="py-4 px-4">Kategori</th>
                        <th class="py-4 px-4">Tanggal & Lokasi</th>
                        <th class="py-4 px-4">Status</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($kegiatan as $id => $item)
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-xl overflow-hidden shrink-0 border border-white/10 bg-slate-900">
                                    <img src="{{ str_starts_with($item['image'], '/') ? $item['image'] : asset('images/' . $item['image']) }}" 
                                         alt="{{ $item['title'] }}" 
                                         class="w-full h-full object-cover" 
                                         onerror="this.src='/images/placeholder.png'">
                                </div>
                                <div class="max-w-md">
                                    <h4 class="font-bold text-white text-sm hover:text-cyan-300 transition-colors line-clamp-1">
                                        {{ $item['title'] }}
                                    </h4>
                                    <p class="text-gray-400 text-[11px] line-clamp-1 mt-0.5">
                                        {{ $item['desc'] ?? '-' }}
                                    </p>
                                    @if(!empty($item['source']))
                                    <span class="text-[10px] text-emerald-400 block mt-0.5">Sumber: {{ $item['source'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-2.5 py-1 rounded-full bg-white/5 border border-white/10 text-gray-300 font-semibold text-[10px] whitespace-nowrap">
                                {{ $item['category_label'] ?? ($item['category'] ?? 'Kegiatan') }}
                            </span>
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <span class="text-white font-semibold block">{{ $item['date_display'] }}</span>
                            <span class="text-gray-400 text-[11px]">{{ $item['location'] }}</span>
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ ($item['status'] ?? '') === 'TERLAKSANA' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30' }}">
                                {{ $item['status_label'] ?? ($item['status'] ?? 'Terlaksana') }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.kegiatan.edit', $item['id'] ?? $id) }}" 
                                   class="p-2 rounded-lg bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-400 transition-colors" title="Edit Kegiatan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.kegiatan.destroy', $item['id'] ?? $id) }}" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 transition-colors" title="Hapus Kegiatan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-400">
                            Tidak ada kegiatan ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
