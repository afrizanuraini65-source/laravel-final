@extends('layouts.admin')

@section('title', 'Edit Profil Duta GenRe - ' . $duta['name'])
@section('header', 'Edit Profil: ' . $duta['name'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.duta-genre.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-300 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali ke Daftar Duta GenRe</span>
        </a>
        <a href="{{ url('/duta-genre/' . $duta['slug']) }}" target="_blank" class="text-xs text-amber-300 hover:underline flex items-center gap-1 font-semibold">
            <span>Lihat Halaman Publik</span> &rarr;
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

    <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-amber-500/30 rounded-3xl p-8 shadow-2xl">
        <form method="POST" action="{{ route('admin.duta-genre.update', $duta['slug']) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Nama Lengkap <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $duta['name']) }}" required 
                           class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-amber-400 focus:outline-none">
                </div>

                <!-- Role / Title -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Identitas & Peran Duta <span class="text-rose-400">*</span></label>
                    <input type="text" name="role" value="{{ old('role', $duta['role'] ?? $duta['title']) }}" required 
                           class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-amber-400 focus:outline-none">
                </div>

                <!-- Focus -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Fokus Advokasi Edukasi <span class="text-rose-400">*</span></label>
                    <input type="text" name="focus" value="{{ old('focus', $duta['focus']) }}" required 
                           placeholder="Contoh: Perencanaan Pendidikan & Masa Depan Remaja"
                           class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-amber-400 focus:outline-none">
                </div>

                <!-- Summary -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Deskripsi Singkat (Tampil di Kartu Depan) <span class="text-rose-400">*</span></label>
                    <textarea name="summary" rows="3" required 
                              class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-amber-400 focus:outline-none">{{ old('summary', $duta['summary']) }}</textarea>
                </div>

                <!-- Biography -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Biografi & Narasi Lengkap <span class="text-rose-400">*</span></label>
                    <textarea name="bio" rows="5" required 
                              class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white leading-relaxed focus:border-amber-400 focus:outline-none">{{ old('bio', $duta['bio']) }}</textarea>
                </div>

                <!-- Quote / Inspiring Message -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Pesan Inspiratif (Kutipan Personal)</label>
                    <textarea name="quote" rows="2" 
                              placeholder="Kutipan motivasi atau pesan inspiratif dari Duta GenRe..."
                              class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white italic focus:border-amber-400 focus:outline-none">{{ old('quote', $duta['quote'] ?? '') }}</textarea>
                </div>

                <!-- Contributions -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">
                        Aksi Nyata & Kontribusi Edukasi <span class="text-gray-400 font-normal">(1 item per baris)</span>
                    </label>
                    <textarea name="contributions" rows="4" 
                              placeholder="Tuliskan tiap kontribusi pada baris baru..."
                              class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-amber-400 focus:outline-none">{{ old('contributions', implode("\n", $duta['contributions'] ?? [])) }}</textarea>
                </div>

                <!-- Achievements (Prestasi) -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-amber-300 mb-2">
                        Prestasi Terverifikasi <span class="text-gray-400 font-normal">(1 item per baris — jangan mengarang jika belum terkonfirmasi)</span>
                    </label>
                    <textarea name="achievements" rows="3" 
                              placeholder="Tuliskan capaian atau prestasi resmi yang sudah dikonfirmasi..."
                              class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-amber-400 focus:outline-none">{{ old('achievements', implode("\n", $duta['achievements'] ?? [])) }}</textarea>
                </div>

                <!-- Experiences (Pengalaman) -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-teal-300 mb-2">
                        Pengalaman & Rekam Jejak Kegiatan <span class="text-gray-400 font-normal">(1 item per baris)</span>
                    </label>
                    <textarea name="experiences" rows="3" 
                              placeholder="Tuliskan pengalaman sosialisasi atau kegiatan GenRe yang pernah diikuti..."
                              class="w-full bg-slate-900 border border-white/15 rounded-xl px-4 py-3 text-sm text-white focus:border-amber-400 focus:outline-none">{{ old('experiences', implode("\n", $duta['experiences'] ?? [])) }}</textarea>
                </div>

                <!-- Photo Upload / Replace -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Ganti Foto Personal</label>
                    <div class="flex items-center gap-6">
                        <div class="w-20 h-24 rounded-2xl overflow-hidden shrink-0 border-2 border-amber-400/40 bg-slate-900">
                            <img src="{{ str_starts_with($duta['photo'] ?? $duta['image'], '/') ? ($duta['photo'] ?? $duta['image']) : asset('images/' . $duta['image']) }}" 
                                 alt="{{ $duta['name'] }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <input type="file" name="photo_file" accept="image/*" 
                                   class="w-full text-xs text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-500/20 file:text-amber-300 hover:file:bg-amber-500/30">
                            <p class="text-[11px] text-gray-400 mt-1">Gunakan foto asli beresolusi baik, format JPG, PNG, WEBP (maks. 3MB)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6 border-t border-white/10 flex items-center justify-end gap-4">
                <a href="{{ route('admin.duta-genre.index') }}" class="px-6 py-3 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 text-xs font-bold uppercase tracking-wider transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-amber-400 to-yellow-500 hover:from-amber-300 hover:to-yellow-400 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg transition-all hover:scale-105">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
