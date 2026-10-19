@extends('layouts.admin')

@section('title', 'Sistem Surat & Dokumen Resmi - PIK-R SKAKMATT')

@section('header', 'Surat & Dokumen Resmi')

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printable-document, #printable-document * {
            visibility: visible;
        }
        #printable-document {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 20mm;
            box-shadow: none !important;
            border: none !important;
            background: white !important;
            color: black !important;
        }
        .no-print {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-8 pb-20">

    <!-- Header & Quick Actions (Screen only) -->
    <div class="no-print flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-slate-900/40 p-6 rounded-3xl border border-slate-700/50 backdrop-blur-md">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-semibold mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Kesekretariatan & Humas
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Format <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-cyan-300">Surat & Dokumen Resmi</span></h1>
            <p class="text-slate-400 text-sm mt-1">Standar format kop surat, daftar hadir, surat dispensasi, dan surat izin orang tua PIK-R SKAKMATT.</p>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-bold text-sm shadow-lg shadow-emerald-500/20 flex items-center gap-2 transition-all hover:scale-105">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Navigation Tabs (Screen only) -->
    <div class="no-print flex overflow-x-auto gap-2 p-1.5 bg-slate-900/60 rounded-2xl border border-slate-700/50 backdrop-blur-md">
        <a href="{{ route('feature.surat_dokumen', ['tab' => 'daftar-hadir']) }}" 
           class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ $activeTab === 'daftar-hadir' ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/20 text-emerald-300 border border-emerald-500/40 shadow-lg shadow-emerald-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            1. Daftar Hadir Kelas / Kegiatan
        </a>

        <a href="{{ route('feature.surat_dokumen', ['tab' => 'dispensasi']) }}" 
           class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ $activeTab === 'dispensasi' ? 'bg-gradient-to-r from-cyan-500/20 to-blue-500/20 text-cyan-300 border border-cyan-500/40 shadow-lg shadow-cyan-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            2. Surat Dispensasi Siswa
        </a>

        <a href="{{ route('feature.surat_dokumen', ['tab' => 'izin-ortu']) }}" 
           class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ $activeTab === 'izin-ortu' ? 'bg-gradient-to-r from-amber-500/20 to-yellow-500/20 text-amber-300 border border-amber-500/40 shadow-lg shadow-amber-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            3. Surat Izin Orang Tua / Wali
        </a>

        <a href="{{ route('feature.surat_dokumen', ['tab' => 'arsip']) }}" 
           class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ $activeTab === 'arsip' ? 'bg-gradient-to-r from-purple-500/20 to-indigo-500/20 text-purple-300 border border-purple-500/40 shadow-lg shadow-purple-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            4. Arsip & Penomoran Surat
        </a>
    </div>

    <!-- Interactive Config Panel (Screen only) -->
    <div class="no-print bg-slate-900/50 p-6 rounded-3xl border border-slate-700/50 shadow-xl backdrop-blur-md">
        @if($activeTab === 'daftar-hadir')
            <form method="GET" action="{{ route('feature.surat_dokumen') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs">
                <input type="hidden" name="tab" value="daftar-hadir">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Filter Kelas</label>
                    <select name="kelas" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-emerald-500">
                        <option value="">-- Semua Kelas (Total: {{ \App\Models\Member::count() }}) --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k }}" {{ $selectedKelas === $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Hari & Tanggal</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" name="meta_hari" value="{{ $metaHari }}" class="bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-emerald-500" placeholder="Hari">
                        <input type="text" name="meta_tanggal" value="{{ $metaTanggal }}" class="bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-emerald-500" placeholder="Tanggal">
                    </div>
                </div>
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Tempat & Kegiatan</label>
                    <input type="text" name="meta_tempat" value="{{ $metaTempat }}" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-emerald-500 mb-1" placeholder="Tempat">
                    <input type="text" name="meta_kegiatan" value="{{ $metaKegiatan }}" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-emerald-500" placeholder="Nama Kegiatan">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl transition-colors">
                        Perbarui Pratinjau Dokumen
                    </button>
                </div>
            </form>
        @elseif($activeTab === 'dispensasi')
            <form method="GET" action="{{ route('feature.surat_dokumen') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <input type="hidden" name="tab" value="dispensasi">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Nomor Surat Dispensasi</label>
                    <input type="text" name="nomor_dispensasi" value="{{ $nomorDispensasi }}" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Nama Kegiatan</label>
                    <input type="text" name="kegiatan_dispensasi" value="{{ $kegiatanDispensasi }}" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Hari & Tanggal Pelaksanaan</label>
                    <input type="text" name="tgl_dispensasi" value="{{ $tglDispensasi }}" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-cyan-500">
                </div>
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Waktu & Tempat</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" name="waktu_dispensasi" value="{{ $waktuDispensasi }}" class="bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-cyan-500" placeholder="Waktu">
                        <input type="text" name="tempat_dispensasi" value="{{ $tempatDispensasi }}" class="bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-cyan-500" placeholder="Tempat">
                    </div>
                </div>
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Pejabat Pengesah (Sesuai Ketentuan)</label>
                    <input type="text" name="nama_waka" value="{{ $namaWaka }}" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-cyan-500 mb-1" placeholder="Waka Kesiswaan (Pak Sugeng)">
                    <input type="text" name="nama_pembina" value="{{ $namaPembina }}" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-cyan-500" placeholder="Pembina PIK-R">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full py-2.5 px-4 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-xl transition-colors">
                        Perbarui Surat Dispensasi
                    </button>
                </div>
            </form>
        @elseif($activeTab === 'izin-ortu')
            <form method="GET" action="{{ route('feature.surat_dokumen') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <input type="hidden" name="tab" value="izin-ortu">
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Nomor Surat Keterangan / Izin</label>
                    <input type="text" name="nomor_izin_ortu" value="{{ $nomorIzinOrtu }}" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Nama Kegiatan</label>
                    <input type="text" name="kegiatan_izin" value="{{ $kegiatanIzin }}" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-slate-300 font-semibold mb-1">Waktu & Tempat Kegiatan</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" name="waktu_izin" value="{{ $waktuIzin }}" class="bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-amber-500" placeholder="Waktu">
                        <input type="text" name="tempat_izin" value="{{ $tempatIzin }}" class="bg-slate-800 border border-slate-700 rounded-xl px-3 py-2 text-white outline-none focus:border-amber-500" placeholder="Tempat">
                    </div>
                </div>
                <div class="md:col-span-3 flex justify-end">
                    <button type="submit" class="py-2.5 px-6 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-xl transition-colors">
                        Perbarui Surat Izin Orang Tua
                    </button>
                </div>
            </form>
        @elseif($activeTab === 'arsip')
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-xs">
                <div class="text-slate-300">
                    <span class="font-bold text-white text-sm">Rekapitulasi Surat Keluar & Masuk</span>
                    <p class="text-slate-400 mt-0.5">Penomoran kode otomatis: <code>/SKAKMATT/DISP/</code> (Dispensasi), <code>/SKAKMATT/IZIN-ORTU/</code> (Izin Ortu), <code>/SKAKMATT/UND/</code> (Undangan).</p>
                </div>
                <button type="button" onclick="alert('Fitur tambah nomor surat baru siap digunakan pada agenda surat tahun berjalan 2025/2026.')" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white font-bold rounded-xl transition-colors">
                    + Tambah Registrasi Surat Baru
                </button>
            </div>
        @endif
    </div>

    @if($activeTab !== 'arsip')
    <!-- ========================================================================= -->
    <!-- DOCUMENT PAPER CONTAINER (PRINT READY & PREVIEW)                           -->
    <!-- ========================================================================= -->
    <div id="printable-document" class="max-w-4xl mx-auto bg-white text-black p-8 sm:p-14 rounded-2xl shadow-2xl font-serif text-[12px] leading-relaxed transition-all">
        
        <!-- KOP SURAT RESMI (Sesuai Gambar 1 & 2) -->
        <div class="border-b-[3px] border-double border-black pb-3 mb-6">
            <div class="flex items-center justify-between gap-4">
                <!-- Logo SMK N 1 Purwokerto (Kiri) -->
                <div class="w-20 shrink-0 flex justify-center items-center">
                    <img src="/images/lgo_smc.png" alt="Logo SMK N 1 Purwokerto" class="w-16 h-16 object-contain" onerror="this.style.display='none'">
                </div>

                <!-- Teks Kop Surat -->
                <div class="text-center flex-1">
                    <h3 class="text-base font-bold uppercase tracking-wider text-black leading-tight">
                        EKSTRAKURIKULER
                    </h3>
                    <h2 class="text-xl font-black uppercase tracking-wider text-black leading-tight mt-0.5">
                        PIK-R SKAKMATT
                    </h2>
                    <h2 class="text-lg font-bold uppercase tracking-wider text-black leading-tight mt-0.5">
                        SMK NEGERI 1 PURWOKERTO
                    </h2>
                    <p class="text-[10px] text-black mt-1 leading-normal italic font-sans">
                        Jl. Dr. Soeparno No. 29 Telp/ Fax. (0281) 637132 Purwokerto 53111
                    </p>
                </div>

                <!-- Logo PIK-R SKAKMATT (Kanan) -->
                <div class="w-20 shrink-0 flex justify-center items-center">
                    <img src="/images/lgo_pikr.png" alt="Logo PIK-R SKAKMATT" class="w-16 h-16 object-contain" onerror="this.style.display='none'">
                </div>
            </div>
        </div>

        @if($activeTab === 'daftar-hadir')
            <!-- 1. DAFTAR HADIR ANGGOTA / KEGIATAN -->
            <div class="text-center mb-6">
                <h1 class="text-sm sm:text-base font-bold uppercase tracking-wider leading-snug">
                    DAFTAR HADIR KELAS ANGGOTA
                </h1>
                <h2 class="text-xs sm:text-sm font-bold uppercase tracking-wider mt-0.5">
                    EKSTRAKURIKULER PIK-R SKAKMATT SMK NEGERI 1 PURWOKERTO
                </h2>
                <h2 class="text-xs sm:text-sm font-bold uppercase tracking-wider mt-0.5">
                    TAHUN PELAJARAN {{ $metaTahunAjaran }}
                </h2>
            </div>

            <!-- METADATA KEGIATAN -->
            <div class="grid grid-cols-2 gap-x-8 gap-y-1 mb-5 text-[11px] font-sans">
                <div class="flex items-center">
                    <span class="w-28 font-bold">Hari</span>
                    <span>: {{ $metaHari }}</span>
                </div>
                <div class="flex items-center">
                    <span class="w-28 font-bold">Tempat</span>
                    <span>: {{ $metaTempat }}</span>
                </div>
                <div class="flex items-center">
                    <span class="w-28 font-bold">Tanggal</span>
                    <span>: {{ $metaTanggal }}</span>
                </div>
                <div class="flex items-center">
                    <span class="w-28 font-bold">Jenis Kegiatan</span>
                    <span>: {{ $metaKegiatan }}</span>
                </div>
            </div>

            <!-- TABEL DAFTAR HADIR (ZIG-ZAG TTD) -->
            <div class="overflow-x-auto mb-8">
                <table class="w-full border-collapse border border-black text-[11px] font-sans">
                    <thead>
                        <tr class="bg-gray-100 text-black text-center font-bold">
                            <th class="p-2 border border-black w-10">NO</th>
                            <th class="p-2 border border-black">NAMA</th>
                            <th class="p-2 border border-black w-24">KELAS</th>
                            <th class="p-2 border border-black w-44" colspan="2">TANDA TANGAN</th>
                            <th class="p-2 border border-black w-16">KET</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hadirMembers->take(30) as $index => $m)
                        <tr class="align-middle">
                            <td class="p-1.5 border border-black text-center">{{ $index + 1 }}</td>
                            <td class="p-1.5 border border-black font-medium">{{ $m->name }}</td>
                            <td class="p-1.5 border border-black text-center">{{ $m->kelas }}</td>
                            @if(($index + 1) % 2 !== 0)
                            <td class="p-1.5 border-t border-b border-l border-black w-20 text-left text-[10px] pl-2 font-bold">{{ $index + 1 }}</td>
                            <td class="p-1.5 border-t border-b border-r border-black w-20 text-center text-[10px] text-gray-400"></td>
                            @else
                            <td class="p-1.5 border-t border-b border-l border-black w-20 text-center text-[10px] text-gray-400"></td>
                            <td class="p-1.5 border-t border-b border-r border-black w-20 text-left text-[10px] pl-2 font-bold">{{ $index + 1 }}</td>
                            @endif
                            <td class="p-1.5 border border-black text-center text-[10px]"></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center italic border border-black">
                                Belum ada data anggota untuk filter kelas ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PENGESAHAN (PERSIS GAMBAR 2) -->
            <div class="mt-8 flex justify-end text-[11px] font-sans break-inside-avoid">
                <div class="text-left w-64">
                    <p class="font-normal">Pelatih PIK-R SKAKMATT,</p>
                    <div class="h-20"></div>
                    <p class="font-bold underline">Nabila Rahma Verdiani</p>
                </div>
            </div>

        @elseif($activeTab === 'dispensasi')
            <!-- 2. SURAT DISPENSASI SISWA (DENGAN TTD PEMBINA, GURU BK, DAN PAK SUGENG) -->
            <div class="space-y-4 font-sans text-[11px]">
                
                <!-- Nomor & Perihal -->
                <div class="flex justify-between items-start">
                    <div class="space-y-0.5">
                        <div class="flex"><span class="w-20 font-bold">Nomor</span><span>: {{ $nomorDispensasi }}</span></div>
                        <div class="flex"><span class="w-20 font-bold">Lampiran</span><span>: 1 (satu) Berkas</span></div>
                        <div class="flex"><span class="w-20 font-bold">Perihal</span><span class="font-bold">: {{ $perihalDispensasi }}</span></div>
                    </div>
                    <div class="text-right">
                        <span>Purwokerto, {{ $tglDispensasi }}</span>
                    </div>
                </div>

                <!-- Tujuan Surat -->
                <div class="pt-2">
                    <p>Kepada Yth.</p>
                    <p class="font-bold">Bapak / Ibu Guru Pengajar & Wali Kelas</p>
                    <p>SMK Negeri 1 Purwokerto</p>
                    <p>di Tempat</p>
                </div>

                <!-- Isi Surat -->
                <div class="space-y-2 pt-2 leading-relaxed text-justify">
                    <p>Dengan hormat,</p>
                    <p>
                        Sehubungan dengan dilaksanakannya agenda kegiatan <strong>{{ $kegiatanDispensasi }}</strong> oleh Ekstrakurikuler PIK-R SKAKMATT SMK Negeri 1 Purwokerto, yang akan diselenggarakan pada:
                    </p>
                    <div class="pl-6 space-y-1">
                        <div class="flex"><span class="w-28 font-semibold">Hari, Tanggal</span><span>: {{ $tglDispensasi }}</span></div>
                        <div class="flex"><span class="w-28 font-semibold">Waktu</span><span>: {{ $waktuDispensasi }}</span></div>
                        <div class="flex"><span class="w-28 font-semibold">Tempat</span><span>: {{ $tempatDispensasi }}</span></div>
                    </div>
                    <p>
                        Maka bersama surat ini kami memohon dispensasi atau izin meninggalkan Kegiatan Belajar Mengajar (KBM) bagi siswa/i pengurus dan perwakilan anggota PIK-R SKAKMATT berikut:
                    </p>
                </div>

                <!-- Tabel Siswa Didispensasi -->
                <div class="overflow-x-auto py-2">
                    <table class="w-full border-collapse border border-black text-[11px]">
                        <thead>
                            <tr class="bg-gray-100 text-center font-bold">
                                <th class="p-1.5 border border-black w-10">NO</th>
                                <th class="p-1.5 border border-black">NAMA SISWA</th>
                                <th class="p-1.5 border border-black w-28">KELAS</th>
                                <th class="p-1.5 border border-black w-48">KETERANGAN / TUGAS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dispensasiStudents as $i => $s)
                            <tr>
                                <td class="p-1.5 border border-black text-center">{{ $i + 1 }}</td>
                                <td class="p-1.5 border border-black font-semibold">{{ $s['nama'] }}</td>
                                <td class="p-1.5 border border-black text-center">{{ $s['kelas'] }}</td>
                                <td class="p-1.5 border border-black">{{ $s['keterangan'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <p class="leading-relaxed text-justify">
                    Demikian surat permohonan dispensasi ini kami sampaikan. Atas perhatian, kebijaksanaan, dan kerjasama Bapak/Ibu Guru, kami mengucapkan terima kasih.
                </p>

                <!-- TANDA TANGAN RESMI 3 PIHAK: PEMBINA, GURU BK, DAN PAK SUGENG (WAKA KESISWAAN) -->
                <div class="pt-6 break-inside-avoid">
                    <div class="grid grid-cols-2 gap-8 text-center">
                        <!-- Guru BK -->
                        <div>
                            <p class="font-normal">Guru Bimbingan Konseling,</p>
                            <div class="h-20"></div>
                            <p class="font-bold underline">{{ $namaGuruBk }}</p>
                            <p class="text-[10px]">{{ $nipGuruBk }}</p>
                        </div>

                        <!-- Pembina PIK-R -->
                        <div>
                            <p class="font-normal">Pembina PIK-R SKAKMATT,</p>
                            <div class="h-20"></div>
                            <p class="font-bold underline">{{ $namaPembina }}</p>
                            <p class="text-[10px]">{{ $nipPembina }}</p>
                        </div>
                    </div>

                    <!-- Mengetahui Waka Kesiswaan (Pak Sugeng) -->
                    <div class="mt-6 text-center">
                        <p class="font-normal">Mengetahui,</p>
                        <p class="font-bold">{{ $jabatanWaka }}</p>
                        <div class="h-20"></div>
                        <p class="font-bold underline">{{ $namaWaka }}</p>
                        <p class="text-[10px]">{{ $nipWaka }}</p>
                    </div>
                </div>

            </div>

        @elseif($activeTab === 'izin-ortu')
            <!-- 3. SURAT IZIN ORANG TUA / WALI -->
            <div class="space-y-4 font-sans text-[11px]">
                
                <div class="text-center mb-6">
                    <h1 class="text-base font-bold uppercase tracking-wider underline">
                        SURAT IZIN ORANG TUA / WALI
                    </h1>
                    <p class="text-[10px] text-gray-700 mt-0.5">Nomor: {{ $nomorIzinOrtu }}</p>
                </div>

                <p>Yang bertanda tangan di bawah ini:</p>
                <div class="pl-6 space-y-1">
                    <div class="flex"><span class="w-36 font-semibold">Nama Orang Tua / Wali</span><span>: {{ $namaOrtu }}</span></div>
                    <div class="flex"><span class="w-36 font-semibold">Alamat Rumah</span><span>: {{ $alamatOrtu }}</span></div>
                    <div class="flex"><span class="w-36 font-semibold">Nomor Telp / WhatsApp</span><span>: {{ $nohpOrtu }}</span></div>
                </div>

                <p class="pt-2">Adalah benar orang tua / wali dari siswa:</p>
                <div class="pl-6 space-y-1">
                    <div class="flex"><span class="w-36 font-semibold">Nama Lengkap Siswa</span><span>: {{ $namaSiswaIzin }}</span></div>
                    <div class="flex"><span class="w-36 font-semibold">Kelas</span><span>: {{ $kelasSiswaIzin }}</span></div>
                    <div class="flex"><span class="w-36 font-semibold">NIS / NISN</span><span>: {{ $nisnSiswaIzin }}</span></div>
                </div>

                <div class="space-y-2 pt-3 leading-relaxed text-justify">
                    <p>
                        Dengan ini menyatakan <strong>MEMBERIKAN IZIN / TIDAK MEMBERIKAN IZIN*</strong> kepada putra/putri kami untuk mengikuti agenda kegiatan Ekstrakurikuler PIK-R SKAKMATT:
                    </p>
                    <div class="pl-6 space-y-1">
                        <div class="flex"><span class="w-36 font-semibold">Nama Kegiatan</span><span>: {{ $kegiatanIzin }}</span></div>
                        <div class="flex"><span class="w-36 font-semibold">Hari / Waktu</span><span>: {{ $waktuIzin }}</span></div>
                        <div class="flex"><span class="w-36 font-semibold">Tempat Pelaksanaan</span><span>: {{ $tempatIzin }}</span></div>
                    </div>
                    <p>
                        Demikian surat izin ini kami buat dengan sebenarnya tanpa paksaan dari pihak manapun untuk dipergunakan sebagaimana mestinya.
                    </p>
                </div>

                <!-- TANDA TANGAN RESMI: ORANG TUA, KETUA PIK-R, DAN PEMBINA -->
                <div class="pt-8 break-inside-avoid">
                    <div class="text-right mb-4">
                        <p>Purwokerto, .................................... 2026</p>
                    </div>

                    <div class="grid grid-cols-2 gap-8 text-center">
                        <div>
                            <p class="font-normal">Mengetahui,</p>
                            <p class="font-bold">Ketua PIK-R SKAKMATT</p>
                            <div class="h-20"></div>
                            <p class="font-bold underline">Afaf Nur Faizah</p>
                            <p class="text-[10px]">Ketua Umum</p>
                        </div>

                        <div>
                            <p class="font-normal">Hormat kami,</p>
                            <p class="font-bold">Orang Tua / Wali Siswa,</p>
                            <div class="h-20"></div>
                            <p class="font-bold underline">( .................................................... )</p>
                            <p class="text-[10px]">Nama Terang & Tanda Tangan</p>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <p class="font-normal">Mengetahui & Menyetujui,</p>
                        <p class="font-bold">Pembina PIK-R SKAKMATT</p>
                        <div class="h-20"></div>
                        <p class="font-bold underline">{{ $namaPembina }}</p>
                        <p class="text-[10px]">{{ $nipPembina }}</p>
                    </div>
                </div>

            </div>
        @endif

    </div>
    @else
    <!-- ========================================================================= -->
    <!-- 4. ARSIP & PENOMORAN SURAT RESMI                                           -->
    <!-- ========================================================================= -->
    <div class="bg-[#0B1221] border border-gray-800 rounded-3xl overflow-hidden shadow-2xl p-6 space-y-6">
        <div>
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Buku Agenda & Arsip Surat Menyurat
            </h2>
            <p class="text-gray-400 text-sm mt-1">Sistem pencatatan nomor surat resmi keluar dan masuk untuk ketertiban administrasi PIK-R SKAKMATT.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-300">
                <thead class="text-xs uppercase bg-slate-900/80 text-gray-400 border-b border-gray-800">
                    <tr>
                        <th class="px-5 py-4">Nomor & Tanggal Surat</th>
                        <th class="px-5 py-4">Klasifikasi / Jenis</th>
                        <th class="px-5 py-4">Perihal / Keterangan</th>
                        <th class="px-5 py-4">Pihak Dituju / Asal</th>
                        <th class="px-5 py-4 text-center">Status Tanda Tangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    <tr class="hover:bg-slate-800/40">
                        <td class="px-5 py-4 font-mono text-cyan-400 font-bold">
                            045/SKAKMATT/DISP/X/2026
                            <span class="block text-[11px] text-gray-400 font-sans mt-0.5">15 Okt 2026</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-xs font-bold">DISPENSASI</span>
                        </td>
                        <td class="px-5 py-4 font-semibold text-white">Permohonan Dispensasi Workshop Edukasi Sebaya</td>
                        <td class="px-5 py-4 text-gray-300">Bapak/Ibu Guru Pengajar & Wali Kelas</td>
                        <td class="px-5 py-4 text-center">
                            <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold">Lengkap: Pembina, Guru BK, Pak Sugeng</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-800/40">
                        <td class="px-5 py-4 font-mono text-amber-400 font-bold">
                            046/SKAKMATT/IZIN-ORTU/X/2026
                            <span class="block text-[11px] text-gray-400 font-sans mt-0.5">18 Okt 2026</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-300 text-xs font-bold">IZIN ORTU</span>
                        </td>
                        <td class="px-5 py-4 font-semibold text-white">Surat Izin Orang Tua Kemah Karakter Duta GenRe</td>
                        <td class="px-5 py-4 text-gray-300">Orang Tua / Wali Siswa Calon Duta</td>
                        <td class="px-5 py-4 text-center">
                            <span class="px-2.5 py-1 rounded-full bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 text-xs font-bold">Menunggu Formulir Kembali</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-800/40">
                        <td class="px-5 py-4 font-mono text-emerald-400 font-bold">
                            047/SKAKMATT/HADIR/X/2026
                            <span class="block text-[11px] text-gray-400 font-sans mt-0.5">20 Okt 2026</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-xs font-bold">PRESENSI</span>
                        </td>
                        <td class="px-5 py-4 font-semibold text-white">Daftar Hadir Anggota Forum Rutin Mingguan</td>
                        <td class="px-5 py-4 text-gray-300">Seluruh Anggota Ekstrakurikuler</td>
                        <td class="px-5 py-4 text-center">
                            <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold">Tervalidasi Pelatih (Nabila)</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
