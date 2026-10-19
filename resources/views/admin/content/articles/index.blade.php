@extends('layouts.admin')

@section('title', 'Kelola Artikel & Edukasi - PIK-R SKAKMATT')
@section('header', 'Kelola Artikel & Edukasi')

@section('content')
<div class="space-y-6">

    <!-- Top Alert Messages -->
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
            <h3 class="text-xl font-extrabold text-white">Manajemen Artikel Publikasi</h3>
            <p class="text-xs text-gray-400 mt-1">Kelola materi edukasi remaja, liputan berita GenRe, draft, dan status publikasi</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 hover:from-cyan-400 hover:to-emerald-400 text-slate-950 font-bold text-xs uppercase tracking-wider shadow-lg transition-all hover:scale-105">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Artikel Baru</span>
            </a>
            <a href="{{ url('/informasi') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white border border-white/10 text-xs font-semibold transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                <span>Lihat Publik</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-[#0B1221]/80 backdrop-blur-xl border border-white/10 p-4 rounded-2xl">
        <form method="GET" action="{{ route('admin.articles.index') }}" class="flex flex-col sm:flex-row gap-3 items-center justify-between">
            <div class="relative w-full sm:w-80">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau kategori..." 
                       class="w-full bg-slate-900/90 border border-white/15 rounded-xl px-4 py-2 text-xs text-white placeholder-gray-400 focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400">
                <svg class="w-4 h-4 text-gray-400 absolute right-3 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <select name="status" onchange="this.form.submit()" 
                        class="bg-slate-900/90 border border-white/15 rounded-xl px-3 py-2 text-xs text-gray-300 focus:border-cyan-400 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="PUBLISHED" {{ request('status') === 'PUBLISHED' ? 'selected' : '' }}>Dipublikasikan</option>
                    <option value="DRAFT" {{ request('status') === 'DRAFT' ? 'selected' : '' }}>Draft</option>
                </select>
                @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.articles.index') }}" class="text-xs text-rose-400 hover:text-rose-300 font-semibold px-2">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Articles Table -->
    <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-300">
                <thead class="bg-black/30 border-b border-white/10 text-gray-400 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="py-4 px-6">Artikel</th>
                        <th class="py-4 px-4">Kategori</th>
                        <th class="py-4 px-4">Status</th>
                        <th class="py-4 px-4">Tanggal Publikasi</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($articles as $art)
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-xl overflow-hidden shrink-0 border border-white/10 bg-slate-900">
                                    <img src="{{ str_starts_with($art->cover_image, '/') ? $art->cover_image : asset('images/' . $art->cover_image) }}" 
                                         alt="{{ $art->title }}" 
                                         class="w-full h-full object-cover" 
                                         onerror="this.src='/images/placeholder.png'">
                                </div>
                                <div class="max-w-md">
                                    <h4 class="font-bold text-white text-sm hover:text-cyan-300 transition-colors line-clamp-1">
                                        {{ $art->title }}
                                    </h4>
                                    <p class="text-gray-400 text-[11px] line-clamp-1 mt-0.5">
                                        {{ $art->excerpt }}
                                    </p>
                                    <span class="text-[10px] text-gray-400 block mt-1">Slug: /informasi/{{ $art->slug }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="px-2.5 py-1 rounded-full bg-white/5 border border-white/10 text-gray-300 font-semibold text-[10px] whitespace-nowrap">
                                {{ $art->category }}
                            </span>
                        </td>
                        <td class="py-4 px-4">
                            <form method="POST" action="{{ route('admin.articles.toggle-status', $art->id) }}">
                                @csrf
                                <button type="submit" title="Klik untuk ubah status" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-all {{ $art->status === 'PUBLISHED' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 hover:bg-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30 hover:bg-amber-500/30' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $art->status === 'PUBLISHED' ? 'bg-emerald-400' : 'bg-amber-400' }}"></span>
                                    <span>{{ $art->status }}</span>
                                </button>
                            </form>
                        </td>
                        <td class="py-4 px-4 text-gray-400 whitespace-nowrap">
                            {{ $art->published_at ? \Carbon\Carbon::parse($art->published_at)->format('d M Y') : 'Draft' }}
                        </td>
                        <td class="py-4 px-6 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ url('/informasi/' . $art->slug) }}" target="_blank" 
                                   class="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-gray-300 hover:text-cyan-300 transition-colors" title="Lihat Artikel">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('admin.articles.edit', $art->id) }}" 
                                   class="p-2 rounded-lg bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-400 transition-colors" title="Edit Artikel">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.articles.destroy', $art->id) }}" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel: {{ addslashes($art->title) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 transition-colors" title="Hapus Artikel">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-400">
                            Tidak ada artikel ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($articles->hasPages())
        <div class="p-4 border-t border-white/10">
            {{ $articles->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
