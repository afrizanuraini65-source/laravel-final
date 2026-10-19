@extends('layouts.admin')

@section('title', 'Direktori Anggota - PIK-R SKAKMATT')

@section('header', 'Direktori Anggota')

@section('content')
<div class="rounded-3xl overflow-hidden shadow-2xl bg-[#F6F8F4] -mt-4 mb-8" x-data="{ modalAddOpen: false, modalImportOpen: false, modalEditOpen: false, editMember: {} }">
    <div class="max-w-7xl mx-auto p-6 md:p-10 space-y-8">
        
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-800 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-800 hover:text-black">&times;</button>
        </div>
        @endif

        @if(session('error'))
        <div class="p-4 bg-rose-500/10 border border-rose-500/30 rounded-2xl text-rose-800 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-800 hover:text-black">&times;</button>
        </div>
        @endif

        <!-- Header & Stats -->
        <div class="flex flex-col lg:flex-row gap-6 justify-between items-start lg:items-center">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-black uppercase tracking-wider">
                        Buku Induk Anggota Resmi
                    </span>
                    @if($canManage)
                    <span class="px-2 py-0.5 rounded-full bg-teal-100 text-teal-800 text-[10px] font-semibold">
                        Akses Kelola: Sekretaris & Admin
                    </span>
                    @elseif($isKetua)
                    <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-semibold">
                        Akses Monitoring: Ketua
                    </span>
                    @endif
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#103D2A] tracking-tight">Anggota PIK-R SKAKMATT</h1>
                <p class="text-[#103D2A]/70 mt-1 text-sm">Daftar seluruh siswa terdaftar sebagai anggota resmi ekstrakurikuler.</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 w-full lg:w-auto">
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-emerald-100/50 flex flex-col items-center justify-center text-center">
                    <span class="text-xs font-semibold text-[#103D2A]/60 uppercase tracking-wider">Total</span>
                    <span class="text-2xl font-black text-[#103D2A]">{{ $totalAnggota }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-emerald-100/50 flex flex-col items-center justify-center text-center">
                    <span class="text-xs font-semibold text-emerald-600/70 uppercase tracking-wider">Kelas X</span>
                    <span class="text-2xl font-black text-emerald-600">{{ $totalX }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-emerald-100/50 flex flex-col items-center justify-center text-center">
                    <span class="text-xs font-semibold text-teal-600/70 uppercase tracking-wider">Kelas XI</span>
                    <span class="text-2xl font-black text-teal-600">{{ $totalXI }}</span>
                </div>
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-emerald-100/50 flex flex-col items-center justify-center text-center">
                    <span class="text-xs font-semibold text-sky-600/70 uppercase tracking-wider">Kelas XII</span>
                    <span class="text-2xl font-black text-sky-600">{{ $totalXII }}</span>
                </div>
            </div>
        </div>

        <!-- Action Toolbar: Excel Export/Import, PDF Generator, Manual Add -->
        <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex flex-wrap items-center gap-2">
                <!-- Export Excel -->
                <a href="{{ route('anggota.export', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-[#103D2A] text-xs font-bold border border-emerald-200 transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Ekspor Excel (.xlsx)</span>
                </a>

                <!-- Generator PDF -->
                <a href="{{ route('anggota.pdf', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-50 hover:bg-teal-100 text-teal-900 text-xs font-bold border border-teal-200 transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Cetak / Dokumen PDF</span>
                </a>
            </div>

            @if($canManage)
            <div class="flex flex-wrap items-center gap-2">
                <!-- Import Excel -->
                <button type="button" @click="modalImportOpen = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition-colors">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span>Impor Excel</span>
                </button>

                <!-- Tambah Manual -->
                <button type="button" @click="modalAddOpen = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#103D2A] hover:bg-[#0c2f20] text-white text-xs font-bold shadow-md shadow-[#103D2A]/20 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Anggota Manual</span>
                </button>
            </div>
            @endif
        </div>

        <!-- Filter & Search -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('feature.anggota') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1 relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NISN anggota..." class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-[#103D2A] focus:ring-1 focus:ring-[#103D2A] outline-none text-gray-700 bg-gray-50 transition-all">
                    <svg class="w-5 h-5 absolute left-3 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                
                <!-- Filter Tingkat -->
                <div class="w-full md:w-36">
                    <select name="level" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#103D2A] focus:ring-1 focus:ring-[#103D2A] outline-none text-gray-700 bg-gray-50 text-xs" onchange="this.form.submit()">
                        <option value="">Semua Tingkat</option>
                        <option value="X" {{ request('level') == 'X' ? 'selected' : '' }}>Kelas X</option>
                        <option value="XI" {{ request('level') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                        <option value="XII" {{ request('level') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                    </select>
                </div>

                <!-- Filter Kelas -->
                <div class="w-full md:w-48">
                    <select name="kelas" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#103D2A] focus:ring-1 focus:ring-[#103D2A] outline-none text-gray-700 bg-gray-50 text-xs" onchange="this.form.submit()">
                        <option value="">Semua Jurusan</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status -->
                <div class="w-full md:w-36">
                    <select name="status" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#103D2A] focus:ring-1 focus:ring-[#103D2A] outline-none text-gray-700 bg-gray-50 text-xs" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="AKTIF" {{ request('status') == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                        <option value="ALUMNI" {{ request('status') == 'ALUMNI' ? 'selected' : '' }}>Alumni</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-3 bg-[#103D2A] hover:bg-[#0c2f20] text-white rounded-xl font-semibold transition-colors flex items-center justify-center text-xs shadow-md shadow-[#103D2A]/20">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'level', 'kelas', 'status']))
                        <a href="{{ route('feature.anggota') }}" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-semibold transition-colors flex items-center justify-center text-xs">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Grid Cards Anggota -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($anggota as $index => $orang)
                @php
                    $level = explode(' ', $orang->kelas)[0] ?? 'X';
                    $badgeClass = match($level) {
                        'X' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'XI' => 'bg-teal-100 text-teal-700 border-teal-200',
                        'XII' => 'bg-sky-100 text-sky-700 border-sky-200',
                        default => 'bg-gray-100 text-gray-700 border-gray-200'
                    };
                    $number = ($anggota->currentPage() - 1) * $anggota->perPage() + $index + 1;
                @endphp
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md hover:border-emerald-200/60 transition-all flex flex-col justify-between group">
                    <div class="flex items-start gap-4">
                        <!-- Kotak Nomor Hijau -->
                        <div class="w-12 h-12 flex-shrink-0 bg-[#103D2A]/5 group-hover:bg-[#103D2A] rounded-xl flex items-center justify-center transition-colors duration-300">
                            <span class="text-[#103D2A] group-hover:text-white font-bold text-lg">{{ $number }}</span>
                        </div>

                        <!-- Info Anggota -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1">
                                <h3 class="text-base font-bold text-gray-800 truncate" title="{{ $orang->name }}">
                                    {{ $orang->name }}
                                </h3>
                                @if($orang->status === 'AKTIF')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">
                                    Aktif
                                </span>
                                @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200 shrink-0">
                                    {{ $orang->status }}
                                </span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold border {{ $badgeClass }}">
                                    KELAS {{ $level }}
                                </span>
                                <span class="text-xs font-medium text-gray-600 truncate">
                                    {{ $orang->kelas }}
                                </span>
                            </div>

                            <div class="mt-2 text-[11px] text-gray-400 flex items-center gap-2">
                                <span>NISN: {{ $orang->nisn ?: '-' }}</span>
                                @if($orang->position)
                                <span class="text-teal-700 font-bold">• {{ $orang->position->title }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($canManage)
                    <!-- Tombol Edit & Hapus (Sekretaris / Admin) -->
                    <div class="border-t border-gray-100 pt-3 mt-3 flex items-center justify-end gap-2">
                        <button type="button" 
                                @click="editMember = { id: {{ $orang->id }}, name: '{{ addslashes($orang->name) }}', kelas: '{{ $orang->kelas }}', nisn: '{{ $orang->nisn }}', phone: '{{ $orang->phone }}', status: '{{ $orang->status }}' }; modalEditOpen = true"
                                class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-semibold flex items-center gap-1 transition-colors">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span>Edit</span>
                        </button>
                        <form action="{{ route('anggota.destroy', $orang->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data anggota {{ addslashes($orang->name) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold transition-colors">
                                Hapus
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl p-12 flex flex-col items-center justify-center text-center border border-dashed border-gray-300">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700">Tidak ada anggota ditemukan</h3>
                    <p class="text-gray-500 mt-1 text-sm">Coba sesuaikan kata kunci atau filter pencarian Anda.</p>
                    @if(request()->hasAny(['search', 'level', 'kelas', 'status']))
                        <a href="{{ route('feature.anggota') }}" class="mt-4 px-5 py-2 bg-[#103D2A] text-white rounded-lg text-sm font-semibold hover:bg-[#0c2f20] transition-colors">Reset Filter</a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($anggota->hasPages())
            <div class="mt-8 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                {{ $anggota->links() }}
            </div>
        @endif
        
    </div>

    <!-- MODAL 1: TAMBAH ANGGOTA MANUAL -->
    @if($canManage)
    <div x-show="modalAddOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm" style="display: none;">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl text-gray-800">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="text-base font-bold text-[#103D2A] flex items-center gap-2">
                    <span>Tambah Anggota Resmi Manual</span>
                </h3>
                <button @click="modalAddOpen = false" class="text-gray-400 hover:text-black">&times;</button>
            </div>

            <form action="{{ route('anggota.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" required class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#103D2A]" placeholder="Nama sesuai data sekolah" />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Kelas *</label>
                        <select name="kelas" required class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#103D2A]">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $k)
                            <option value="{{ $k }}">{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">NISN (Opsional)</label>
                        <input type="text" name="nisn" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#103D2A]" placeholder="10 digit NISN" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Nomor HP / WhatsApp</label>
                        <input type="text" name="phone" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#103D2A]" placeholder="08..." />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Status Keanggotaan</label>
                        <select name="status" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#103D2A]">
                            <option value="AKTIF">Aktif</option>
                            <option value="ALUMNI">Alumni</option>
                            <option value="DEMISIONER">Demisioner</option>
                            <option value="TIDAK_AKTIF">Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" @click="modalAddOpen = false" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#103D2A] hover:bg-[#0c2f20] text-white font-bold">
                        Simpan Anggota
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: EDIT ANGGOTA -->
    <div x-show="modalEditOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm" style="display: none;">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl text-gray-800">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="text-base font-bold text-[#103D2A]">
                    Edit Data Anggota
                </h3>
                <button @click="modalEditOpen = false" class="text-gray-400 hover:text-black">&times;</button>
            </div>

            <form :action="'/admin/anggota/' + editMember.id" method="POST" class="space-y-4 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" x-model="editMember.name" required class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#103D2A]" />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Kelas *</label>
                        <select name="kelas" x-model="editMember.kelas" required class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#103D2A]">
                            @foreach($kelasList as $k)
                            <option value="{{ $k }}">{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">NISN (Opsional)</label>
                        <input type="text" name="nisn" x-model="editMember.nisn" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#103D2A]" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Nomor HP</label>
                        <input type="text" name="phone" x-model="editMember.phone" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#103D2A]" />
                    </div>
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Status Keanggotaan</label>
                        <select name="status" x-model="editMember.status" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#103D2A]">
                            <option value="AKTIF">Aktif</option>
                            <option value="ALUMNI">Alumni</option>
                            <option value="DEMISIONER">Demisioner</option>
                            <option value="TIDAK_AKTIF">Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" @click="modalEditOpen = false" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#103D2A] hover:bg-[#0c2f20] text-white font-bold">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3: IMPOR EXCEL ANGGOTA -->
    <div x-show="modalImportOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm" style="display: none;">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl text-gray-800">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="text-base font-bold text-[#103D2A]">
                    Impor Data Anggota via Excel (.xlsx)
                </h3>
                <button @click="modalImportOpen = false" class="text-gray-400 hover:text-black">&times;</button>
            </div>

            <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 text-xs text-[#103D2A] flex items-center justify-between">
                <span>Gunakan format template resmi agar data terdeteksi tepat.</span>
                <a href="{{ route('anggota.template') }}" class="font-bold underline text-emerald-700 hover:text-emerald-900 shrink-0">
                    Unduh Template &darr;
                </a>
            </div>

            <form action="{{ route('anggota.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">File Excel (.xlsx) *</label>
                    <input type="file" name="excel_file" accept=".xlsx" required class="w-full border border-gray-300 rounded-xl p-2 text-xs" />
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Penanganan Data Ganda</label>
                    <div class="space-y-2 mt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="duplicate_mode" value="skip" checked class="text-emerald-600 focus:ring-emerald-500" />
                            <span>Lewati jika siswa sudah terdaftar (Skip)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="duplicate_mode" value="update" class="text-emerald-600 focus:ring-emerald-500" />
                            <span>Perbarui data jika siswa sudah ada (Update)</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" @click="modalImportOpen = false" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#103D2A] hover:bg-[#0c2f20] text-white font-bold">
                        Mulai Impor Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
@endsection
