@extends('layouts.public')

@section('title', 'PIK-R SKAKMATT - Ruang Tumbuh Generasi Berencana')

@section('content')
<!-- Hero Section (Natural Image Height to ensure 100% visibility) -->
<section class="relative w-full bg-pikr-background overflow-hidden flex flex-col justify-start -mt-20">
    <!-- Hero Image Container (Natural flow, dictates section height) -->
    <div class="relative w-full mt-20">
        <img src="/images/all.jpeg" alt="Anggota PIK-R SKAKMATT" class="w-full h-auto object-contain block" onerror="this.src='/images/placeholder.png'" />
        
        <!-- Gradient Overlay: Darkens the top so the centered text is readable against the white wall -->
        <div class="absolute top-0 left-0 right-0 h-[60%] bg-gradient-to-b from-pikr-background via-pikr-background/80 to-transparent"></div>
        
        <!-- Gradient Overlay: Blends the bottom edge into the dark theme seamlessly -->
        <div class="absolute bottom-0 left-0 right-0 h-40 bg-gradient-to-t from-pikr-background via-pikr-background/70 to-transparent"></div>
        
        <!-- Centered Text Content (Positioned absolutely over the top white space) -->
        <div class="absolute inset-x-0 top-0 pt-16 md:pt-24 lg:pt-32 z-10 flex flex-col items-center">
            <div class="w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white/5 border border-white/10 text-gray-300 mb-6 backdrop-blur-md shadow-lg">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)] animate-pulse"></span>
                    <span class="text-xs font-bold tracking-widest uppercase">Ceria Ceria Ceria</span>
                </div>
                
                <!-- Heading -->
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-8xl font-black text-white tracking-tighter mb-4 lg:mb-6 leading-[1.1] drop-shadow-2xl">
                    PIK-R <br class="sm:hidden"/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-pikr-accent to-emerald-300 drop-shadow-[0_0_20px_rgba(52,211,153,0.2)]">SKAKMATT</span>
                </h1>
                
                <!-- Paragraph -->
                <p class="text-sm sm:text-base md:text-xl text-gray-300 mb-8 lg:mb-10 font-medium leading-relaxed max-w-3xl mx-auto drop-shadow-md">
                    Ruang aman untuk bertumbuh, saling berbagi, dan berkembang bersama. Temukan potensimu, ukir kenangan indah, dan jadilah Generasi Berencana yang inspiratif.
                </p>
                
                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-6">
                    <a href="/informasi" class="bg-emerald-500 text-white px-8 py-3.5 lg:py-4 rounded-full font-bold text-sm lg:text-base hover:bg-emerald-400 transition-all duration-300 shadow-[0_8px_20px_rgba(16,185,129,0.3)] transform hover:-translate-y-1">
                        Kenal Lebih Dekat
                    </a>
                    <a href="/kegiatan" class="bg-[#2a2f38]/80 backdrop-blur-md text-white border border-white/20 px-8 py-3.5 lg:py-4 rounded-full font-bold text-sm lg:text-base hover:bg-[#373e4a] transition-all duration-300 shadow-lg transform hover:-translate-y-1">
                        Lihat Kegiatan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Pills Section (Placed BELOW the hero image) -->
<section class="relative z-20 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 -mt-4">
    <div class="hidden md:grid grid-cols-4 gap-4 lg:gap-6">
        <div class="bg-[#1f242e]/90 backdrop-blur-lg border border-white/5 rounded-2xl p-6 text-center shadow-xl hover:-translate-y-2 transition-transform duration-300">
            <div class="text-3xl font-black text-emerald-400 mb-1">{{ \App\Models\Division::count() }}</div>
            <div class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Divisi Aktif</div>
        </div>
        <div class="bg-[#1f242e]/90 backdrop-blur-lg border border-white/5 rounded-2xl p-6 text-center shadow-xl hover:-translate-y-2 transition-transform duration-300">
            <div class="text-3xl font-black text-white mb-1">{{ \App\Models\Member::count() }}</div>
            <div class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Total Anggota</div>
        </div>
        <div class="bg-[#1f242e]/90 backdrop-blur-lg border border-white/5 rounded-2xl p-6 text-center shadow-xl hover:-translate-y-2 transition-transform duration-300">
            <div class="text-3xl font-black text-emerald-400 mb-1">30</div>
            <div class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Program Kerja</div>
        </div>
        <div class="bg-[#1f242e]/90 backdrop-blur-lg border border-white/5 rounded-2xl p-6 text-center shadow-xl hover:-translate-y-2 transition-transform duration-300">
            <div class="text-3xl font-black text-white mb-1">{{ \App\Models\User::role('KONSELOR_SEBAYA')->count() }}</div>
            <div class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Konselor</div>
        </div>
    </div>
</section>

<!-- Stats for Mobile -->
<section class="md:hidden w-full max-w-7xl mx-auto px-4 py-8 relative z-10">
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-pikr-darkgreen/80 backdrop-blur-xl border border-white/10 rounded-2xl p-5 text-center shadow-lg">
            <div class="text-3xl font-black text-pikr-accent mb-1">{{ \App\Models\Division::count() }}</div>
            <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">Divisi Aktif</div>
        </div>
        <div class="bg-pikr-darkgreen/80 backdrop-blur-xl border border-white/10 rounded-2xl p-5 text-center shadow-lg">
            <div class="text-3xl font-black text-white mb-1">{{ \App\Models\Member::count() }}</div>
            <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">Total Anggota</div>
        </div>
        <div class="bg-pikr-darkgreen/80 backdrop-blur-xl border border-white/10 rounded-2xl p-5 text-center shadow-lg">
            <div class="text-3xl font-black text-pikr-accent mb-1">30</div>
            <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">Program Kerja</div>
        </div>
        <div class="bg-pikr-darkgreen/80 backdrop-blur-xl border border-white/10 rounded-2xl p-5 text-center shadow-lg">
            <div class="text-3xl font-black text-white mb-1">{{ \App\Models\User::role('KONSELOR_SEBAYA')->count() }}</div>
            <div class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">Konselor</div>
        </div>
    </div>
</section>

<!-- Bento Grid Features Section (Original Section Preserved in Original Position) -->
<section class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 pb-32">
    <div class="mb-16 text-center">
        <h2 class="text-3xl md:text-5xl font-bold text-white mb-4 tracking-tight">Apa yang Kami Lakukan?</h2>
        <p class="text-gray-400 text-lg max-w-2xl mx-auto leading-relaxed">Kami berfokus pada pengembangan diri remaja, edukasi kesehatan reproduksi, dan persiapan kehidupan berkeluarga.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Large feature block -->
        <div class="md:col-span-2 bg-gradient-to-br from-white/10 to-transparent backdrop-blur-lg border border-emerald-500/20 rounded-3xl p-8 hover:border-pikr-accent/50 transition-all duration-300 group shadow-xl">
            <div class="w-16 h-16 bg-pikr-accent/20 text-pikr-accent rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <h3 class="text-3xl font-bold text-white mb-4 tracking-tight">Konseling Sebaya</h3>
            <p class="text-gray-300 text-lg leading-relaxed mb-8 max-w-xl">Layanan konseling dari remaja, untuk remaja. Kami menyediakan ruang aman untuk berdiskusi tentang masalah pribadi, sekolah, maupun keluarga tanpa penghakiman.</p>
            <a href="/ruang-sebaya" class="text-pikr-accent font-semibold flex items-center gap-2 group-hover:gap-4 transition-all duration-300">
                Pelajari lebih lanjut <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>

        <!-- Small feature block 1 -->
        <div class="bg-gradient-to-br from-white/5 to-transparent backdrop-blur-lg border border-white/10 rounded-3xl p-8 hover:border-pikr-accent/40 transition-all duration-300 group shadow-xl">
            <div class="w-14 h-14 bg-white/10 text-white rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-white mb-3 tracking-tight">Edukasi GenRe</h3>
            <p class="text-gray-400 leading-relaxed mb-6">Portal materi edukasi remaja, kesehatan reproduksi, perencanaan masa depan, serta life skills PIK-R SKAKMATT.</p>
            <a href="/informasi" class="text-pikr-accent/80 hover:text-pikr-accent font-medium transition-colors flex items-center gap-1 group-hover:gap-2 duration-300">
                Baca Artikel <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <!-- Small feature block 2 -->
        <div class="bg-gradient-to-br from-white/5 to-transparent backdrop-blur-lg border border-white/10 rounded-3xl p-8 hover:border-pikr-accent/40 transition-all duration-300 group shadow-xl">
            <div class="w-14 h-14 bg-white/10 text-white rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-white mb-3 tracking-tight">Event Tahunan</h3>
            <p class="text-gray-400 leading-relaxed mb-6">SKAKMATT Discussion Time, Buka Bersama, dan Reorganisasi Pengurus.</p>
            <a href="/kegiatan" class="text-pikr-accent/80 hover:text-pikr-accent font-medium transition-colors flex items-center gap-1 group-hover:gap-2 duration-300">
                Lihat Jadwal <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <!-- Large Horizontal Block -->
        <div class="md:col-span-2 bg-pikr-emerald/30 backdrop-blur-lg border border-pikr-accent/30 rounded-3xl p-8 flex flex-col justify-center relative overflow-hidden group shadow-2xl">
            <div class="absolute right-0 bottom-0 opacity-10 group-hover:opacity-20 transition-opacity duration-500 transform translate-x-1/4 translate-y-1/4">
                <svg width="300" height="300" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            </div>
            <h3 class="text-3xl font-bold text-white mb-4 relative z-10 tracking-tight">Bergabung Bersama SKAKMATT</h3>
            <p class="text-pikr-light text-lg mb-8 relative z-10 max-w-xl leading-relaxed">Daftarkan dirimu dan jadilah bagian dari agen perubahan. Kelola kegiatan, ikuti event, dan temukan relasi positif.</p>
            <div class="relative z-10 flex flex-wrap gap-4">
                <a href="/pendaftaran-anggota" class="bg-white text-pikr-dark px-8 py-3.5 rounded-full font-bold hover:bg-pikr-verylight transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 active:scale-95 inline-block text-sm">
                    Daftar Anggota
                </a>
                <a href="/register" class="bg-white/10 text-white border border-white/20 px-6 py-3.5 rounded-full font-bold hover:bg-white/20 transition-all duration-300 inline-block text-sm">
                    Daftar Akun Siswa
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================================= --}}
{{-- 4.A PENGENALAN PIK-R SKAKMATT: RUANG TUMBUH GENERASI BERENCANA           --}}
{{-- ========================================================================= --}}
<section id="tentang" class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 scroll-mt-24">
    <div class="bg-gradient-to-b from-[#0B1221] to-[#064E3B]/20 border border-emerald-500/20 rounded-3xl p-8 sm:p-12 shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-500/10 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-3xl mb-12">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-4">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Tentang Organisasi
            </div>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
                Ruang Tumbuh <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400">Generasi Berencana</span>
            </h2>
            <p class="text-gray-300 text-sm sm:text-base md:text-lg mt-4 leading-relaxed font-medium">
                "PIK-R SKAKMATT merupakan wadah bagi remaja SMK Negeri 1 Purwokerto untuk memperoleh informasi, mengembangkan potensi diri, berbagi pengalaman, dan belajar mempersiapkan masa depan yang berkualitas."
            </p>
        </div>

        <!-- 5 Pilar Inti Organisasi -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Pilar 1: Pengertian PIK-R -->
            <div class="p-6 rounded-2xl bg-white/[0.03] border border-white/5 hover:border-emerald-500/30 transition-all hover:-translate-y-1">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-black mb-4">
                    01
                </div>
                <h3 class="text-base font-bold text-white mb-2">Pengertian PIK-R</h3>
                <p class="text-xs text-gray-300 leading-relaxed">
                    Pusat Informasi dan Konseling Remaja (PIK-R) adalah program resmi pembinaan ketahanan remaja yang dikelola dari, oleh, dan untuk remaja guna memberikan pelayanan informasi serta konseling terpercaya.
                </p>
            </div>

            <!-- Pilar 2: Tujuan PIK-R bagi Remaja -->
            <div class="p-6 rounded-2xl bg-white/[0.03] border border-white/5 hover:border-emerald-500/30 transition-all hover:-translate-y-1">
                <div class="w-10 h-10 rounded-xl bg-teal-500/20 text-teal-300 flex items-center justify-center font-black mb-4">
                    02
                </div>
                <h3 class="text-base font-bold text-white mb-2">Tujuan bagi Remaja</h3>
                <p class="text-xs text-gray-300 leading-relaxed">
                    Membimbing siswa agar terhindar dari risiko Triad KRR (seksualitas pra-nikah, pernikahan dini, dan NAPZA/HIV) serta membekali perencanaan masa depan secara matang dan bermakna.
                </p>
            </div>

            <!-- Pilar 3: Peran PIK-R SKAKMATT di Sekolah -->
            <div class="p-6 rounded-2xl bg-white/[0.03] border border-white/5 hover:border-emerald-500/30 transition-all hover:-translate-y-1">
                <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-300 flex items-center justify-center font-black mb-4">
                    03
                </div>
                <h3 class="text-base font-bold text-white mb-2">Peran di SMKN 1 Purwokerto</h3>
                <p class="text-xs text-gray-300 leading-relaxed">
                    Menjadi garda terdepan pembinaan karakter sebaya, penggerak iklim sekolah yang positif, inklusif, dan ramah remaja melalui kolaborasi 5 divisi kerja aktif.
                </p>
            </div>

            <!-- Pilar 4: Edukasi & Konseling Sebaya -->
            <div class="p-6 rounded-2xl bg-white/[0.03] border border-white/5 hover:border-emerald-500/30 transition-all hover:-translate-y-1">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-black mb-4">
                    04
                </div>
                <h3 class="text-base font-bold text-white mb-2">Edukasi & Konseling Sebaya</h3>
                <p class="text-xs text-gray-300 leading-relaxed">
                    Menyediakan ruang aman bagi siswa untuk saling mendengarkan, berbagi beban perasaan, dan mendiskusikan tantangan kehidupan remaja tanpa stigma maupun penghakiman.
                </p>
            </div>

            <!-- Pilar 5: Potensi, Komunikasi, & Kepemimpinan -->
            <div class="p-6 rounded-2xl bg-white/[0.03] border border-white/5 hover:border-emerald-500/30 transition-all hover:-translate-y-1 lg:col-span-2">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-300 flex items-center justify-center font-black mb-4">
                    05
                </div>
                <h3 class="text-base font-bold text-white mb-2">Pengembangan Potensi & Kepemimpinan</h3>
                <p class="text-xs text-gray-300 leading-relaxed">
                    Mengasah soft skills penting: komunikasi asertif, public speaking, kepemimpinan adaptif, dan kewirausahaan kreatif agar pelajar SMK siap menjadi teladan dan agen perubahan di era digital.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================================= --}}
{{-- 4.C PENGENALAN DUTA GENRE SMK NEGERI 1 PURWOKERTO                        --}}
{{-- ========================================================================= --}}
<section id="duta-genre" class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 scroll-mt-24">
    <div class="bg-gradient-to-br from-[#0B1221] via-[#022C22] to-[#0A0F1D] border border-amber-500/30 rounded-3xl p-8 sm:p-12 shadow-2xl relative overflow-hidden">
        <!-- Golden Ambient Glow -->
        <div class="absolute top-0 right-0 w-80 h-80 bg-amber-500/10 blur-[100px] rounded-full pointer-events-none"></div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <!-- Image Frame: all_genre -->
            <div class="lg:col-span-5">
                <div class="relative rounded-2xl overflow-hidden border-2 border-amber-500/40 shadow-[0_0_30px_rgba(245,158,11,0.2)] group">
                    <img src="/images/all_genre.jpeg" alt="Ketiga Duta GenRe SMK Negeri 1 Purwokerto" class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.src='/images/all.jpeg'">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4 text-center">
                        <span class="inline-block px-3 py-1 rounded-full bg-amber-500/90 text-black text-xs font-black uppercase tracking-wider shadow-lg">
                            Duta GenRe SMK Negeri 1 Purwokerto
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="lg:col-span-7 space-y-5">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-500/15 border border-amber-500/30 text-amber-300 text-xs font-bold uppercase tracking-widest">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    Role Model & Agen Perubahan
                </span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
                    Kenali <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-emerald-300 to-teal-200">Duta GenRe</span> SMK Negeri 1 Purwokerto
                </h2>
                <div class="space-y-3 text-xs sm:text-sm text-gray-300 leading-relaxed">
                    <p>
                        <strong>Duta Generasi Berencana (Duta GenRe)</strong> merupakan figur teladan remaja yang dipilih melalui seleksi komprehensif untuk menjadi representasi generasi muda yang memiliki perencanaan hidup matang, sehat, cerdas, dan ceria.
                    </p>
                    <p>
                        Dalam ekosistem <strong>PIK-R SKAKMATT</strong>, Duta GenRe berperan sebagai jembatan komunikasi dan edukator sebaya. Mereka aktif mensosialisasikan pentingnya pendewasaan usia pernikahan, perencanaan pendidikan dan karier, serta pencegahan perilaku berisiko di lingkungan sekolah.
                    </p>
                </div>

                <div class="pt-4 flex flex-wrap gap-4">
                    <a href="#profil-duta" class="px-7 py-3.5 rounded-full bg-gradient-to-r from-amber-500 to-emerald-500 hover:from-amber-400 hover:to-emerald-400 text-black font-extrabold text-xs uppercase tracking-wider shadow-lg shadow-amber-950/40 transition-all transform hover:-translate-y-0.5">
                        Kenali Para Duta &rarr;
                    </a>
                    <a href="{{ url('/duta-genre') }}" class="px-6 py-3.5 rounded-full bg-white/10 hover:bg-white/20 text-white font-bold text-xs uppercase tracking-wider border border-white/20 transition-all">
                        Halaman Publik Duta
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================================= --}}
{{-- 4.D PROFIL PERSONAL DUTA GENRE (3 KARTU KONSISTEN)                       --}}
{{-- ========================================================================= --}}
<section id="profil-duta" class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 scroll-mt-24">
    <div class="mb-10 text-center space-y-2">
        <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Figur Teladan Sebaya</span>
        <h3 class="text-2xl sm:text-3xl font-black text-white">Profil Para Duta GenRe</h3>
        <p class="text-gray-400 text-xs sm:text-sm max-w-xl mx-auto">
            Mengenal lebih dekat dedikasi dan kontribusi nyata ketiga Duta GenRe SMK Negeri 1 Purwokerto.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Duta 1: Benaya Iskha Ghifari (benaya_genre) -->
        <div class="bg-gradient-to-b from-[#111A2E] to-[#0A0F1D] border border-white/10 hover:border-emerald-500/40 rounded-3xl p-6 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_15px_40px_rgba(16,185,129,0.15)] flex flex-col justify-between">
            <div>
                <div class="w-full aspect-[4/5] rounded-2xl overflow-hidden mb-5 bg-slate-900 border border-white/10">
                    <img src="/images/benaya_genre.jpeg" alt="Benaya Iskha Ghifari" class="w-full h-full object-cover" onerror="this.src='/images/all_genre.jpeg'">
                </div>
                <span class="text-[11px] font-extrabold text-emerald-400 uppercase tracking-wider">Duta GenRe Putri</span>
                <h4 class="text-xl font-bold text-white mt-1">Benaya Iskha Ghifari</h4>
                <p class="text-xs text-gray-400 mt-2 line-clamp-3 leading-relaxed">
                    Koordinator Konselor Sebaya yang aktif mengadvokasi kesehatan mental, komunikasi asertif, dan perencanaan masa depan remaja putri di SMK Negeri 1 Purwokerto.
                </p>
            </div>
            <div class="pt-4 mt-4 border-t border-white/10 flex items-center justify-between">
                <span class="text-[11px] text-gray-400">SMKN 1 Purwokerto</span>
                <a href="{{ url('/duta-genre/benaya-iskha-ghifari') }}" class="text-emerald-400 hover:text-emerald-300 text-xs font-bold flex items-center gap-1.5 transition-colors">
                    <span>Lihat Profil</span> &rarr;
                </a>
            </div>
        </div>

        <!-- Duta 2: Muhammad Paaza Abidin (paaza_genre) -->
        <div class="bg-gradient-to-b from-[#111A2E] to-[#0A0F1D] border border-white/10 hover:border-emerald-500/40 rounded-3xl p-6 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_15px_40px_rgba(16,185,129,0.15)] flex flex-col justify-between">
            <div>
                <div class="w-full aspect-[4/5] rounded-2xl overflow-hidden mb-5 bg-slate-900 border border-white/10">
                    <img src="/images/paaza_genre.jpeg" alt="Muhammad Paaza Abidin" class="w-full h-full object-cover" onerror="this.src='/images/all_genre.jpeg'">
                </div>
                <span class="text-[11px] font-extrabold text-cyan-400 uppercase tracking-wider">Duta GenRe Putra</span>
                <h4 class="text-xl font-bold text-white mt-1">Muhammad Paaza Abidin</h4>
                <p class="text-xs text-gray-400 mt-2 line-clamp-3 leading-relaxed">
                    Berdedikasi dalam penguatan substansi Triad KRR, kepemimpinan sebaya, literasi perencanaan masa depan, serta teladan remaja berencana.
                </p>
            </div>
            <div class="pt-4 mt-4 border-t border-white/10 flex items-center justify-between">
                <span class="text-[11px] text-gray-400">SMKN 1 Purwokerto</span>
                <a href="{{ url('/duta-genre/muhammad-paaza-abidin') }}" class="text-cyan-400 hover:text-cyan-300 text-xs font-bold flex items-center gap-1.5 transition-colors">
                    <span>Lihat Profil</span> &rarr;
                </a>
            </div>
        </div>

        <!-- Duta 3: Nazarli Nadiah Putri Yuarliana (nazarli_genre) -->
        <div class="bg-gradient-to-b from-[#111A2E] to-[#0A0F1D] border border-white/10 hover:border-emerald-500/40 rounded-3xl p-6 transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_15px_40px_rgba(16,185,129,0.15)] flex flex-col justify-between">
            <div>
                <div class="w-full aspect-[4/5] rounded-2xl overflow-hidden mb-5 bg-slate-900 border border-white/10">
                    <img src="/images/nazarli_genre.jpeg" alt="Nazarli Nadiah Putri Yuarliana" class="w-full h-full object-cover" onerror="this.src='/images/all_genre.jpeg'">
                </div>
                <span class="text-[11px] font-extrabold text-teal-400 uppercase tracking-wider">Duta GenRe Influencer</span>
                <h4 class="text-xl font-bold text-white mt-1">Nazarli Nadiah Putri Yuarliana</h4>
                <p class="text-xs text-gray-400 mt-2 line-clamp-3 leading-relaxed">
                    Menggerakkan edukasi Generasi Berencana lewat media sosial kreatif, advokasi digital positif, kesehatan reproduksi, dan gaya hidup sehat remaja.
                </p>
            </div>
            <div class="pt-4 mt-4 border-t border-white/10 flex items-center justify-between">
                <span class="text-[11px] text-gray-400">SMKN 1 Purwokerto</span>
                <a href="{{ url('/duta-genre/nazarli-nadiah-putri-yuarliana') }}" class="text-teal-400 hover:text-teal-300 text-xs font-bold flex items-center gap-1.5 transition-colors">
                    <span>Lihat Profil</span> &rarr;
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================================= --}}
{{-- 5. ARTIKEL PEMILIHAN DUTA GENRE DENGAN FOTO ASLI pendutgen.png           --}}
{{-- ========================================================================= --}}
<section class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-[#0B1221]/90 border border-emerald-500/30 rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <!-- Image pendutgen -->
            <div class="lg:col-span-5">
                <div class="rounded-2xl overflow-hidden border border-white/10 shadow-xl group">
                    <img src="/images/pendutgen.png" alt="Pemilihan Duta GenRe SMK Negeri 1 Purwokerto" class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="text-[11px] text-gray-400 italic text-center mt-2">
                    Dokumentasi Pemilihan Duta GenRe SMK Negeri 1 Purwokerto
                </div>
            </div>

            <!-- News & Details -->
            <div class="lg:col-span-7 space-y-4">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-black uppercase tracking-wider border border-emerald-500/30">
                        Kegiatan Terlaksana • 12 Juni 2025
                    </span>
                    <span class="text-gray-400 text-xs">SMK Negeri 1 Purwokerto</span>
                </div>

                <h3 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-snug">
                    Berani Melangkah, Siap Menjadi Inspirasi: Pemilihan Duta GenRe SMK Negeri 1 Purwokerto
                </h3>

                <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                    Ajang pemilihan Duta GenRe SMK Negeri 1 Purwokerto sukses diselenggarakan sebagai wadah pembentukan karakter generasi berencana. Kegiatan yang memuncak pada <strong>Grand Final 12 Juni 2025</strong> ini menguji kecakapan advokasi, kepribadian, serta wawasan Triad KRR seluruh peserta di bawah naungan pembinaan PIK-R SKAKMATT.
                </p>

                <!-- Referensi Faktual Media Resmi -->
                <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/10 text-xs space-y-2">
                    <span class="font-bold text-amber-300 uppercase tracking-wider text-[10px] block">Sumber Liputan Resmi Media Massa:</span>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="https://rri.co.id/purwokerto/regional/1578495/smkn-1-purwokerto-gelar-pemilihan-duta-genre" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-cyan-400 hover:text-cyan-300 underline font-semibold">
                            <span>RRI Purwokerto</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                        <span class="hidden sm:inline text-gray-500">•</span>
                        <a href="https://purwokerto.inews.id/read/607442/bentuk-generasi-berencana-smk-n-1-purwokerto-gelar-pemilihan-duta-genre" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-emerald-400 hover:text-emerald-300 underline font-semibold">
                            <span>iNews Purwokerto</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </div>
                </div>

                <div class="pt-2 flex flex-wrap gap-3">
                    <a href="{{ url('/informasi/berani-melangkah-siap-menjadi-inspirasi-pemilihan-duta-genre') }}" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider transition-all">
                        Baca Artikel Lengkap
                    </a>
                    <a href="#dokumentasi" class="px-6 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs uppercase tracking-wider border border-white/10 transition-all">
                        Lihat Dokumentasi &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================================= --}}
{{-- 4.B ARTIKEL DAN EDUKASI REMAJA                                            --}}
{{-- ========================================================================= --}}
<section id="artikel" class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 scroll-mt-24">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
        <div>
            <span class="text-xs font-black uppercase tracking-wider text-emerald-400">Pusat Literasi & Edukasi</span>
            <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight mt-1">
                Pengetahuan Hari Ini, <br class="hidden sm:block"/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">Bekal untuk Masa Depan</span>
            </h2>
            <p class="text-gray-400 text-xs sm:text-sm mt-2 max-w-xl">
                Artikel edukasi remaja tentang kesehatan reproduksi, Triad KRR, konseling sebaya, dan persiapan masa depan.
            </p>
        </div>
        <a href="{{ url('/informasi') }}" class="px-6 py-2.5 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs font-bold uppercase tracking-wider border border-white/20 transition-all shrink-0">
            Lihat Semua Artikel &rarr;
        </a>
    </div>

    @php
        $featuredArticles = array_slice(\App\Data\ArticleData::getAll(), 0, 6);
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($featuredArticles as $art)
        <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 hover:border-emerald-500/40 rounded-3xl p-5 flex flex-col justify-between transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_15px_40px_rgba(16,185,129,0.15)] group">
            <div>
                <!-- Thumbnail -->
                <div class="relative w-full aspect-video rounded-2xl overflow-hidden mb-4 bg-slate-900 border border-white/5">
                    <img src="/images/{{ $art['image'] }}" alt="{{ $art['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.src='/images/all.jpeg'">
                    <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded-full bg-emerald-950/80 text-emerald-300 border border-emerald-500/30 text-[10px] font-bold">
                        {{ $art['category'] }}
                    </span>
                </div>

                <h3 class="text-base font-bold text-white group-hover:text-emerald-300 transition-colors line-clamp-2 leading-snug">
                    {{ $art['title'] }}
                </h3>
                <p class="text-xs text-gray-400 mt-2 line-clamp-2 leading-relaxed">
                    {{ $art['summary'] }}
                </p>
            </div>

            <div class="pt-4 mt-4 border-t border-white/10 flex items-center justify-between text-xs">
                <span class="text-[11px] text-gray-500">{{ $art['author'] ?? 'PIK-R SKAKMATT' }}</span>
                <a href="{{ url('/informasi/' . $art['slug']) }}" class="text-emerald-400 hover:text-emerald-300 font-bold text-xs flex items-center gap-1 transition-colors">
                    <span>Baca</span> &rarr;
                </a>
            </div>
        </div>
        @endforeach
    </div>
</section>



{{-- ========================================================================= --}}
{{-- 6. KEGIATAN DAN INFORMASI SKAKMATT: BERGERAK BERSAMA, BERTUMBUH BERSAMA   --}}
{{-- ========================================================================= --}}
<section id="kegiatan" class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 scroll-mt-24" x-data="{ tabKegiatan: 'terlaksana' }">
    <div class="text-center max-w-3xl mx-auto mb-10 space-y-3">
        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs font-bold uppercase tracking-wider">
            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
            Aksi Nyata & Kolaborasi
        </span>
        <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight">
            Bergerak Bersama, <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">Bertumbuh Bersama</span>
        </h2>
        <p class="text-gray-400 text-xs sm:text-sm">
            Rangkaian kegiatan edukatif, konseling, dan pembinaan kepemimpinan remaja di SMK Negeri 1 Purwokerto.
        </p>

        <!-- Tab Selector: Telah Terlaksana, Berita Organisasi, Agenda Mendatang -->
        <div class="pt-4 flex justify-center gap-2 flex-wrap">
            <button @click="tabKegiatan = 'terlaksana'" 
                    :class="tabKegiatan === 'terlaksana' ? 'bg-emerald-500 text-white font-bold shadow-lg shadow-emerald-950/50' : 'bg-white/5 text-gray-400 hover:text-white'"
                    class="px-5 py-2 rounded-xl text-xs uppercase tracking-wider transition-all">
                Telah Terlaksana
            </button>
            <button @click="tabKegiatan = 'berita'" 
                    :class="tabKegiatan === 'berita' ? 'bg-emerald-500 text-white font-bold shadow-lg shadow-emerald-950/50' : 'bg-white/5 text-gray-400 hover:text-white'"
                    class="px-5 py-2 rounded-xl text-xs uppercase tracking-wider transition-all">
                Berita Organisasi
            </button>
            <button @click="tabKegiatan = 'agenda'" 
                    :class="tabKegiatan === 'agenda' ? 'bg-emerald-500 text-white font-bold shadow-lg shadow-emerald-950/50' : 'bg-white/5 text-gray-400 hover:text-white'"
                    class="px-5 py-2 rounded-xl text-xs uppercase tracking-wider transition-all">
                Agenda Mendatang
            </button>
        </div>
    </div>

    <!-- Tab 1: Telah Terlaksana -->
    <div x-show="tabKegiatan === 'terlaksana'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#0B1221]/90 border border-white/10 rounded-3xl p-6 space-y-4">
            <div class="aspect-video rounded-2xl overflow-hidden bg-slate-900">
                <img src="/images/pendutgen.png" alt="Pemilihan Duta GenRe" class="w-full h-full object-cover">
            </div>
            <span class="text-[10px] font-black uppercase tracking-wider text-emerald-400">12 Juni 2025 • Event Akbar</span>
            <h4 class="text-base font-bold text-white">Pemilihan Duta GenRe SMK N 1 Purwokerto</h4>
            <p class="text-xs text-gray-400 leading-relaxed">
                Penobatan Benaya, Paaza, dan Nazarli sebagai figur panutan sebaya melalui seleksi intensif dan uji advokasi GenRe.
            </p>
        </div>

        <div class="bg-[#0B1221]/90 border border-white/10 rounded-3xl p-6 space-y-4">
            <div class="aspect-video rounded-2xl overflow-hidden bg-slate-900">
                <img src="/images/Bahaya Seks Pranikah & Pornografi bagi Remaja.jpeg" alt="Sosialisasi Triad KRR" class="w-full h-full object-cover">
            </div>
            <span class="text-[10px] font-black uppercase tracking-wider text-teal-400">Edukasi & Sosialisasi</span>
            <h4 class="text-base font-bold text-white">Sosialisasi Triad KRR & Pencegahan Anemia</h4>
            <p class="text-xs text-gray-400 leading-relaxed">
                Edukasi tatap muka menyeluruh mengenai bahaya seks pra-nikah, bahaya pornografi, dan konsumsi tablet tambah darah remaja putri.
            </p>
        </div>

        <div class="bg-[#0B1221]/90 border border-white/10 rounded-3xl p-6 space-y-4">
            <div class="aspect-video rounded-2xl overflow-hidden bg-slate-900">
                <img src="/images/Pergaulan Sehat dan Dasar Keterampilan Konseling Sebaya.jpeg" alt="Pelatihan Konselor" class="w-full h-full object-cover">
            </div>
            <span class="text-[10px] font-black uppercase tracking-wider text-cyan-400">Konseling & Kapasitas</span>
            <h4 class="text-base font-bold text-white">Pelatihan Keterampilan Konselor Sebaya</h4>
            <p class="text-xs text-gray-400 leading-relaxed">
                Pembekalan teknik active listening, empati, dan etika kerahasiaan konseling bagi para pengurus dan konselor remaja.
            </p>
        </div>
    </div>

    <!-- Tab 2: Berita Organisasi -->
    <div x-show="tabKegiatan === 'berita'" class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: none;">
        <div class="bg-[#0B1221]/90 border border-white/10 rounded-3xl p-6 space-y-3">
            <span class="text-[10px] font-black uppercase tracking-wider text-emerald-400">Layanan Aktif</span>
            <h4 class="text-base font-bold text-white">Pelayanan Konseling Sebaya Terbuka Setiap Hari Sekolah</h4>
            <p class="text-xs text-gray-300 leading-relaxed">
                Ruang Sebaya PIK-R SKAKMATT siap menyambut siswa SMK Negeri 1 Purwokerto yang ingin berdiskusi santai atau mencari solusi atas kendala adaptasi remaja.
            </p>
            <a href="/ruang-sebaya" class="inline-block text-xs font-bold text-emerald-400 hover:underline">Akses Ruang Sebaya &rarr;</a>
        </div>

        <div class="bg-[#0B1221]/90 border border-white/10 rounded-3xl p-6 space-y-3">
            <span class="text-[10px] font-black uppercase tracking-wider text-teal-400">Struktur & Sinergi</span>
            <h4 class="text-base font-bold text-white">Pengukuhan Pengurus & Koordinasi 5 Divisi</h4>
            <p class="text-xs text-gray-300 leading-relaxed">
                Divisi Kegiatan, Humas, PDD, PKWU, dan Konselor Sebaya terus merapatkan barisan dalam menjalankan program kerja terpadu.
            </p>
            <a href="/kepengurusan" class="inline-block text-xs font-bold text-teal-400 hover:underline">Lihat Struktur Pengurus &rarr;</a>
        </div>
    </div>

    <!-- Tab 3: Agenda Mendatang -->
    <div x-show="tabKegiatan === 'agenda'" class="bg-[#0B1221]/90 border border-white/10 rounded-3xl p-8 text-center max-w-xl mx-auto space-y-3" style="display: none;">
        <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <h4 class="text-lg font-bold text-white">SKAKMATT Discussion Time & Workshop Karier</h4>
        <p class="text-xs text-gray-300 leading-relaxed">
            Agenda diskusi bulanan dan pembekalan transisi pendidikan menuju dunia kerja untuk siswa tingkat akhir. Pengumuman resmi jadwal akan dirilis melalui pengurus.
        </p>
    </div>
</section>

{{-- ========================================================================= --}}
{{-- 7. GALERI DOKUMENTASI: CERITA DAN MOMEN SKAKMATT (DENGAN LIGHTBOX MODAL)  --}}
{{-- ========================================================================= --}}
<section id="dokumentasi" class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 scroll-mt-24" 
         x-data="{ 
             activeCategory: 'semua', 
             lightboxOpen: false, 
             lightboxImg: '', 
             lightboxCaption: '' 
         }">
    
    <div class="text-center max-w-2xl mx-auto mb-10 space-y-3">
        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs font-bold uppercase tracking-wider">
            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
            Galeri Foto
        </span>
        <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight">
            Cerita dan Momen <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">SKAKMATT</span>
        </h2>
        <p class="text-gray-400 text-xs sm:text-sm">
            Kumpulan rekam jejak visual perjalanan, pemilihan Duta GenRe, dan kebersamaan seluruh anggota.
        </p>

        <!-- Kategori Filter -->
        <div class="pt-4 flex justify-center gap-2 flex-wrap">
            <button @click="activeCategory = 'semua'" :class="activeCategory === 'semua' ? 'bg-emerald-500 text-white font-bold' : 'bg-white/5 text-gray-400 hover:text-white'" class="px-4 py-1.5 rounded-full text-xs transition-all">Semua</button>
            <button @click="activeCategory = 'duta_genre'" :class="activeCategory === 'duta_genre' ? 'bg-emerald-500 text-white font-bold' : 'bg-white/5 text-gray-400 hover:text-white'" class="px-4 py-1.5 rounded-full text-xs transition-all">Duta GenRe</button>
            <button @click="activeCategory = 'pemilihan_duta'" :class="activeCategory === 'pemilihan_duta' ? 'bg-emerald-500 text-white font-bold' : 'bg-white/5 text-gray-400 hover:text-white'" class="px-4 py-1.5 rounded-full text-xs transition-all">Pemilihan Duta GenRe</button>
            <button @click="activeCategory = 'edukasi'" :class="activeCategory === 'edukasi' ? 'bg-emerald-500 text-white font-bold' : 'bg-white/5 text-gray-400 hover:text-white'" class="px-4 py-1.5 rounded-full text-xs transition-all">Edukasi & Sosialisasi</button>
            <button @click="activeCategory = 'kebersamaan'" :class="activeCategory === 'kebersamaan' ? 'bg-emerald-500 text-white font-bold' : 'bg-white/5 text-gray-400 hover:text-white'" class="px-4 py-1.5 rounded-full text-xs transition-all">Kebersamaan Anggota</button>
            <button @click="activeCategory = 'organisasi'" :class="activeCategory === 'organisasi' ? 'bg-emerald-500 text-white font-bold' : 'bg-white/5 text-gray-400 hover:text-white'" class="px-4 py-1.5 rounded-full text-xs transition-all">Kegiatan Organisasi</button>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        <!-- Item 1: all_genre (Duta GenRe) -->
        <div x-show="activeCategory === 'semua' || activeCategory === 'duta_genre'" 
             @click="lightboxImg = '/images/all_genre.jpeg'; lightboxCaption = 'Foto Bersama Ketiga Duta GenRe SMK Negeri 1 Purwokerto'; lightboxOpen = true"
             class="group relative aspect-square rounded-2xl overflow-hidden bg-slate-900 border border-white/10 cursor-pointer hover:border-emerald-500/40 transition-all">
            <img src="/images/all_genre.jpeg" alt="Foto Bersama Duta GenRe" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-3 flex flex-col justify-end">
                <span class="text-[10px] font-bold text-amber-300">Duta GenRe</span>
                <p class="text-xs font-semibold text-white truncate">Foto Bersama Tiga Duta</p>
            </div>
        </div>

        <!-- Item 2: pendutgen (Pemilihan Duta GenRe) -->
        <div x-show="activeCategory === 'semua' || activeCategory === 'pemilihan_duta' || activeCategory === 'organisasi'" 
             @click="lightboxImg = '/images/pendutgen.png'; lightboxCaption = 'Momen Grand Final Pemilihan Duta GenRe SMK Negeri 1 Purwokerto (12 Juni 2025)'; lightboxOpen = true"
             class="group relative aspect-square rounded-2xl overflow-hidden bg-slate-900 border border-white/10 cursor-pointer hover:border-emerald-500/40 transition-all">
            <img src="/images/pendutgen.png" alt="Pemilihan Duta GenRe" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-3 flex flex-col justify-end">
                <span class="text-[10px] font-bold text-emerald-300">Pemilihan Duta GenRe</span>
                <p class="text-xs font-semibold text-white truncate">Grand Final 12 Juni 2025</p>
            </div>
        </div>

        <!-- Item 3: benaya_genre (Duta GenRe) -->
        <div x-show="activeCategory === 'semua' || activeCategory === 'duta_genre'" 
             @click="lightboxImg = '/images/benaya_genre.jpeg'; lightboxCaption = 'Benaya Iskha Ghifari - Duta GenRe Putri SMK Negeri 1 Purwokerto'; lightboxOpen = true"
             class="group relative aspect-square rounded-2xl overflow-hidden bg-slate-900 border border-white/10 cursor-pointer hover:border-emerald-500/40 transition-all">
            <img src="/images/benaya_genre.jpeg" alt="Benaya Iskha Ghifari" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-3 flex flex-col justify-end">
                <span class="text-[10px] font-bold text-emerald-300">Duta GenRe Putri</span>
                <p class="text-xs font-semibold text-white truncate">Benaya Iskha Ghifari</p>
            </div>
        </div>

        <!-- Item 4: paaza_genre (Duta GenRe) -->
        <div x-show="activeCategory === 'semua' || activeCategory === 'duta_genre'" 
             @click="lightboxImg = '/images/paaza_genre.jpeg'; lightboxCaption = 'Muhammad Paaza Abidin - Duta GenRe Putra SMK Negeri 1 Purwokerto'; lightboxOpen = true"
             class="group relative aspect-square rounded-2xl overflow-hidden bg-slate-900 border border-white/10 cursor-pointer hover:border-emerald-500/40 transition-all">
            <img src="/images/paaza_genre.jpeg" alt="Muhammad Paaza Abidin" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-3 flex flex-col justify-end">
                <span class="text-[10px] font-bold text-cyan-300">Duta GenRe Putra</span>
                <p class="text-xs font-semibold text-white truncate">Muhammad Paaza Abidin</p>
            </div>
        </div>

        <!-- Item 5: nazarli_genre (Duta GenRe) -->
        <div x-show="activeCategory === 'semua' || activeCategory === 'duta_genre'" 
             @click="lightboxImg = '/images/nazarli_genre.jpeg'; lightboxCaption = 'Nazarli Nadiah Putri Yuarliana - Duta GenRe Influencer SMK Negeri 1 Purwokerto'; lightboxOpen = true"
             class="group relative aspect-square rounded-2xl overflow-hidden bg-slate-900 border border-white/10 cursor-pointer hover:border-emerald-500/40 transition-all">
            <img src="/images/nazarli_genre.jpeg" alt="Nazarli Nadiah Putri Yuarliana" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-3 flex flex-col justify-end">
                <span class="text-[10px] font-bold text-teal-300">Duta GenRe Influencer</span>
                <p class="text-xs font-semibold text-white truncate">Nazarli Nadiah Putri</p>
            </div>
        </div>

        <!-- Item 6: all.jpeg (Kebersamaan Anggota) -->
        <div x-show="activeCategory === 'semua' || activeCategory === 'kebersamaan' || activeCategory === 'organisasi'" 
             @click="lightboxImg = '/images/all.jpeg'; lightboxCaption = 'Keluarga Besar Anggota dan Pengurus PIK-R SKAKMATT SMK Negeri 1 Purwokerto'; lightboxOpen = true"
             class="group relative aspect-square rounded-2xl overflow-hidden bg-slate-900 border border-white/10 cursor-pointer hover:border-emerald-500/40 transition-all">
            <img src="/images/all.jpeg" alt="Keluarga Besar PIK-R SKAKMATT" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-3 flex flex-col justify-end">
                <span class="text-[10px] font-bold text-emerald-300">Kebersamaan Anggota</span>
                <p class="text-xs font-semibold text-white truncate">Keluarga Besar SKAKMATT</p>
            </div>
        </div>

        <!-- Item 7: Pembekalan Pildugen (Edukasi & Sosialisasi) -->
        <div x-show="activeCategory === 'semua' || activeCategory === 'edukasi' || activeCategory === 'pemilihan_duta'" 
             @click="lightboxImg = '/images/pembekalan pildugen dari mba dista.jpeg'; lightboxCaption = 'Sesi Pembekalan Materi Duta GenRe bersama Narasumber Ahli'; lightboxOpen = true"
             class="group relative aspect-square rounded-2xl overflow-hidden bg-slate-900 border border-white/10 cursor-pointer hover:border-emerald-500/40 transition-all">
            <img src="/images/pembekalan pildugen dari mba dista.jpeg" alt="Pembekalan Duta GenRe" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" onerror="this.src='/images/pendutgen.png'">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-3 flex flex-col justify-end">
                <span class="text-[10px] font-bold text-cyan-300">Edukasi & Sosialisasi</span>
                <p class="text-xs font-semibold text-white truncate">Pembekalan Duta GenRe</p>
            </div>
        </div>

        <!-- Item 8: Konseling & Diskusi (Edukasi & Kebersamaan) -->
        <div x-show="activeCategory === 'semua' || activeCategory === 'edukasi' || activeCategory === 'organisasi'" 
             @click="lightboxImg = '/images/Pergaulan Sehat dan Dasar Keterampilan Konseling Sebaya.jpeg'; lightboxCaption = 'Workshop Pergaulan Sehat dan Keterampilan Konseling Sebaya'; lightboxOpen = true"
             class="group relative aspect-square rounded-2xl overflow-hidden bg-slate-900 border border-white/10 cursor-pointer hover:border-emerald-500/40 transition-all">
            <img src="/images/Pergaulan Sehat dan Dasar Keterampilan Konseling Sebaya.jpeg" alt="Workshop Konseling Sebaya" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" onerror="this.src='/images/all.jpeg'">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-3 flex flex-col justify-end">
                <span class="text-[10px] font-bold text-teal-300">Kegiatan Organisasi</span>
                <p class="text-xs font-semibold text-white truncate">Keterampilan Konseling</p>
            </div>
        </div>
    </div>

    <!-- Lightbox Modal untuk Melihat Foto dalam Ukuran Penuh -->
    <div x-show="lightboxOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md" 
         style="display: none;" 
         @keydown.escape.window="lightboxOpen = false">
        
        <div class="relative max-w-4xl w-full bg-[#0B1221] border border-white/20 rounded-3xl overflow-hidden shadow-2xl">
            <!-- Close Button -->
            <button @click="lightboxOpen = false" class="absolute top-4 right-4 z-10 w-9 h-9 rounded-full bg-black/60 hover:bg-black/90 text-white flex items-center justify-center border border-white/20 transition-all">
                &times;
            </button>

            <div class="max-h-[75vh] overflow-hidden flex items-center justify-center bg-black">
                <img :src="lightboxImg" :alt="lightboxCaption" class="max-h-[75vh] w-auto object-contain">
            </div>

            <div class="p-4 bg-[#0B1221] border-t border-white/10 flex justify-between items-center text-xs">
                <p class="text-white font-medium" x-text="lightboxCaption"></p>
                <button @click="lightboxOpen = false" class="text-gray-400 hover:text-white text-xs">Tutup</button>
            </div>
        </div>
    </div>
</section>

{{-- ========================================================================= --}}
{{-- CTA BLOCK: DAFTAR ANGGOTA & BUAT AKUN SISWA (PRESERVED)                   --}}
{{-- ========================================================================= --}}
<section class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-gradient-to-r from-emerald-900/40 via-[#0B1221] to-teal-900/40 backdrop-blur-lg border border-emerald-500/30 rounded-3xl p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-6 shadow-2xl">
        <div class="space-y-2 text-center md:text-left">
            <span class="text-xs font-black uppercase tracking-wider text-emerald-400">Komunitas & Aksi Nyata</span>
            <h3 class="text-2xl md:text-3xl font-extrabold text-white">
                Bergerak Bersama, Mengukir <span class="text-emerald-400">Cerita dan Momen</span> Berharga
            </h3>
            <p class="text-gray-300 text-sm max-w-xl">
                Jadilah bagian dari keluarga besar PIK-R SKAKMATT SMK Negeri 1 Purwokerto. Ikuti setiap langkah perubahan positif remaja.
            </p>
        </div>
        <div class="shrink-0 flex flex-wrap justify-center gap-3">
            <a href="/pendaftaran-anggota" class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-8 py-3.5 rounded-full font-bold hover:from-emerald-400 hover:to-teal-400 transition-all duration-300 shadow-xl hover:-translate-y-0.5 inline-block text-sm">
                Daftar Menjadi Anggota
            </a>
            <a href="/register" class="bg-white/10 text-white px-6 py-3.5 rounded-full font-bold hover:bg-white/20 transition-all duration-300 border border-white/20 inline-block text-sm">
                Daftar Akun Siswa
            </a>
        </div>
    </div>
</section>
@endsection
