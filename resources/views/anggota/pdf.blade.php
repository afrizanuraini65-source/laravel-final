<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $docTitle }} - PIK-R SKAKMATT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                font-family: 'Times New Roman', Times, serif;
                padding: 0 !important;
                margin: 0 !important;
            }
            .page-container {
                box-shadow: none !important;
                border: none !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }
            thead {
                display: table-header-group;
            }
            tr {
                page-break-inside: avoid;
            }
            th, td {
                border: 1px solid black !important;
                color: black !important;
            }
            .kop-border {
                border-bottom: 3px double black !important;
            }
        }

        .paper-table th, .paper-table td {
            border: 1px solid #1f2937;
        }
    </style>
</head>
<body class="bg-slate-900 text-gray-100 min-h-screen py-8">

    <!-- Top Toolbar (Non-Print) -->
    <div class="no-print max-w-5xl mx-auto px-4 mb-8 space-y-4">
        <div class="bg-[#0B1221] border border-white/10 rounded-2xl p-6 shadow-2xl">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-white/10 pb-4 mb-4">
                <div>
                    <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest bg-emerald-500/10 border border-emerald-500/30 px-2.5 py-1 rounded-full">
                        Generator PDF Dokumen Resmi
                    </span>
                    <h2 class="text-xl font-bold text-white mt-1">Pengaturan & Cetak Dokumen</h2>
                    <p class="text-xs text-gray-400">Sesuaikan kolom dan metadata sesuai format resmi DAFTAR HADIR PIK-R FIX.</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('anggota.index') }}" class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 text-xs font-semibold border border-white/10 transition-colors">
                        &larr; Kembali
                    </a>
                    <button onclick="window.print()" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-emerald-950/40 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        <span>Cetak / Unduh PDF</span>
                    </button>
                </div>
            </div>

            <!-- Configuration Controls -->
            <form method="GET" action="{{ route('anggota.pdf') }}" class="space-y-4 text-xs">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">Judul Dokumen</label>
                        <select name="doc_title" class="w-full bg-slate-900 border border-white/10 rounded-xl px-3 py-2 text-white">
                            <option value="DAFTAR HADIR KELAS ANGGOTA EKSTRAKURIKULER PIK-R SKAKMATT" {{ $docTitle === 'DAFTAR HADIR KELAS ANGGOTA EKSTRAKURIKULER PIK-R SKAKMATT' ? 'selected' : '' }}>DAFTAR HADIR KELAS ANGGOTA EKSTRAKURIKULER</option>
                            <option value="DAFTAR ANGGOTA RESMI EKSTRAKURIKULER PIK-R SKAKMATT" {{ $docTitle === 'DAFTAR ANGGOTA RESMI EKSTRAKURIKULER PIK-R SKAKMATT' ? 'selected' : '' }}>DAFTAR ANGGOTA RESMI PIK-R</option>
                            <option value="DAFTAR PENGURUS PIK-R SKAKMATT" {{ $docTitle === 'DAFTAR PENGURUS PIK-R SKAKMATT' ? 'selected' : '' }}>DAFTAR PENGURUS PIK-R</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">Filter Kelas</label>
                        <select name="kelas" class="w-full bg-slate-900 border border-white/10 rounded-xl px-3 py-2 text-white">
                            <option value="">-- Semua Kelas ({{ count($members) }} Siswa) --</option>
                            @foreach($kelasList as $k)
                            <option value="{{ $k }}" {{ request('kelas') === $k ? 'selected' : '' }}>{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-300 mb-1">Pengurutan</label>
                        <select name="sort" class="w-full bg-slate-900 border border-white/10 rounded-xl px-3 py-2 text-white">
                            <option value="kelas" {{ request('sort') !== 'name' ? 'selected' : '' }}>Urut Berdasarkan Kelas</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Urut Berdasarkan Nama (A-Z)</option>
                        </select>
                    </div>
                </div>

                <!-- Checkbox Pilihan Kolom -->
                <div>
                    <span class="block font-semibold text-gray-300 mb-2">Pilihan Kolom Tabel:</span>
                    <div class="flex flex-wrap gap-4 text-gray-300">
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="col_no" value="1" {{ $colNo ? 'checked' : '' }} class="rounded text-emerald-500 bg-white/10 border-white/20">
                            <span>Nomor</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="col_name" value="1" {{ $colName ? 'checked' : '' }} class="rounded text-emerald-500 bg-white/10 border-white/20">
                            <span>Nama Lengkap</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="col_kelas" value="1" {{ $colKelas ? 'checked' : '' }} class="rounded text-emerald-500 bg-white/10 border-white/20">
                            <span>Kelas</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="col_nisn" value="1" {{ $colNisn ? 'checked' : '' }} class="rounded text-emerald-500 bg-white/10 border-white/20">
                            <span>NIS / NISN</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="col_signature" value="1" {{ $colSignature ? 'checked' : '' }} class="rounded text-emerald-500 bg-white/10 border-white/20">
                            <span>Tanda Tangan (Zig-zag)</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="col_ket" value="1" {{ $colKet ? 'checked' : '' }} class="rounded text-emerald-500 bg-white/10 border-white/20">
                            <span>Keterangan</span>
                        </label>
                    </div>
                </div>

                <!-- Checkbox Pilihan Elemen -->
                <div class="border-t border-white/5 pt-3">
                    <span class="block font-semibold text-gray-300 mb-2">Pilihan Elemen Tambahan:</span>
                    <div class="flex flex-wrap gap-4 text-gray-300">
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="show_kop" value="1" {{ $showKop ? 'checked' : '' }} class="rounded text-emerald-500 bg-white/10 border-white/20">
                            <span>Kop Resmi & Logo Sekolah</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="show_metadata" value="1" {{ $showMetadata ? 'checked' : '' }} class="rounded text-emerald-500 bg-white/10 border-white/20">
                            <span>Metadata Kegiatan (Hari/Tgl/Tempat)</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="checkbox" name="show_signature_area" value="1" {{ $showSignatureArea ? 'checked' : '' }} class="rounded text-emerald-500 bg-white/10 border-white/20">
                            <span>Area Tanda Tangan Pengesahan</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold transition-colors">
                        Terapkan Pengaturan Pratinjau
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- DOCUMENT PAPER CONTAINER (PRINT READY) -->
    <div class="page-container max-w-4xl mx-auto bg-white text-black p-8 sm:p-12 rounded-xl shadow-2xl font-serif text-[12px] leading-relaxed">
        
        <!-- KOP SURAT RESMI -->
        @if($showKop)
        <div class="border-b-[3px] border-double border-black pb-3 mb-5">
            <div class="flex items-center justify-between gap-4">
                <div class="w-20 shrink-0 flex justify-center">
                    <img src="/images/lgo_smc.png" alt="Logo SMK" class="w-16 h-16 object-contain" onerror="this.style.display='none'">
                </div>
                <div class="text-center flex-1">
                    <h3 class="text-base font-bold uppercase tracking-wider text-black leading-tight">
                        EKSTRAKURIKULER PIK-R SKAKMATT
                    </h3>
                    <h2 class="text-lg font-black uppercase tracking-wider text-black leading-tight">
                        SMK NEGERI 1 PURWOKERTO
                    </h2>
                    <p class="text-[10px] text-black mt-1 leading-normal">
                        Jl. Dr. Soeparno No. 29 Telp/ Fax. (0281) 637132 Purwokerto 53111
                    </p>
                </div>
                <div class="w-20 shrink-0 flex justify-center gap-1">
                    <img src="/images/lgo_pikr.png" alt="Logo PIK-R" class="w-14 h-14 object-contain" onerror="this.style.display='none'">
                </div>
            </div>
        </div>
        @endif

        <!-- JUDUL DOKUMEN -->
        <div class="text-center mb-5">
            <h1 class="text-sm sm:text-base font-bold uppercase tracking-wide underline underline-offset-4 leading-snug">
                {{ $docTitle }}
            </h1>
            <h2 class="text-xs sm:text-sm font-bold uppercase tracking-wide mt-1">
                SMK NEGERI 1 PURWOKERTO TAHUN PELAJARAN {{ $metaTahunAjaran }}
            </h2>
        </div>

        <!-- METADATA KEGIATAN -->
        @if($showMetadata)
        <div class="grid grid-cols-2 gap-x-8 gap-y-1 mb-4 text-[11px]">
            <div class="flex items-center">
                <span class="w-28 font-semibold">Hari</span>
                <span>: {{ $metaHari }}</span>
            </div>
            <div class="flex items-center">
                <span class="w-28 font-semibold">Tempat</span>
                <span>: {{ $metaTempat }}</span>
            </div>
            <div class="flex items-center">
                <span class="w-28 font-semibold">Tanggal</span>
                <span>: {{ $metaTanggal }}</span>
            </div>
            <div class="flex items-center">
                <span class="w-28 font-semibold">Jenis Kegiatan</span>
                <span>: {{ $metaKegiatan }}</span>
            </div>
        </div>
        @endif

        <!-- TABEL ANGGOTA / HADIR -->
        <div class="overflow-x-auto">
            <table class="w-full paper-table text-left border-collapse border border-black text-[11px]">
                <thead>
                    <tr class="bg-gray-100 text-black text-center font-bold">
                        @if($colNo)<th class="p-2 border border-black w-10">NO</th>@endif
                        @if($colName)<th class="p-2 border border-black">NAMA LENGKAP</th>@endif
                        @if($colKelas)<th class="p-2 border border-black w-24">KELAS</th>@endif
                        @if($colNisn)<th class="p-2 border border-black w-24">NIS / NISN</th>@endif
                        @if($colSignature)<th class="p-2 border border-black w-40" colspan="2">TANDA TANGAN</th>@endif
                        @if($colKet)<th class="p-2 border border-black w-20">KET</th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $index => $m)
                    <tr class="align-middle">
                        @if($colNo)
                        <td class="p-1.5 border border-black text-center">{{ $index + 1 }}</td>
                        @endif
                        @if($colName)
                        <td class="p-1.5 border border-black font-semibold uppercase">{{ $m->name }}</td>
                        @endif
                        @if($colKelas)
                        <td class="p-1.5 border border-black text-center">{{ $m->kelas }}</td>
                        @endif
                        @if($colNisn)
                        <td class="p-1.5 border border-black text-center font-mono text-[10px]">{{ $m->nisn ?: '-' }}</td>
                        @endif
                        @if($colSignature)
                            @if(($index + 1) % 2 !== 0)
                            <td class="p-1.5 border-t border-b border-l border-black w-20 text-left text-[10px] pl-2 font-bold">{{ $index + 1 }}. ...........</td>
                            <td class="p-1.5 border-t border-b border-r border-black w-20 text-center text-[10px] text-gray-400"></td>
                            @else
                            <td class="p-1.5 border-t border-b border-l border-black w-20 text-center text-[10px] text-gray-400"></td>
                            <td class="p-1.5 border-t border-b border-r border-black w-20 text-left text-[10px] pl-2 font-bold">{{ $index + 1 }}. ...........</td>
                            @endif
                        @endif
                        @if($colKet)
                        <td class="p-1.5 border border-black text-center text-[10px] text-gray-500"></td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center italic border border-black">
                            Tidak ada data anggota yang sesuai dengan filter yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- AREA PENGESAHAN -->
        @if($showSignatureArea)
        <div class="mt-8 pt-4 flex justify-between items-start text-[11px] break-inside-avoid">
            <div class="text-center w-56">
                <p>Mengetahui,</p>
                <p class="font-bold">Ketua PIK-R SKAKMATT</p>
                <div class="h-20"></div>
                <p class="font-bold underline">Afaf Nur Faizah</p>
                <p class="text-[10px] text-gray-600">Ketua Umum</p>
            </div>

            <div class="text-center w-56">
                <p>Purwokerto, {{ $metaTanggal }}</p>
                <p class="font-bold">Pelatih PIK-R SKAKMATT,</p>
                <div class="h-20"></div>
                <p class="font-bold underline">Nabila Rahma Verdiani</p>
                <p class="text-[10px] text-gray-600">Pelatih PIK-R SKAKMATT</p>
            </div>
        </div>
        @endif

    </div>

</body>
</html>
