<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Induk Siswa - PIK-R SKAKMATT SMK N 1 Purwokerto</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .page-container {
                box-shadow: none !important;
                border: none !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 15mm 15mm !important;
                margin: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen py-8 px-4 font-sans">

    <!-- Action Bar (Hidden on print) -->
    <div class="no-print max-w-5xl mx-auto mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-900/90 border border-slate-700/60 p-4 rounded-2xl shadow-xl backdrop-blur-md">
        <div class="flex items-center gap-3">
            <a href="{{ route('feature.verifikasi_anggota') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold border border-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Verifikasi
            </a>
            <span class="text-xs text-slate-400">Total: <strong>{{ count($items) }}</strong> data siswa buku induk</span>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 flex items-center gap-2 transition-all hover:scale-105">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- DOCUMENT CONTAINER (A4 Paper Print Ready) -->
    <div class="page-container max-w-5xl mx-auto bg-white text-black p-8 sm:p-14 rounded-2xl shadow-2xl font-serif text-[11px] leading-relaxed">
        
        <!-- KOP SURAT RESMI -->
        <div class="border-b-[3px] border-double border-black pb-3 mb-6">
            <div class="flex items-center justify-between gap-4">
                <div class="w-20 shrink-0 flex justify-center items-center">
                    <img src="/images/lgo_smc.png" alt="Logo SMK N 1 Purwokerto" class="w-16 h-16 object-contain" onerror="this.style.display='none'">
                </div>

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

                <div class="w-20 shrink-0 flex justify-center items-center">
                    <img src="/images/lgo_pikr.png" alt="Logo PIK-R SKAKMATT" class="w-16 h-16 object-contain" onerror="this.style.display='none'">
                </div>
            </div>
        </div>

        <!-- JUDUL DOKUMEN -->
        <div class="text-center mb-6">
            <h1 class="text-base font-bold uppercase tracking-wider underline">
                BUKU INDUK MASTER DATA SISWA
            </h1>
            <h2 class="text-xs font-bold uppercase tracking-wider mt-1">
                EKSTRAKURIKULER PIK-R SKAKMATT SMK NEGERI 1 PURWOKERTO
            </h2>
            <h2 class="text-xs font-bold uppercase tracking-wider mt-0.5">
                TAHUN PELAJARAN {{ $metaTahunAjaran }}
            </h2>
        </div>

        <!-- TABEL BUKU INDUK SISWA -->
        <div class="overflow-x-auto mb-8 font-sans">
            <table class="w-full border-collapse border border-black text-[10px]">
                <thead>
                    <tr class="bg-gray-100 text-black text-center font-bold">
                        <th class="p-1.5 border border-black w-8">NO</th>
                        <th class="p-1.5 border border-black">NAMA LENGKAP</th>
                        <th class="p-1.5 border border-black w-20">KELAS</th>
                        <th class="p-1.5 border border-black w-24">NISN</th>
                        <th class="p-1.5 border border-black">TEMPAT & TGL LAHIR</th>
                        <th class="p-1.5 border border-black w-24">NO HP / WA</th>
                        <th class="p-1.5 border border-black w-20">STATUS AKUN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                    <tr>
                        <td class="p-1 border border-black text-center">{{ $index + 1 }}</td>
                        <td class="p-1 border border-black font-semibold">{{ $item->name }}</td>
                        <td class="p-1 border border-black text-center">{{ $item->kelas }}</td>
                        <td class="p-1 border border-black text-center font-mono">{{ $item->nisn ?: '-' }}</td>
                        <td class="p-1 border border-black">
                            {{ $item->tempat_lahir ?: '-' }}, {{ $item->tanggal_lahir ? $item->tanggal_lahir->format('d/m/Y') : '-' }}
                        </td>
                        <td class="p-1 border border-black text-center font-mono">{{ $item->no_hp ?: '-' }}</td>
                        <td class="p-1 border border-black text-center">
                            {{ $item->is_registered ? 'Terdaftar' : 'Belum' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center italic border border-black">
                            Tidak ada data siswa dalam Buku Induk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- AREA PENGESAHAN -->
        <div class="mt-8 pt-4 flex justify-between items-start text-[11px] font-sans break-inside-avoid">
            <div class="text-center w-56">
                <p>Mengetahui,</p>
                <p class="font-bold">Ketua PIK-R SKAKMATT</p>
                <div class="h-20"></div>
                <p class="font-bold underline">Afaf Nur Faizah</p>
                <p class="text-[10px] text-gray-600">Ketua Umum</p>
            </div>

            <div class="text-center w-56">
                <p>Purwokerto, {{ $metaTanggal }}</p>
                <p class="font-bold">Pembina PIK-R SKAKMATT,</p>
                <div class="h-20"></div>
                <p class="font-bold underline">Hayu Almara'tus S., S.Pd</p>
                <p class="text-[10px] text-gray-600">NIP. 19850412 201001 2 025</p>
            </div>
        </div>

    </div>

</body>
</html>
