@extends('layouts.public')

@section('title', 'Penerimaan Anggota Baru - PIK-R SKAKMATT')

@section('content')
<section class="relative pt-24 pb-20 overflow-hidden">
    <!-- Ambient Glow Background -->
    <div class="absolute inset-0 bg-[#070B19] pointer-events-none">
        <div class="absolute top-1/4 right-1/4 w-[500px] h-[500px] bg-emerald-500/10 blur-[130px] rounded-full"></div>
        <div class="absolute bottom-1/4 left-1/4 w-[500px] h-[500px] bg-teal-500/10 blur-[130px] rounded-full"></div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Header -->
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-4">
                <span class="w-2 h-2 rounded-full {{ $isMemberOpen ? 'bg-emerald-400 animate-pulse' : 'bg-amber-400' }}"></span>
                {{ $isMemberOpen ? 'Penerimaan Anggota Baru Dibuka' : 'Penerimaan Anggota Baru Ditutup' }}
            </span>
            <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                Penerimaan Anggota PIK-R SKAKMATT
            </h1>
            <p class="text-gray-300 text-sm sm:text-base mt-3 leading-relaxed">
                Wadah resmi pengembangan karakter, pendidik sebaya, dan konselor remaja di SMK Negeri 1 Purwokerto.
            </p>
        </div>

        @if(session('success'))
        <div class="mb-8 p-4 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-200 text-sm flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <div>{{ session('success') }}</div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-8 p-4 rounded-2xl bg-rose-500/20 border border-rose-500/40 text-rose-200 text-sm flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            <div>{{ session('error') }}</div>
        </div>
        @endif

        <!-- Case 1: Sudah Jadi Anggota Aktif -->
        @if($existingMember && $existingMember->status === 'AKTIF')
        <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-emerald-500/30 rounded-3xl p-8 sm:p-10 shadow-2xl text-center space-y-6">
            <div class="w-20 h-20 rounded-3xl bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center mx-auto text-emerald-400 shadow-xl shadow-emerald-950/50">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold uppercase tracking-wider border border-emerald-500/30">
                    Status: Anggota Aktif Resmi
                </span>
                <h3 class="text-2xl font-bold text-white mt-3">
                    Halo, {{ $existingMember->name }}!
                </h3>
                <p class="text-gray-300 text-sm mt-2 max-w-lg mx-auto leading-relaxed">
                    Anda telah resmi terdaftar sebagai anggota aktif Ekstrakurikuler PIK-R SKAKMATT SMK Negeri 1 Purwokerto (Kelas: {{ $existingMember->kelas }}).
                </p>
            </div>
            <div class="pt-4 flex justify-center gap-4">
                <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg transition-all">
                    Buka Dashboard Anggota &rarr;
                </a>
            </div>
        </div>

        <!-- Case 2: Sudah Pernah Mengajukan (Status Pending / Perlu Pemeriksaan / Ditolak) -->
        @elseif($existingApplication)
        <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-8 sm:p-10 shadow-2xl space-y-6">
            <div class="flex items-center justify-between flex-wrap gap-4 border-b border-white/10 pb-6">
                <div>
                    <h3 class="text-xl font-bold text-white">Status Permohonan Keanggotaan</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Diajukan pada: {{ $existingApplication->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                </div>
                <div>
                    @if($existingApplication->status === 'MENUNGGU_PERSETUJUAN' || $existingApplication->status === 'MENUNGGU_VALIDASI')
                        <span class="px-3.5 py-1.5 rounded-full bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                            Menunggu Persetujuan Pengurus
                        </span>
                    @elseif($existingApplication->status === 'PERLU_PEMERIKSAAN')
                        <span class="px-3.5 py-1.5 rounded-full bg-amber-500/20 text-amber-300 border border-amber-500/30 text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            Perlu Pemeriksaan Manual
                        </span>
                    @elseif($existingApplication->status === 'DISETUJUI')
                        <span class="px-3.5 py-1.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            Disetujui
                        </span>
                    @elseif($existingApplication->status === 'DITOLAK')
                        <span class="px-3.5 py-1.5 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/30 text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                            Belum Diterima / Perlu Revisi
                        </span>
                    @endif
                </div>
            </div>

            <!-- Detail Data Diajukan -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div class="p-4 rounded-2xl bg-white/5 border border-white/5">
                    <span class="text-gray-400 block mb-1">Nama Lengkap</span>
                    <span class="text-white font-bold text-sm">{{ $existingApplication->name }}</span>
                </div>
                <div class="p-4 rounded-2xl bg-white/5 border border-white/5">
                    <span class="text-gray-400 block mb-1">Kelas</span>
                    <span class="text-white font-bold text-sm">{{ $existingApplication->kelas }}</span>
                </div>
                <div class="p-4 rounded-2xl bg-white/5 border border-white/5">
                    <span class="text-gray-400 block mb-1">NIS / NISN</span>
                    <span class="text-white font-bold text-sm">{{ $existingApplication->nisn ?: '-' }}</span>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-white/5 border border-white/5 space-y-1.5 text-xs">
                <span class="text-gray-400 block">Alasan / Motivasi Bergabung:</span>
                <p class="text-gray-200 leading-relaxed italic">"{{ $existingApplication->reason }}"</p>
            </div>

            @if($existingApplication->validation_notes)
            <div class="p-4 rounded-2xl bg-cyan-950/30 border border-cyan-500/20 text-xs text-cyan-200 space-y-1">
                <span class="font-bold uppercase tracking-wider text-[10px] text-cyan-400">Catatan Validasi Identitas:</span>
                <p>{{ $existingApplication->validation_notes }}</p>
            </div>
            @endif

            @if($existingApplication->status === 'DISETUJUI')
            <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-950/60 to-teal-950/60 border border-emerald-500/40 text-xs text-white space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-emerald-300">Pendaftaran Keanggotaan Telah Disetujui!</h4>
                        <p class="text-gray-300 mt-1 leading-relaxed">
                            Selamat! Formulir keanggotaan Anda telah disetujui oleh pengurus. Silakan lanjut ke <strong>Tahap 2: Pendaftaran Akun</strong> menggunakan NISN dan Email Anda di atas agar sistem backend dapat memverifikasi otomatis akun Anda.
                        </p>
                    </div>
                </div>
                <div class="pt-2">
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg transition-all">
                        <span>Lanjut Buat Akun Siswa</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
            @endif

            @if($existingApplication->status === 'DITOLAK' && $existingApplication->rejection_reason)
            <div class="p-4 rounded-2xl bg-rose-950/30 border border-rose-500/30 text-xs text-rose-200 space-y-1">
                <span class="font-bold uppercase tracking-wider text-[10px] text-rose-400">Catatan dari Pengurus:</span>
                <p>{{ $existingApplication->rejection_reason }}</p>
            </div>
            @endif

            @if($existingApplication->status === 'DITOLAK' || $existingApplication->status === 'PERLU_PEMERIKSAAN')
            <!-- Form Perbaikan Data -->
            <div class="border-t border-white/10 pt-6">
                <h4 class="text-base font-bold text-white mb-4">Perbarui atau Ajukan Ulang Data</h4>
                <form action="{{ route('pendaftaran-anggota.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 mb-1">Nama Lengkap Siswa</label>
                            <input type="text" name="name" value="{{ old('name', $existingApplication->name) }}" required
                                   class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white text-xs focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-300 mb-1">Kelas</label>
                            <select name="kelas" required class="w-full px-4 py-2.5 rounded-xl bg-[#070B19] border border-white/10 text-white text-xs focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                                @foreach($kelasList as $k)
                                <option value="{{ $k }}" {{ old('kelas', $existingApplication->kelas) === $k ? 'selected' : '' }}>{{ $k }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">NIS / NISN (Opsional)</label>
                        <input type="text" name="nisn" value="{{ old('nisn', $existingApplication->nisn) }}"
                               class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white text-xs focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">Alasan Bergabung</label>
                        <textarea name="reason" rows="3" required class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white text-xs focus:ring-2 focus:ring-emerald-400 focus:outline-none">{{ old('reason', $existingApplication->reason) }}</textarea>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer text-xs text-gray-300">
                        <input type="checkbox" name="data_consent" value="1" required class="rounded bg-white/10 border-white/20 text-emerald-500 focus:ring-emerald-400">
                        <span>Saya menyatakan data yang saya kirimkan adalah benar dan bersedia diverifikasi oleh pengurus.</span>
                    </label>
                    <button type="submit" class="py-2.5 px-6 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider transition-all">
                        Kirim Ulang Permohonan
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Case 3: Penerimaan Ditutup -->
        @elseif(!$isMemberOpen)
        <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-8 sm:p-10 shadow-2xl text-center space-y-6">
            <div class="w-16 h-16 rounded-2xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center mx-auto text-amber-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-white">Penerimaan Anggota Baru Saat Ini Ditutup</h3>
                <p class="text-sm text-gray-300 mt-2 max-w-lg mx-auto leading-relaxed">
                    Pengurus sedang memproses data keanggotaan atau belum membuka gelombang penerimaan baru. Silakan pantau pengumuman resmi berkala.
                </p>
            </div>

            @if($settings->member_registration_start || $settings->member_registration_end)
            <div class="p-4 rounded-2xl bg-white/5 border border-white/10 text-xs text-left max-w-md mx-auto space-y-1">
                <span class="font-bold text-amber-300 uppercase tracking-wider text-[10px] block">Jadwal Penerimaan:</span>
                @if($settings->member_registration_start)
                <div class="text-gray-300">Mulai: <span class="text-white font-semibold">{{ $settings->member_registration_start->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span></div>
                @endif
                @if($settings->member_registration_end)
                <div class="text-gray-300">Berakhir: <span class="text-white font-semibold">{{ $settings->member_registration_end->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span></div>
                @endif
            </div>
            @endif

            <div class="pt-4 flex justify-center gap-3">
                <a href="{{ url('/#tentang') }}" class="py-2.5 px-6 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white text-xs font-semibold border border-white/10 transition-colors">
                    Pelajari Tentang PIK-R SKAKMATT
                </a>
            </div>
        </div>

        <!-- Case 4: Formulir Pengajuan Anggota (Buka untuk Publik & Siswa) -->
        @else
        <div class="bg-[#0B1221]/90 backdrop-blur-xl border border-white/10 rounded-3xl p-8 sm:p-10 shadow-2xl space-y-8">
            <!-- Informasi & Persyaratan -->
            @if($settings->announcement_description || $settings->requirements)
            <div class="p-6 rounded-2xl bg-emerald-950/30 border border-emerald-500/20 space-y-3">
                <h4 class="font-bold text-emerald-300 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Informasi & Persyaratan Penerimaan Anggota</span>
                </h4>
                @if($settings->announcement_description)
                <p class="text-gray-300 text-xs leading-relaxed">{{ $settings->announcement_description }}</p>
                @endif
                @if($settings->requirements)
                <div class="text-xs text-gray-300 whitespace-pre-line border-t border-emerald-500/20 pt-2 font-mono">
                    {{ $settings->requirements }}
                </div>
                @endif
            </div>
            @endif

            <div class="border-b border-white/10 pb-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                    Formulir Pendaftaran Anggota Baru
                </h3>
                <p class="text-xs text-gray-400 mt-1">
                    Tahap 1: Daftarkan diri Anda sebagai calon anggota. Setelah permohonan disetujui oleh pengurus, Anda dapat membuat akun login untuk diverifikasi otomatis oleh sistem.
                </p>
            </div>

            <!-- Form -->
            <form action="{{ route('pendaftaran-anggota.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-2">Nama Lengkap Siswa <span class="text-rose-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user?->name) }}" required
                               placeholder="Nama sesuai data sekolah / buku induk"
                               class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white text-sm focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                        <span class="text-[10px] text-gray-400 block mt-1">Harap gunakan nama lengkap resmi, bukan nama panggilan.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-2">NIS / NISN (Nomor Induk Siswa Nasional)</label>
                        <input type="text" name="nisn" value="{{ old('nisn', $user?->nisn) }}"
                               placeholder="Contoh: 0071234567 (10 digit)"
                               class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white text-sm focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                        <span class="text-[10px] text-gray-400 block mt-1">Digunakan untuk pencocokan otomatis identitas ke Buku Induk & registrasi akun.</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-2">Kelas <span class="text-rose-400">*</span></label>
                        <select name="kelas" required class="w-full px-4 py-3 rounded-xl bg-[#070B19] border border-white/10 text-white text-sm focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                            <option value="">-- Pilih Kelas Anda --</option>
                            @foreach($kelasList as $k)
                            <option value="{{ $k }}" {{ old('kelas', $user?->kelas) === $k ? 'selected' : '' }}>{{ $k }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-2">Kompetensi Keahlian / Jurusan</label>
                        <select name="jurusan" class="w-full px-4 py-3 rounded-xl bg-[#070B19] border border-white/10 text-white text-sm focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach($jurusanList as $j)
                            <option value="{{ $j }}" {{ old('jurusan') === $j ? 'selected' : '' }}>{{ $j }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-2">Nomor WhatsApp / HP Aktif <span class="text-rose-400">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $user?->phone) }}" required
                               placeholder="Contoh: 081234567890"
                               class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white text-sm focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                        <span class="text-[10px] text-gray-400 block mt-1">Untuk koordinasi kegiatan dan konfirmasi dari pengurus.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-2">Alamat Email Aktif (Gmail) <span class="text-rose-400">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user?->email) }}" required
                               placeholder="nama@gmail.com"
                               class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white text-sm focus:ring-2 focus:ring-emerald-400 focus:outline-none">
                        <span class="text-[10px] text-gray-400 block mt-1">Gunakan email yang sama saat nanti membuat akun siswa.</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-2">Alasan & Motivasi Ingin Bergabung <span class="text-rose-400">*</span></label>
                    <textarea name="reason" rows="4" required
                              placeholder="Ceritakan alasan dan motivasi Anda bergabung dengan PIK-R SKAKMATT (min. 15 karakter)..."
                              class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white text-sm focus:ring-2 focus:ring-emerald-400 focus:outline-none">{{ old('reason') }}</textarea>
                </div>

                <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                    <label class="flex items-start gap-3 cursor-pointer text-xs text-gray-300">
                        <input type="checkbox" name="data_consent" value="1" required class="mt-0.5 rounded bg-white/10 border-white/20 text-emerald-500 focus:ring-emerald-400">
                        <span class="leading-relaxed">
                            Saya menyatakan bahwa data yang saya kirimkan adalah benar, sesuai identitas resmi di SMK Negeri 1 Purwokerto, dan bersedia mengikuti proses verifikasi keanggotaan oleh pengurus PIK-R SKAKMATT.
                        </span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 px-8 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-sm uppercase tracking-wider shadow-lg shadow-emerald-950/40 transition-all">
                        Kirim Formulir Permohonan Keanggotaan
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>
</section>
@endsection
