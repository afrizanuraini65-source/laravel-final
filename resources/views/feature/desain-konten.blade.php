@extends('layouts.admin')

@section('title', 'Desain Konten - SKAKMATT')

@section('content')
<div x-data="desainKontenApp()" x-init="initApp()" class="space-y-6 relative pb-16">

    <!-- Top Header & Quick Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-[#0B1221]/90 p-6 rounded-3xl border border-purple-500/20 backdrop-blur-md shadow-xl">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-300 text-xs font-semibold mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Divisi PDD • Publikasi & Media
            </div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-pink-400 to-amber-300">
                Desain Konten & Poster
            </h2>
            <p class="text-gray-400 text-sm mt-1">Kelola pamflet, poster acara, dan media publikasi resmi PIK-R SKAKMATT.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <!-- Search bar -->
            <div class="relative flex-1 md:w-64">
                <input type="text" x-model="searchQuery" placeholder="Cari nama desain..." 
                       class="w-full bg-[#121B2D] border border-white/10 rounded-xl px-4 py-2.5 pl-10 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all">
                <svg class="w-4 h-4 text-gray-500 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <!-- Tombol Buat Desain Baru -->
            <button @click="openCreateModal()" 
                    class="px-5 py-2.5 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-400 hover:to-pink-400 text-white rounded-xl font-bold text-xs uppercase tracking-wider shadow-lg shadow-purple-500/30 transition-all hover:scale-105 flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Desain Baru
            </button>
        </div>
    </div>

    <!-- Filter/Tabs Kategori -->
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div class="flex gap-2 overflow-x-auto pb-1 [scrollbar-width:none]">
            <template x-for="cat in categories" :key="cat">
                <button @click="activeFilter = cat" 
                        :class="activeFilter === cat ? 'bg-purple-500/25 text-purple-200 border-purple-500/50 shadow-lg shadow-purple-500/10' : 'bg-white/5 text-gray-400 hover:text-purple-300 hover:bg-white/10 border-transparent'"
                        class="px-4 py-2 rounded-xl border font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2">
                    <span x-text="cat"></span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px]"
                          :class="activeFilter === cat ? 'bg-purple-400/30 text-purple-200' : 'bg-white/10 text-gray-400'"
                          x-text="getCategoryCount(cat)"></span>
                </button>
            </template>
        </div>

        <div class="text-xs text-gray-500 hidden sm:block">
            Menampilkan <span class="font-bold text-purple-400" x-text="filteredDesigns.length"></span> desain
        </div>
    </div>

    <!-- Empty State -->
    <div x-show="filteredDesigns.length === 0" 
         class="bg-[#0B1221]/50 border border-white/5 rounded-3xl p-12 text-center max-w-xl mx-auto my-8">
        <div class="w-16 h-16 rounded-2xl bg-purple-500/10 text-purple-400 flex items-center justify-center mx-auto mb-4 border border-purple-500/20">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-white mb-1">Tidak Ada Desain Ditemukan</h3>
        <p class="text-gray-400 text-xs mb-6">Belum ada konten pada kategori ini atau kata kunci pencarian Anda tidak cocok.</p>
        <button @click="openCreateModal()" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-500 text-white rounded-xl text-xs font-bold transition-all">
            + Tambah Desain Sekarang
        </button>
    </div>

    <!-- Design Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <template x-for="(item, index) in filteredDesigns" :key="item.id">
            <div class="group relative flex flex-col justify-between bg-[#0e172a] rounded-3xl p-3 border border-white/10 hover:border-purple-500/40 transition-all duration-300 shadow-xl hover:shadow-[0_10px_30px_rgba(168,85,247,0.15)]">
                
                <!-- Poster Box -->
                <div @click="openPreview(item)" 
                     class="aspect-[4/5] relative rounded-2xl overflow-hidden cursor-pointer group-hover:scale-[1.01] transition-transform duration-500 bg-[#070b19] border border-white/5">
                    
                    <!-- If has uploaded image or URL -->
                    <template x-if="item.image">
                        <img :src="item.image" :alt="item.title" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity">
                    </template>

                    <!-- Gradient Theme Poster if no image or fallback -->
                    <template x-if="!item.image">
                        <div class="w-full h-full p-6 flex flex-col justify-between relative overflow-hidden"
                             :class="{
                                 'bg-gradient-to-tr from-red-900 via-stone-900 to-black': item.theme === 'red',
                                 'bg-gradient-to-tr from-pink-900 via-purple-950 to-slate-900': item.theme === 'pink',
                                 'bg-gradient-to-tr from-cyan-950 via-slate-900 to-blue-950': item.theme === 'cyan',
                                 'bg-gradient-to-tr from-emerald-950 via-slate-900 to-teal-950': item.theme === 'emerald',
                                 'bg-gradient-to-tr from-purple-950 via-slate-900 to-[#0B1221]': !['red','pink','cyan','emerald'].includes(item.theme)
                             }">
                            <div class="flex justify-between items-start">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase bg-white/10 text-white backdrop-blur-md" x-text="item.category"></span>
                                <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            </div>
                            <div class="my-auto text-center py-6">
                                <h3 class="text-2xl font-black text-white uppercase tracking-tight drop-shadow-md" x-text="item.subtitle || item.title"></h3>
                                <p class="text-xs text-white/70 mt-2 font-medium" x-text="item.date"></p>
                            </div>
                            <div class="text-[10px] text-white/50 tracking-widest uppercase font-semibold text-center border-t border-white/10 pt-3">
                                PIK-R SKAKMATT
                            </div>
                        </div>
                    </template>

                    <!-- Hover Quick Preview Hint -->
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 backdrop-blur-[2px]">
                        <span class="px-3.5 py-1.5 bg-white/20 hover:bg-white/30 text-white rounded-full text-xs font-bold backdrop-blur-md flex items-center gap-1.5 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Klik untuk Detail
                        </span>
                    </div>
                </div>

                <!-- Card Content & Actions -->
                <div class="mt-4 px-2 pb-1">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h4 class="font-bold text-white text-base leading-snug line-clamp-1" x-text="item.title"></h4>
                            <p class="text-[11px] text-gray-400 mt-0.5" x-text="item.category + (item.date ? ' • ' + item.date : '')"></p>
                        </div>
                        
                        <!-- Toggle Status Badge -->
                        <button @click.stop="toggleStatus(item)" 
                                :title="'Klik untuk mengubah ke ' + (item.status === 'Published' ? 'Draft' : 'Published')"
                                class="shrink-0 text-[10px] font-bold px-2 py-0.5 rounded-md transition-all cursor-pointer flex items-center gap-1"
                                :class="item.status === 'Published' ? 'text-emerald-400 bg-emerald-500/10 border border-emerald-500/30 hover:bg-emerald-500/20' : 'text-amber-400 bg-amber-500/10 border border-amber-500/30 hover:bg-amber-500/20'">
                            <span class="w-1.5 h-1.5 rounded-full" :class="item.status === 'Published' ? 'bg-emerald-400' : 'bg-amber-400'"></span>
                            <span x-text="item.status"></span>
                        </button>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2 mt-4 pt-3 border-t border-white/5">
                        
                        <!-- Button 1: Unduh (PNG) atau Preview -->
                        <template x-if="item.status === 'Published'">
                            <button @click="downloadPoster(item)" 
                                    title="Unduh poster dalam format gambar PNG"
                                    class="flex-1 py-2 px-3 text-xs font-bold text-white bg-white/5 hover:bg-white/15 rounded-xl transition-all border border-white/10 flex items-center justify-center gap-1.5 group-hover:border-purple-500/30">
                                <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Unduh (PNG)
                            </button>
                        </template>

                        <template x-if="item.status === 'Draft'">
                            <button @click="openPreview(item)" 
                                    title="Lihat pratinjau desain"
                                    class="flex-1 py-2 px-3 text-xs font-bold text-white bg-white/5 hover:bg-white/15 rounded-xl transition-all border border-white/10 flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Preview
                            </button>
                        </template>

                        <!-- Button 2: Edit di Canva -->
                        <button @click="openCanva(item)" 
                                title="Buka tautan proyek di Canva"
                                class="flex-1 py-2 px-3 text-xs font-bold text-purple-300 bg-purple-500/20 hover:bg-purple-500/30 rounded-xl transition-all border border-purple-500/30 flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-pink-400" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-9l6 4.5-6 4.5z"/></svg>
                            Edit di Canva
                        </button>

                        <!-- Quick Edit / Delete Buttons -->
                        <button @click="openEditModal(item)" 
                                title="Edit data desain" 
                                class="p-2 rounded-xl bg-white/5 hover:bg-purple-500/20 text-gray-400 hover:text-purple-300 transition-colors border border-white/5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>

                        <button @click="deleteDesign(item)" 
                                title="Hapus desain" 
                                class="p-2 rounded-xl bg-white/5 hover:bg-red-500/20 text-gray-400 hover:text-red-400 transition-colors border border-white/5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>

            </div>
        </template>
    </div>

    <!-- MODAL: BUAT / EDIT DESAIN BARU -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md" 
         style="display: none;" 
         @keydown.escape.window="showModal = false">
        
        <div @click.away="showModal = false" 
             class="relative max-w-xl w-full bg-[#0F172A] border border-purple-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl overflow-y-auto max-h-[90vh] space-y-6">
            
            <div class="flex justify-between items-center border-b border-white/10 pb-4">
                <div>
                    <h3 class="text-xl font-bold text-white" x-text="isEditing ? 'Edit Desain & Poster' : 'Buat Desain Baru'"></h3>
                    <p class="text-xs text-gray-400 mt-0.5">Isi detail aset publikasi untuk divisi PDD.</p>
                </div>
                <button @click="showModal = false" class="text-gray-400 hover:text-white text-lg font-bold p-1">&times;</button>
            </div>

            <form @submit.prevent="saveDesign()" class="space-y-4">
                
                <!-- Judul Desain -->
                <div>
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Judul Desain / Pamflet *</label>
                    <input type="text" x-model="form.title" required placeholder="Contoh: Poster Hari Sumpah Pemuda" 
                           class="w-full bg-[#1E293B] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-purple-400 transition-all">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kategori -->
                    <div>
                        <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Kategori *</label>
                        <select x-model="form.category" required 
                                class="w-full bg-[#1E293B] border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-purple-400 transition-all">
                            <option value="Peringatan Hari Besar">Peringatan Hari Besar</option>
                            <option value="Event">Event</option>
                            <option value="Edukasi KRR">Edukasi KRR</option>
                            <option value="Quiz Fun">Quiz Fun</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Status Publikasi</label>
                        <select x-model="form.status" 
                                class="w-full bg-[#1E293B] border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-purple-400 transition-all">
                            <option value="Draft">Draft (Tahap Konsep / Review)</option>
                            <option value="Published">Published (Siap Publikasi)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Tanggal / Waktu Event -->
                    <div>
                        <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Tanggal / Subtitle</label>
                        <input type="text" x-model="form.date" placeholder="Contoh: 28 Oktober 2026" 
                               class="w-full bg-[#1E293B] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-purple-400 transition-all">
                    </div>

                    <!-- Pilihan Tema Poster (Jika tanpa gambar) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Warna Tema Grafis</label>
                        <select x-model="form.theme" 
                                class="w-full bg-[#1E293B] border border-white/10 rounded-xl px-3 py-2.5 text-sm text-white focus:outline-none focus:border-purple-400 transition-all">
                            <option value="purple">Ungu (Default Kreatif)</option>
                            <option value="red">Merah (Hari Nasional)</option>
                            <option value="pink">Pink Magenta (KRR / Edukasi)</option>
                            <option value="cyan">Biru Cyan (Event & Oprec)</option>
                            <option value="emerald">Hijau Emerald (Kesehatan)</option>
                        </select>
                    </div>
                </div>

                <!-- Link Canva -->
                <div>
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Tautan Canva (Project Link)</label>
                    <div class="relative">
                        <input type="url" x-model="form.canvaUrl" placeholder="https://www.canva.com/design/..." 
                               class="w-full bg-[#1E293B] border border-white/10 rounded-xl px-4 py-2.5 pl-10 text-sm text-white focus:outline-none focus:border-purple-400 transition-all">
                        <svg class="w-4 h-4 text-purple-400 absolute left-3.5 top-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-9l6 4.5-6 4.5z"/></svg>
                    </div>
                </div>

                <!-- Upload Gambar atau URL Gambar -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider">Gambar / Poster Desain</label>
                    
                    <div class="flex flex-col sm:flex-row gap-3 items-center">
                        <!-- File Upload -->
                        <label class="w-full sm:w-auto px-4 py-2.5 bg-purple-500/20 hover:bg-purple-500/30 text-purple-200 border border-purple-500/30 rounded-xl text-xs font-bold cursor-pointer text-center flex items-center justify-center gap-2 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Unggah dari Perangkat
                            <input type="file" accept="image/*" @change="handleFileUpload($event)" class="hidden">
                        </label>

                        <span class="text-xs text-gray-500">atau masukkan URL gambar:</span>
                    </div>

                    <input type="url" x-model="form.image" placeholder="https://..." 
                           class="w-full bg-[#1E293B] border border-white/10 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-purple-400 transition-all">

                    <!-- Image preview if any -->
                    <template x-if="form.image">
                        <div class="mt-2 flex items-center gap-3 bg-black/30 p-2 rounded-xl border border-white/5">
                            <img :src="form.image" alt="Preview" class="w-12 h-16 object-cover rounded-lg border border-white/10">
                            <div class="flex-1 min-w-0">
                                <span class="text-xs text-emerald-400 font-bold block">Gambar Terpasang</span>
                                <span class="text-[10px] text-gray-400 truncate block" x-text="form.image.substring(0, 45) + '...'"></span>
                            </div>
                            <button type="button" @click="form.image = ''" class="text-xs text-red-400 hover:text-red-300 font-bold px-2 py-1">Hapus</button>
                        </div>
                    </template>
                </div>

                <!-- Deskripsi / Catatan -->
                <div>
                    <label class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Deskripsi / Catatan Publikasi</label>
                    <textarea x-model="form.description" rows="2" placeholder="Tuliskan catatan postingan atau caption..." 
                              class="w-full bg-[#1E293B] border border-white/10 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-purple-400 transition-all"></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                    <button type="button" @click="showModal = false" class="px-5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 text-xs font-bold transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-400 hover:to-pink-400 text-white text-xs font-bold shadow-lg shadow-purple-500/30 transition-all">
                        Simpan Desain
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- MODAL: PREVIEW LENGKAP DESAIN -->
    <div x-show="showPreviewModal && previewItem" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-md" 
         style="display: none;" 
         @keydown.escape.window="showPreviewModal = false">
        
        <div @click.away="showPreviewModal = false" 
             class="relative max-w-4xl w-full bg-[#0F172A] border border-purple-500/30 rounded-3xl p-6 sm:p-8 shadow-2xl overflow-y-auto max-h-[90vh]">
            
            <button @click="showPreviewModal = false" class="absolute top-5 right-5 text-gray-400 hover:text-white text-xl font-bold p-1 z-10">&times;</button>

            <template x-if="previewItem">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    
                    <!-- Left: Poster Preview Canvas/Image -->
                    <div class="aspect-[4/5] rounded-2xl overflow-hidden bg-black/40 border border-white/10 shadow-2xl relative flex items-center justify-center">
                        <template x-if="previewItem.image">
                            <img :src="previewItem.image" :alt="previewItem.title" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!previewItem.image">
                            <div class="w-full h-full p-8 flex flex-col justify-between"
                                 :class="{
                                     'bg-gradient-to-tr from-red-900 via-stone-900 to-black': previewItem.theme === 'red',
                                     'bg-gradient-to-tr from-pink-900 via-purple-950 to-slate-900': previewItem.theme === 'pink',
                                     'bg-gradient-to-tr from-cyan-950 via-slate-900 to-blue-950': previewItem.theme === 'cyan',
                                     'bg-gradient-to-tr from-emerald-950 via-slate-900 to-teal-950': previewItem.theme === 'emerald',
                                     'bg-gradient-to-tr from-purple-950 via-slate-900 to-[#0B1221]': !['red','pink','cyan','emerald'].includes(previewItem.theme)
                                 }">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/10 text-white w-max" x-text="previewItem.category"></span>
                                <div class="text-center my-auto">
                                    <h2 class="text-3xl font-black text-white uppercase drop-shadow-lg" x-text="previewItem.subtitle || previewItem.title"></h2>
                                    <p class="text-sm text-white/80 mt-2 font-medium" x-text="previewItem.date"></p>
                                </div>
                                <div class="text-xs text-white/50 tracking-widest text-center uppercase border-t border-white/10 pt-4">
                                    PIK-R SKAKMATT SMKN 1 PURWOKERTO
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Right: Detail & Actions -->
                    <div class="space-y-6">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2.5 py-0.5 rounded-md text-xs font-bold uppercase bg-purple-500/20 text-purple-300 border border-purple-500/30" x-text="previewItem.category"></span>
                                <span class="px-2.5 py-0.5 rounded-md text-xs font-bold"
                                      :class="previewItem.status === 'Published' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30'"
                                      x-text="previewItem.status"></span>
                            </div>
                            <h3 class="text-2xl font-black text-white" x-text="previewItem.title"></h3>
                            <p class="text-sm text-gray-400 mt-1" x-text="previewItem.date ? 'Target Rilis / Tanggal: ' + previewItem.date : 'Belum ada tanggal'"></p>
                        </div>

                        <div class="bg-black/30 p-4 rounded-2xl border border-white/5 space-y-2">
                            <h5 class="text-xs font-bold text-gray-300 uppercase tracking-wider">Catatan Publikasi:</h5>
                            <p class="text-xs text-gray-300 leading-relaxed" x-text="previewItem.description || 'Tidak ada deskripsi tambahan untuk desain ini.'"></p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-2">
                            <button @click="downloadPoster(previewItem)" 
                                    class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Unduh File Desain (PNG)
                            </button>

                            <button @click="openCanva(previewItem)" 
                                    class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-400 hover:to-pink-400 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-purple-500/20 transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14.5v-9l6 4.5-6 4.5z"/></svg>
                                Buka di Canva
                            </button>

                            <div class="grid grid-cols-2 gap-3 pt-2">
                                <button @click="toggleStatus(previewItem)" 
                                        class="py-2.5 px-3 rounded-xl bg-white/5 hover:bg-white/10 text-white text-xs font-bold transition-all border border-white/10 flex items-center justify-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full" :class="previewItem.status === 'Published' ? 'bg-amber-400' : 'bg-emerald-400'"></span>
                                    <span x-text="previewItem.status === 'Published' ? 'Ubah ke Draft' : 'Ubah ke Published'"></span>
                                </button>

                                <button @click="showPreviewModal = false; openEditModal(previewItem)" 
                                        class="py-2.5 px-3 rounded-xl bg-white/5 hover:bg-purple-500/20 text-purple-300 text-xs font-bold transition-all border border-white/10 flex items-center justify-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    Edit Detail
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </template>

        </div>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-4"
         class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-5 py-3 rounded-2xl shadow-2xl backdrop-blur-xl border text-sm font-bold"
         :class="{
             'bg-emerald-950/90 text-emerald-200 border-emerald-500/30': toast.type === 'success',
             'bg-purple-950/90 text-purple-200 border-purple-500/30': toast.type === 'info',
             'bg-red-950/90 text-red-200 border-red-500/30': toast.type === 'error'
         }"
         style="display: none;">
        <span x-text="toast.message"></span>
    </div>

</div>

<!-- SCRIPT: LOGIKA FITUR DESAIN KONTEN & MEDIA -->
<script>
function desainKontenApp() {
    return {
        activeFilter: 'Semua Desain',
        searchQuery: '',
        showModal: false,
        showPreviewModal: false,
        isEditing: false,
        previewItem: null,
        categories: ['Semua Desain', 'Peringatan Hari Besar', 'Event', 'Edukasi KRR', 'Quiz Fun'],
        toast: { show: false, message: '', type: 'success' },
        toastTimeout: null,
        form: {
            id: null,
            title: '',
            category: 'Peringatan Hari Besar',
            subtitle: '',
            date: '',
            status: 'Draft',
            canvaUrl: '',
            image: '',
            theme: 'purple',
            description: ''
        },
        defaultDesigns: [
            {
                id: 1,
                title: 'Rabu Kuis',
                subtitle: 'Rabu Kuis',
                category: 'Quiz Fun',
                date: 'Oktober–Desember 2026',
                status: 'Published',
                canvaUrl: 'https://www.canva.com/design/DAGq8EcYmiI/BnU3M7dUcYFtu_jCB6IPxg/edit',
                image: '/images/Rabu kuis.png',
            },
            {
                id: 2,
                title: 'Hari Pahlawan',
                subtitle: 'Hari Pahlawan',
                category: 'Peringatan Hari Besar',
                date: '10 November 2026',
                status: 'Published',
                canvaUrl: 'https://www.canva.com/design/DAG4A9wBSsU/sT6huTaTnI46ElNNiYbbiw/edit',
                image: '/images/hari pahlawan.png',
            },
            {
                id: 3,
                title: 'Purna Tugas Angkatan VIII',
                subtitle: 'Purna Tugas Angkatan VIII',
                category: 'Event',
                date: 'Oktober 2026',
                status: 'Published',
                canvaUrl: 'https://www.canva.com/design/DAG10JvML5Q/zK3qbyoiVywdUqFPIHbqBw/edit?utm_content=DAG10JvML5Q&utm_campaign=designshare&utm_medium=link2&utm_source=sharebutton',
                image: '/images/Purna Tugas.png',
            },
            {
                id: 4,
                title: 'Hari Santri',
                subtitle: 'Hari Santri Nasional',
                category: 'Peringatan Hari Besar',
                date: '22 Oktober 2026',
                status: 'Published',
                canvaUrl: 'https://canva.link/ltz0f00xrxit30m',
                image: '/images/Hari Santri Nasional.png',
            },
            {
                id: 5,
                title: 'Sumpah Pemuda',
                subtitle: 'Hari Sumpah Pemuda',
                category: 'Peringatan Hari Besar',
                date: '28 Oktober 2026',
                status: 'Published',
                canvaUrl: 'https://www.canva.com/design/DAHYb15nvw8/LhxsMygyPNwcWcc4tVxM3w/edit?utm_content=DAHYb15nvw8&utm_campaign=designshare&utm_medium=link2&utm_source=sharebutton',
                image: '/images/Sumpah Pemuda.png',
            }
        ],
        designs: [],

        initApp() {
            const saved = localStorage.getItem('pikr_pdd_designs_v5');
            if (saved) {
                try {
                    this.designs = JSON.parse(saved);
                } catch (e) {
                    this.designs = [...this.defaultDesigns];
                }
            } else {
                const oldSaved = localStorage.getItem('pikr_pdd_designs_v4');
                let customDesigns = [];
                if (oldSaved) {
                    try {
                        const parsedOld = JSON.parse(oldSaved);
                        customDesigns = parsedOld.filter(d => d.id > 10 && !this.defaultDesigns.some(def => def.id === d.id));
                    } catch (e) {}
                }
                this.designs = [...this.defaultDesigns, ...customDesigns];
                this.saveStorage();
            }
        },

        saveStorage() {
            localStorage.setItem('pikr_pdd_designs_v5', JSON.stringify(this.designs));
        },

        get filteredDesigns() {
            return this.designs.filter(item => {
                const matchFilter = this.activeFilter === 'Semua Desain' || item.category === this.activeFilter;
                const query = this.searchQuery.trim().toLowerCase();
                const matchSearch = query === '' || 
                                    item.title.toLowerCase().includes(query) || 
                                    (item.subtitle && item.subtitle.toLowerCase().includes(query)) ||
                                    item.category.toLowerCase().includes(query);
                return matchFilter && matchSearch;
            });
        },

        getCategoryCount(cat) {
            if (cat === 'Semua Desain') return this.designs.length;
            return this.designs.filter(d => d.category === cat).length;
        },

        showToast(message, type = 'success') {
            this.toast = { show: true, message, type };
            if (this.toastTimeout) clearTimeout(this.toastTimeout);
            this.toastTimeout = setTimeout(() => {
                this.toast.show = false;
            }, 3000);
        },

        openCreateModal() {
            this.isEditing = false;
            this.form = {
                id: Date.now(),
                title: '',
                category: this.activeFilter !== 'Semua Desain' ? this.activeFilter : 'Peringatan Hari Besar',
                subtitle: '',
                date: '',
                status: 'Draft',
                canvaUrl: 'https://www.canva.com',
                image: '',
                theme: 'purple',
                description: ''
            };
            this.showModal = true;
        },

        openEditModal(item) {
            this.isEditing = true;
            this.form = JSON.parse(JSON.stringify(item));
            this.showModal = true;
        },

        handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                this.form.image = e.target.result;
                this.showToast('Gambar berhasil dimuat!', 'info');
            };
            reader.readAsDataURL(file);
        },

        saveDesign() {
            if (!this.form.title.trim()) {
                this.showToast('Judul desain wajib diisi!', 'error');
                return;
            }

            if (!this.form.subtitle) {
                this.form.subtitle = this.form.title;
            }

            if (this.isEditing) {
                const idx = this.designs.findIndex(d => d.id === this.form.id);
                if (idx !== -1) {
                    this.designs[idx] = { ...this.form };
                    if (this.previewItem && this.previewItem.id === this.form.id) {
                        this.previewItem = { ...this.form };
                    }
                    this.showToast('Desain berhasil diperbarui!', 'success');
                }
            } else {
                this.designs.unshift({ ...this.form });
                this.showToast('Desain baru berhasil ditambahkan!', 'success');
            }

            this.saveStorage();
            this.showModal = false;
        },

        deleteDesign(item) {
            if (confirm(`Hapus desain "${item.title}"?`)) {
                this.designs = this.designs.filter(d => d.id !== item.id);
                this.saveStorage();
                if (this.previewItem && this.previewItem.id === item.id) {
                    this.showPreviewModal = false;
                    this.previewItem = null;
                }
                this.showToast('Desain berhasil dihapus.', 'info');
            }
        },

        toggleStatus(item) {
            item.status = item.status === 'Published' ? 'Draft' : 'Published';
            this.saveStorage();
            this.showToast(`Status diubah ke ${item.status}`, 'success');
        },

        openPreview(item) {
            this.previewItem = item;
            this.showPreviewModal = true;
        },

        openCanva(item) {
            let url = item.canvaUrl ? item.canvaUrl.trim() : '';
            if (!url) {
                url = 'https://www.canva.com';
            }
            if (!url.startsWith('http://') && !url.startsWith('https://')) {
                url = 'https://' + url;
            }
            this.showToast('Membuka Canva...', 'info');
            window.open(url, '_blank');
        },

        downloadPoster(item) {
            this.showToast('Memproses file unduhan PNG...', 'info');

            // Jika gambar adalah data base64 lokal
            if (item.image && item.image.startsWith('data:image')) {
                const a = document.createElement('a');
                a.href = item.image;
                a.download = (item.title || 'Poster').replace(/[^a-zA-Z0-9_-]/g, '_') + '.png';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                this.showToast('Poster berhasil diunduh!', 'success');
                return;
            }

            // Gunakan HTML5 Canvas untuk menghasilkan file PNG resolusi tinggi
            const canvas = document.createElement('canvas');
            canvas.width = 1080;
            canvas.height = 1350;
            const ctx = canvas.getContext('2d');

            // Background Gradient
            const grad = ctx.createLinearGradient(0, 0, 1080, 1350);
            if (item.theme === 'red') {
                grad.addColorStop(0, '#991b1b');
                grad.addColorStop(0.6, '#450a0a');
                grad.addColorStop(1, '#0f172a');
            } else if (item.theme === 'pink') {
                grad.addColorStop(0, '#9d174d');
                grad.addColorStop(0.6, '#581c87');
                grad.addColorStop(1, '#0f172a');
            } else if (item.theme === 'cyan') {
                grad.addColorStop(0, '#0e7490');
                grad.addColorStop(0.6, '#1e293b');
                grad.addColorStop(1, '#070b19');
            } else if (item.theme === 'emerald') {
                grad.addColorStop(0, '#047857');
                grad.addColorStop(0.6, '#064e3b');
                grad.addColorStop(1, '#070b19');
            } else {
                grad.addColorStop(0, '#6b21a8');
                grad.addColorStop(0.6, '#3b0764');
                grad.addColorStop(1, '#070b19');
            }
            ctx.fillStyle = grad;
            ctx.fillRect(0, 0, 1080, 1350);

            const renderFinalAndDownload = () => {
                // Bingkai Poster Elegan
                ctx.strokeStyle = 'rgba(255, 255, 255, 0.25)';
                ctx.lineWidth = 6;
                ctx.strokeRect(50, 50, 980, 1250);

                // Watermark / Header Pill
                ctx.fillStyle = 'rgba(255, 255, 255, 0.15)';
                if (ctx.roundRect) {
                    ctx.roundRect(80, 80, 360, 60, 30);
                } else {
                    ctx.fillRect(80, 80, 360, 60);
                }
                ctx.fill();

                ctx.fillStyle = '#ffffff';
                ctx.font = 'bold 24px sans-serif';
                ctx.fillText('PIK-R SKAKMATT', 115, 118);

                // Category
                ctx.fillStyle = '#f472b6';
                ctx.font = 'bold 30px sans-serif';
                ctx.fillText((item.category || 'PUBLIKASI').toUpperCase(), 80, 780);

                // Title
                ctx.fillStyle = '#ffffff';
                ctx.font = 'bold 64px sans-serif';
                const displayText = (item.subtitle || item.title || '').toUpperCase();
                const words = displayText.split(' ');
                let line = '';
                let y = 860;
                for (let i = 0; i < words.length; i++) {
                    const testLine = line + words[i] + ' ';
                    const metrics = ctx.measureText(testLine);
                    if (metrics.width > 900 && i > 0) {
                        ctx.fillText(line, 80, y);
                        line = words[i] + ' ';
                        y += 75;
                    } else {
                        line = testLine;
                    }
                }
                ctx.fillText(line, 80, y);

                // Subtitle / Date
                if (item.date) {
                    ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
                    ctx.font = '32px sans-serif';
                    ctx.fillText(item.date, 80, y + 65);
                }

                // Footer
                ctx.fillStyle = 'rgba(255, 255, 255, 0.5)';
                ctx.font = '22px sans-serif';
                ctx.fillText('SMK NEGERI 1 PURWOKERTO • DIVISI PDD', 80, 1250);

                // Trigger Download
                try {
                    const link = document.createElement('a');
                    link.download = (item.title || 'Poster').replace(/[^a-zA-Z0-9_-]/g, '_') + '.png';
                    link.href = canvas.toDataURL('image/png');
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    this.showToast('Poster berhasil diunduh sebagai PNG!', 'success');
                } catch (err) {
                    if (item.image) {
                        window.open(item.image, '_blank');
                    }
                }
            };

            // Jika ada gambar eksternal, coba load ke canvas
            if (item.image && item.image.startsWith('http')) {
                const img = new Image();
                img.crossOrigin = 'anonymous';
                img.onload = () => {
                    ctx.drawImage(img, 0, 0, 1080, 1350);
                    ctx.fillStyle = 'rgba(0, 0, 0, 0.45)';
                    ctx.fillRect(0, 0, 1080, 1350);
                    renderFinalAndDownload();
                };
                img.onerror = () => {
                    renderFinalAndDownload();
                };
                img.src = item.image;
            } else {
                renderFinalAndDownload();
            }
        }
    };
}
</script>
@endsection
