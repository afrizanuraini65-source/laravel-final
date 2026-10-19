@extends('layouts.admin')

@section('title', ($isEdit ? 'Edit' : 'Tambah') . ' Artikel - PIK-R SKAKMATT')
@section('header', ($isEdit ? 'Edit' : 'Tambah') . ' Artikel Edukasi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.articles.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-300 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali ke Daftar Artikel</span>
        </a>
    </div>

    @if ($errors->any())
    <div class="p-4 rounded-2xl bg-rose-500/15 border border-rose-500/30 text-rose-300">
        <strong class="font-bold block mb-1">Perhatian:</strong>
        <ul class="list-disc list-inside text-xs space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">
        <form method="POST" action="{{ $isEdit ? route('admin.articles.update', $article->id) : route('admin.articles.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Judul Artikel <span class="text-rose-400">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $article->title) }}" required 
                           class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-400 focus:outline-none">
                </div>

                <!-- Slug -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Slug URL <span class="text-gray-400 font-normal">(otomatis jika dikosongkan)</span></label>
                    <input type="text" name="slug" value="{{ old('slug', $article->slug) }}" 
                           class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-400 focus:outline-none">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Kategori <span class="text-rose-400">*</span></label>
                    <input type="text" name="category" value="{{ old('category', $article->category ?? 'Edukasi Remaja') }}" required 
                           placeholder="Contoh: Program GenRe, Kesehatan Reproduksi, Life Skills"
                           class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-400 focus:outline-none">
                </div>

                <!-- Excerpt / Summary -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Ringkasan Singkat (Excerpt) <span class="text-rose-400">*</span></label>
                    <textarea name="excerpt" rows="3" required 
                              placeholder="Deskripsi singkat yang tampil pada kartu artikel..."
                              class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-400 focus:outline-none">{{ old('excerpt', $article->excerpt) }}</textarea>
                </div>

                <!-- Content -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Konten Lengkap Artikel (Mendukung HTML & Paragraf) <span class="text-rose-400">*</span></label>
                    <textarea name="content" rows="12" required 
                              placeholder="Tuliskan isi artikel lengkap dengan paragraf <p>, subjudul <h3>, dan daftar <ul>..."
                              class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white font-mono leading-relaxed focus:border-cyan-400 focus:outline-none">{{ old('content', $article->content) }}</textarea>
                </div>

                <!-- Cover Image Upload -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Upload Foto Cover Baru</label>
                    <input type="file" name="cover_image" accept="image/*" 
                           class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-cyan-500/20 file:text-cyan-300 hover:file:bg-cyan-500/30">
                    <p class="text-[11px] text-gray-400 mt-1">Mendukung format JPG, PNG, WEBP (maks. 3MB)</p>
                </div>

                <!-- Existing Cover Image Filename -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Atau Gunakan Nama File Gambar</label>
                    <input type="text" name="cover_image_text" value="{{ old('cover_image_text', $article->cover_image) }}" 
                           placeholder="Contoh: pendutgen.png atau all_genre.jpeg"
                           class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-400 focus:outline-none">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Status Publikasi <span class="text-rose-400">*</span></label>
                    <select name="status" required class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-400 focus:outline-none">
                        <option value="PUBLISHED" {{ old('status', $article->status) === 'PUBLISHED' ? 'selected' : '' }}>PUBLISHED (Tampil di Website Publik)</option>
                        <option value="DRAFT" {{ old('status', $article->status) === 'DRAFT' ? 'selected' : '' }}>DRAFT (Hanya Tampak di Panel Admin)</option>
                    </select>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6 border-t border-white/10 flex items-center justify-end gap-4">
                <a href="{{ route('admin.articles.index') }}" class="px-6 py-3 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 text-xs font-bold uppercase tracking-wider transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 hover:from-cyan-400 hover:to-emerald-400 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg transition-all hover:scale-105">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Terbitkan Artikel' }}
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
