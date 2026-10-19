@extends('layouts.admin')

@section('title', 'Detail Divisi ' . $divisiName . ' - PIK-R SKAKMATT')

@section('header', 'Detail Divisi ' . $divisiName)

@section('content')
<div class="font-sans pb-32">
    <!-- Back Button -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 pt-8 mb-4">
        <a href="{{ route('feature.divisi') }}" class="inline-flex items-center gap-2 text-teal-400 hover:text-cyan-300 transition-colors font-medium bg-slate-900/40 px-4 py-2 rounded-xl border border-slate-700/50 hover:border-teal-500/50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Manajemen Divisi
        </a>
    </div>

    <!-- Page Hero Header -->
    <section class="relative pb-16 pt-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row gap-8 items-center bg-slate-900/40 p-6 md:p-10 rounded-[3rem] border border-slate-700/50 shadow-[0_0_50px_rgba(16,185,129,0.1)] backdrop-blur-md">
                
                @php
                    $photoMap = [
                        'Kegiatan' => '/images/Divisi Kegiatan.jpeg',
                        'Humas' => '/images/Divisi Hubungan Masyarakat.jpeg',
                        'PDD' => '/images/Divisi Publikasi Dokumentasi & Desain.jpeg',
                        'PKWU' => '/images/Divisi Prakarya dan Kewirausahaan.jpeg',
                        'Konselor Sebaya' => '/images/Divisi Konselor Sebaya.jpeg'
                    ];
                    
                    $photoSrc = $photoMap[$divisiName] ?? '/images/placeholder.png';
                @endphp

                <!-- Division Image -->
                <div class="w-full md:w-1/3">
                    <div class="aspect-square rounded-3xl overflow-hidden border-2 border-emerald-500/30 shadow-[0_0_30px_rgba(16,185,129,0.2)]">
                        <img src="{{ $photoSrc }}" alt="Divisi {{ $divisiName }}" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Division Info -->
                <div class="w-full md:w-2/3 space-y-6">
                    <div class="inline-flex items-center justify-center px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-semibold uppercase tracking-widest text-sm">
                        Profil Divisi
                    </div>
                    <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight drop-shadow-md">
                        Divisi <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">{{ $divisiName }}</span>
                    </h1>
                    
                    <p class="text-slate-300 text-lg leading-relaxed max-w-2xl">
                        @if($divisiName == 'Kegiatan')
                            Mengkoordinir seluruh kegiatan operasional dan program kerja lapangan PIK-R SKAKMATT agar berjalan dengan lancar dan sesuai tujuan.
                        @elseif($divisiName == 'Humas')
                            Menjadi jembatan komunikasi antara PIK-R SKAKMATT dengan pihak luar, menyebarkan informasi, dan membangun relasi positif.
                        @elseif($divisiName == 'PDD')
                            Bertanggung jawab atas publikasi informasi, dokumentasi acara, dan desain visual yang kreatif untuk membranding PIK-R SKAKMATT.
                        @elseif($divisiName == 'PKWU')
                            Menumbuhkan jiwa kewirausahaan anggota melalui program karya kreatif dan usaha mandiri untuk mendukung finansial organisasi.
                        @else
                            Memberikan ruang aman dan nyaman bagi remaja untuk bercerita, berkonsultasi, dan berbagi solusi mengenai permasalahan remaja.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 space-y-12 relative z-10">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Anggota List -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Koor & Sekre -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach(collect($members)->whereIn('jabatan', ['Koordinator', 'Sekretaris']) as $inti)
                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-6 rounded-2xl border border-slate-700/50 hover:border-emerald-500/50 transition-colors shadow-lg relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/10 blur-[30px] rounded-full group-hover:bg-emerald-500/20 transition-all"></div>
                        <span class="text-emerald-400 text-xs font-bold uppercase tracking-wider mb-1 block">{{ $inti['jabatan'] }}</span>
                        <h3 class="text-xl font-bold text-white mb-2">{{ $inti['nama'] }}</h3>
                        <div class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-800 border border-slate-600 text-slate-300 text-sm font-medium">
                            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            {{ $inti['kelas'] }}
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Daftar Anggota Lengkap -->
                <div class="bg-slate-900/60 rounded-3xl p-6 md:p-8 border border-slate-700/50 backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Daftar Anggota Lengkap
                        </h2>
                        <span class="bg-slate-800 text-teal-400 font-bold px-4 py-1.5 rounded-full text-sm border border-slate-700">
                            {{ count($members) }} Orang
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        @foreach(collect($members)->where('jabatan', 'Anggota')->values() as $index => $anggota)
                        <div class="relative group p-1 rounded-2xl bg-gradient-to-b from-slate-700/50 to-slate-800/50 hover:from-emerald-500/50 hover:to-teal-500/50 transition-all duration-300">
                            <div class="absolute inset-0 bg-emerald-500/10 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-2xl"></div>
                            <div class="relative flex items-center gap-4 p-4 bg-slate-900 rounded-xl h-full border border-slate-700/50 group-hover:border-transparent transition-all overflow-hidden">
                                <div class="absolute -right-4 -top-4 w-16 h-16 bg-emerald-500/20 blur-xl rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                                <div class="w-12 h-12 shrink-0 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-300 font-bold text-lg transition-all duration-300 z-10 shadow-md">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0 z-10">
                                    <h4 class="text-slate-200 font-bold text-base truncate group-hover:text-emerald-300 transition-colors">{{ $anggota['nama'] }}</h4>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-md bg-slate-800 border border-slate-700 text-[11px] font-medium text-slate-400 group-hover:bg-emerald-900/40 group-hover:text-emerald-300 group-hover:border-emerald-500/30 transition-all">
                                            {{ $anggota['kelas'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Proker -->
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-b from-emerald-900/20 to-slate-900/60 rounded-3xl p-6 md:p-8 border border-emerald-500/20 backdrop-blur-sm sticky top-8">
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Program Kerja
                    </h2>
                    
                    <ul class="space-y-5">
                        @if($divisiName == 'Kegiatan')
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">Bukbermat (Buka Bersama SKAKMATT)</h4>
                                    <p class="text-sm text-slate-400 mt-1">Melaksanakan kegiatan buka bersama dan berbagi takjil untuk mempererat silaturahmi antar anggota PIK-R SKAKMATT.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">PiSat (PIK-R Sehat)</h4>
                                    <p class="text-sm text-slate-400 mt-1">Mendorong anggota PIK-R SKAKMATT menjadi remaja yang sadar akan pentingnya olahraga untuk kesehatan.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">Fun Day PIK-R</h4>
                                    <p class="text-sm text-slate-400 mt-1">Mempererat kebersamaan antara anggota melalui kegiatan seru seperti lomba-lomba edukatif.</p>
                                </div>
                            </li>
                        @elseif($divisiName == 'Humas')
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">LENTERA (Literasi Remaja Berencana)</h4>
                                    <p class="text-sm text-slate-400 mt-1">Penyebaran informasi mengenai 8 substansi GenRe melalui kegiatan literasi.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">Anjangsana</h4>
                                    <p class="text-sm text-slate-400 mt-1">Menjalin silaturahmi dan mempererat hubungan antar PIK-R dengan pihak luar sekolah (kunjungan keluar).</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">Anjangsini</h4>
                                    <p class="text-sm text-slate-400 mt-1">Menjalin silaturahmi dan mempererat hubungan antar PIK-R dengan pihak luar sekolah (menerima kunjungan).</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">Demos (Demonstrasi Ekstrakurikuler)</h4>
                                    <p class="text-sm text-slate-400 mt-1">Memperkenalkan PIK-R SKAKMATT kepada seluruh siswa terutama siswa baru.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">Open Recruitment</h4>
                                    <p class="text-sm text-slate-400 mt-1">Penerimaan anggota baru untuk meningkatkan regenerasi dan keberlanjutan program PIK-R.</p>
                                </div>
                            </li>
                        @elseif($divisiName == 'PDD')
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">Manajemen Media Sosial</h4>
                                    <p class="text-sm text-slate-400 mt-1">Mengelola akun Instagram dan platform sosial media lainnya dengan konten edukatif dan informatif terkait remaja.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">Dokumentasi Kegiatan</h4>
                                    <p class="text-sm text-slate-400 mt-1">Mengambil foto dan video setiap kegiatan atau forum PIK-R SKAKMATT sebagai arsip dan bahan publikasi.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">Desain & Branding Organisasi</h4>
                                    <p class="text-sm text-slate-400 mt-1">Membuat desain poster, banner, infografis, serta visual branding untuk keperluan publikasi dan acara.</p>
                                </div>
                            </li>
                        @elseif($divisiName == 'PKWU')
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">Pembuatan Pin, PDL, dan Selempang</h4>
                                    <p class="text-sm text-slate-400 mt-1">Menumbuhkan rasa memiliki antaranggota, melatih keterampilan desain dan produksi, dan mengembangkan jiwa wirausaha.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">Skakmatt Dodolan</h4>
                                    <p class="text-sm text-slate-400 mt-1">Kegiatan jualan makanan/minuman kekinian yang disukai siswa. Melatih jiwa wirausaha dan kreativitas kuliner.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">Skakmatt Fresh</h4>
                                    <p class="text-sm text-slate-400 mt-1">Mendorong gaya hidup sehat melalui program jualan makanan bergizi seperti salad, infused water, atau roti gandum.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">Skakmatt Creative</h4>
                                    <p class="text-sm text-slate-400 mt-1">Kegiatan membuat kerajinan menghias cermin, menghasilkan produk bernilai estetika serta melatih kerja sama.</p>
                                </div>
                            </li>
                        @else
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">SKAKMATT Call Center (SCC)</h4>
                                    <p class="text-sm text-slate-400 mt-1">Layanan konseling sebaya berbasis bot (Telegram, Instagram) menyediakan tempat aman bagi siswa bercerita dan berkeluh kesah.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">Pemilihan Duta GenRe</h4>
                                    <p class="text-sm text-slate-400 mt-1">Menemukan role model Generasi Berencana yang dapat membantu mewujudkan kehidupan berencana bagi remaja di SMKN 1 Purwokerto.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">SKAKMATT Challenge Video/Aksara Remaja</h4>
                                    <p class="text-sm text-slate-400 mt-1">Edukasi pentingnya menjadi pendengar yang baik melalui pembuatan video kolaborasi, meningkatkan kreativitas anggota PIK-R.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">SKAKMATT Discussion Time</h4>
                                    <p class="text-sm text-slate-400 mt-1">Rekreasi dan diskusi untuk mempererat rasa kekeluargaan antaranggota PIK-R, serta ruang mengekspresikan perasaan.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div class="mt-1.5 w-2.5 h-2.5 shrink-0 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                                <div>
                                    <h4 class="text-white font-semibold">Asa Remaja</h4>
                                    <p class="text-sm text-slate-400 mt-1">Memberikan ruang edukasi membahas isu-isu seputar GenRe dan berbagi pengalaman positif di masjid.</p>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        
    </section>
</div>
@endsection
