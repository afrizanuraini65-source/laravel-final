@extends('layouts.admin')

@section('title', 'Edit Event - PIK-R SKAKMATT')

@section('header', 'Edit Event')

@section('content')
<div class="px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Edit <span class="text-teal-400">Event</span></h1>
                <p class="text-slate-400 mt-2">Ubah data event PIK-R SKAKMATT.</p>
            </div>
            <a href="{{ route('feature.event.index') }}" class="px-4 py-2 bg-slate-700/50 text-white rounded-xl hover:bg-slate-700 transition-colors text-sm font-semibold border border-slate-600">
                Kembali
            </a>
        </div>

        <div class="bg-slate-800/80 backdrop-blur-md rounded-3xl border border-slate-700 shadow-xl overflow-hidden">
            <form action="{{ route('feature.event.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-slate-300">Nama Event</label>
                        <input type="text" name="title" id="title" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" value="{{ old('title', $event->title) }}" required>
                        @error('title') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="category" class="block text-sm font-medium text-slate-300">Kategori</label>
                        <select name="category" id="category" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" required>
                            <option value="Kegiatan" {{ old('category', $event->category) == 'Kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                            <option value="Peringatan" {{ old('category', $event->category) == 'Peringatan' ? 'selected' : '' }}>Peringatan</option>
                            <option value="Edukasi" {{ old('category', $event->category) == 'Edukasi' ? 'selected' : '' }}>Edukasi</option>
                            <option value="Kampanye" {{ old('category', $event->category) == 'Kampanye' ? 'selected' : '' }}>Kampanye</option>
                        </select>
                        @error('category') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-medium text-slate-300">Status</label>
                        <select name="status" id="status" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" required>
                            <option value="Akan Datang" {{ old('status', $event->status) == 'Akan Datang' ? 'selected' : '' }}>Akan Datang</option>
                            <option value="Berlangsung" {{ old('status', $event->status) == 'Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="Selesai" {{ old('status', $event->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="date" class="block text-sm font-medium text-slate-300">Tanggal</label>
                        <input type="date" name="date" id="date" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" value="{{ old('date', $event->date ? \Carbon\Carbon::parse($event->date)->format('Y-m-d') : '') }}" required>
                        @error('date') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="time" class="block text-sm font-medium text-slate-300">Waktu (Opsional)</label>
                        <input type="time" name="time" id="time" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" value="{{ old('time', $event->time ? \Carbon\Carbon::parse($event->time)->format('H:i') : '') }}">
                        @error('time') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label for="location" class="block text-sm font-medium text-slate-300">Tempat (Opsional)</label>
                        <input type="text" name="location" id="location" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" value="{{ old('location', $event->location) }}">
                        @error('location') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label for="docs_link" class="block text-sm font-medium text-slate-300">Link Dokumentasi (Opsional)</label>
                        <input type="url" name="docs_link" id="docs_link" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" value="{{ old('docs_link', $event->docs_link) }}">
                        @error('docs_link') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label for="banner_image" class="block text-sm font-medium text-slate-300">Poster / Foto Event (Opsional)</label>
                        @if($event->banner_image)
                            <div class="mb-3">
                                <p class="text-xs text-slate-400 mb-2">Poster saat ini:</p>
                                <img src="{{ Storage::url($event->banner_image) }}" alt="Poster" class="h-32 object-cover rounded-xl border border-slate-700">
                            </div>
                        @endif
                        <input type="file" name="banner_image" id="banner_image" accept="image/*" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-500/20 file:text-cyan-400 hover:file:bg-cyan-500/30">
                        <p class="text-xs text-slate-500">Biarkan kosong jika tidak ingin mengubah poster.</p>
                        @error('banner_image') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-slate-300">Deskripsi/Keterangan</label>
                        <textarea name="description" id="description" rows="5" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" required>{{ old('description', $event->description) }}</textarea>
                        @error('description') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-700 flex justify-end gap-3">
                    <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-white font-bold text-sm shadow-[0_0_15px_rgba(6,182,212,0.4)] hover:shadow-[0_0_25px_rgba(6,182,212,0.6)] transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
