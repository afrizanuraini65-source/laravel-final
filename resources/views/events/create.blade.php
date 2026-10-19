@extends('layouts.admin')

@section('title', 'Tambah Event - PIK-R SKAKMATT')

@section('header', 'Tambah Event')

@section('content')
<div class="px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Tambah <span class="text-teal-400">Event</span></h1>
                <p class="text-slate-400 mt-2">Buat event baru untuk PIK-R SKAKMATT.</p>
            </div>
            <a href="{{ request('redirect') == 'peserta' ? route('peserta.index') : route('feature.event.index') }}" class="px-4 py-2 bg-slate-700/50 text-white rounded-xl hover:bg-slate-700 transition-colors text-sm font-semibold border border-slate-600">
                Kembali
            </a>
        </div>

        <div class="bg-slate-800/80 backdrop-blur-md rounded-3xl border border-slate-700 shadow-xl overflow-hidden">
            <form action="{{ route('feature.event.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                @if(request()->has('redirect'))
                    <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-slate-300">Nama Event</label>
                        <input type="text" name="title" id="title" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" value="{{ old('title') }}" required placeholder="Contoh: Pemilihan Duta GenRe">
                        @error('title') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="category" class="block text-sm font-medium text-slate-300">Kategori</label>
                        <select name="category" id="category" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Kegiatan" {{ old('category') == 'Kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                            <option value="Peringatan" {{ old('category') == 'Peringatan' ? 'selected' : '' }}>Peringatan</option>
                            <option value="Edukasi" {{ old('category') == 'Edukasi' ? 'selected' : '' }}>Edukasi</option>
                            <option value="Kampanye" {{ old('category') == 'Kampanye' ? 'selected' : '' }}>Kampanye</option>
                        </select>
                        @error('category') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-medium text-slate-300">Status</label>
                        <select name="status" id="status" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" required>
                            <option value="Akan Datang" {{ old('status') == 'Akan Datang' ? 'selected' : '' }}>Akan Datang</option>
                            <option value="Berlangsung" {{ old('status') == 'Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="date" class="block text-sm font-medium text-slate-300">Tanggal</label>
                        <input type="date" name="date" id="date" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" value="{{ old('date') }}" required>
                        @error('date') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="time" class="block text-sm font-medium text-slate-300">Waktu (Opsional)</label>
                        <input type="time" name="time" id="time" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" value="{{ old('time') }}">
                        @error('time') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label for="location" class="block text-sm font-medium text-slate-300">Tempat (Opsional)</label>
                        <input type="text" name="location" id="location" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" value="{{ old('location') }}" placeholder="Contoh: Aula SMKN 1 Purwokerto">
                        @error('location') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label for="docs_link" class="block text-sm font-medium text-slate-300">Link Dokumentasi (Opsional)</label>
                        <input type="url" name="docs_link" id="docs_link" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" value="{{ old('docs_link') }}" placeholder="Contoh: https://drive.google.com/...">
                        <p class="text-xs text-slate-500">Masukkan link Google Drive atau link dokumentasi lainnya.</p>
                        @error('docs_link') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label for="banner_image" class="block text-sm font-medium text-slate-300">Poster / Foto Event (Opsional)</label>
                        <input type="file" name="banner_image" id="banner_image" accept="image/*" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-500/20 file:text-cyan-400 hover:file:bg-cyan-500/30">
                        <p class="text-xs text-slate-500">Format: JPG, PNG, GIF. Maks: 2MB.</p>
                        @error('banner_image') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-slate-300">Deskripsi/Keterangan</label>
                        <textarea name="description" id="description" rows="5" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" required placeholder="Jelaskan mengenai event ini...">{{ old('description') }}</textarea>
                        @error('description') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-700 flex justify-end gap-3">
                    <button type="reset" class="px-6 py-3 rounded-xl bg-slate-700/50 text-white font-bold text-sm shadow-sm hover:bg-slate-700 transition-all">
                        Reset
                    </button>
                    <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-white font-bold text-sm shadow-[0_0_15px_rgba(6,182,212,0.4)] hover:shadow-[0_0_25px_rgba(6,182,212,0.6)] transition-all">
                        Simpan Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
