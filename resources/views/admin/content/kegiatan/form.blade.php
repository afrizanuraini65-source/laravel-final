@extends('layouts.admin')

@section('title', ($isEdit ? 'Edit' : 'Tambah') . ' Kegiatan - PIK-R SKAKMATT')
@section('header', ($isEdit ? 'Edit' : 'Tambah') . ' Kegiatan & Dokumentasi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.kegiatan.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-300 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali ke Daftar Kegiatan</span>
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
        <form method="POST" action="{{ $isEdit ? route('admin.kegiatan.update', $item['id']) : route('admin.kegiatan.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Nama / Judul Kegiatan <span class="text-rose-400">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $item['title']) }}" required 
                           class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-400 focus:outline-none">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Kategori Kegiatan <span class="text-rose-400">*</span></label>
                    <select name="category" required class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-400 focus:outline-none">
                        <option value="Pemilihan Duta GenRe" {{ old('category', $item['category']) === 'Pemilihan Duta GenRe' ? 'selected' : '' }}>Pemilihan Duta GenRe</option>
                        <option value="Edukasi dan Sosialisasi Remaja" {{ old('category', $item['category']) === 'Edukasi dan Sosialisasi Remaja' ? 'selected' : '' }}>Edukasi dan Sosialisasi Remaja</option>
                        <option value="Diskusi dan Konseling Sebaya" {{ old('category', $item['category']) === 'Diskusi dan Konseling Sebaya' ? 'selected' : '' }}>Diskusi dan Konseling Sebaya</option>
                        <option value="Pengembangan Diri dan Kepemimpinan" {{ old('category', $item['category']) === 'Pengembangan Diri dan Kepemimpinan' ? 'selected' : '' }}>Pengembangan Diri dan Kepemimpinan</option>
                        <option value="Kegiatan Internal dan Kebersamaan Anggota" {{ old('category', $item['category']) === 'Kegiatan Internal dan Kebersamaan Anggota' ? 'selected' : '' }}>Kegiatan Internal dan Kebersamaan Anggota</option>
                    </select>
                </div>

                <!-- Category Label -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Label Singkat (Badge)</label>
                    <input type="text" name="category_label" value="{{ old('category_label', $item['category_label'] ?? '') }}" 
                           placeholder="Contoh: Pemilihan Duta, Sosialisasi, Studi Banding"
                           class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-400 focus:outline-none">
                </div>

                <!-- Date Display -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Tanggal Pelaksanaan <span class="text-rose-400">*</span></label>
                    <input type="text" name="date_display" value="{{ old('date_display', $item['date_display']) }}" required 
                           placeholder="Contoh: 12 Juni 2025 atau 9 Januari 2026"
                           class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-400 focus:outline-none">
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Lokasi Pelaksanaan <span class="text-rose-400">*</span></label>
                    <input type="text" name="location" value="{{ old('location', $item['location']) }}" required 
                           placeholder="Contoh: Aula SMKN 1 Purwokerto / Workshop / Lapangan"
                           class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-400 focus:outline-none">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Status Kegiatan <span class="text-rose-400">*</span></label>
                    <select name="status" required class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-400 focus:outline-none">
                        <option value="TERLAKSANA" {{ old('status', $item['status']) === 'TERLAKSANA' ? 'selected' : '' }}>TERLAKSANA (Dokumentasi Momen)</option>
                        <option value="MENDATANG" {{ old('status', $item['status']) === 'MENDATANG' ? 'selected' : '' }}>MENDATANG (Agenda Kalender)</option>
                        <option value="AKTIF" {{ old('status', $item['status']) === 'AKTIF' ? 'selected' : '' }}>AKTIF (Layanan Rutin)</option>
                    </select>
                </div>

                <!-- Source Reference -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Sumber Referensi / Liputan</label>
                    <input type="text" name="source" value="{{ old('source', $item['source'] ?? '') }}" 
                           placeholder="Contoh: Liputan RRI & iNews Purwokerto, Instagram @ruangsebaya_"
                           class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-400 focus:outline-none">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Deskripsi / Rangkuman Kegiatan <span class="text-rose-400">*</span></label>
                    <textarea name="desc" rows="4" required 
                              placeholder="Jelaskan tujuan, peserta, dan hasil dari kegiatan..."
                              class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-400 focus:outline-none">{{ old('desc', $item['desc'] ?? '') }}</textarea>
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Upload Foto Dokumentasi Baru</label>
                    <input type="file" name="image_file" accept="image/*" 
                           class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-cyan-500/20 file:text-cyan-300 hover:file:bg-cyan-500/30">
                </div>

                <!-- Image Filename -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Atau Gunakan Nama File Foto</label>
                    <input type="text" name="image_text" value="{{ old('image_text', $item['image'] ?? '') }}" 
                           placeholder="Contoh: pendutgen.png, pildugen.jpeg, bukber.jpeg"
                           class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-400 focus:outline-none">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6 border-t border-white/10 flex items-center justify-end gap-4">
                <a href="{{ route('admin.kegiatan.index') }}" class="px-6 py-3 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 text-xs font-bold uppercase tracking-wider transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 hover:from-cyan-400 hover:to-emerald-400 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg transition-all hover:scale-105">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Kegiatan' }}
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
