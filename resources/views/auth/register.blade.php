@extends('layouts.guest')

@section('title', 'Daftar Akun Anggota PIK-R')

@section('content')
<div class="text-center mb-6" x-data="{ 
    otpSent: false, 
    otpLoading: false, 
    countdown: 0, 
    otpMessage: '', 
    otpError: '',
    showPassword: false,
    sendOtp() {
        const email = document.getElementById('email').value;
        const name = document.getElementById('name').value;
        if (!email) {
            this.otpError = 'Harap isi alamat email terlebih dahulu.';
            return;
        }
        this.otpLoading = true;
        this.otpError = '';
        this.otpMessage = '';

        fetch('{{ route('register.otp') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: email, name: name })
        })
        .then(res => res.json())
        .then(data => {
            this.otpLoading = false;
            if (data.success) {
                this.otpSent = true;
                this.otpMessage = data.message;
                if (data.dev_code) {
                    const codeEl = document.getElementById('verification_code');
                    if (codeEl) codeEl.value = data.dev_code;
                }
                this.countdown = 60;
                const timer = setInterval(() => {
                    this.countdown--;
                    if (this.countdown <= 0) clearInterval(timer);
                }, 1000);
            } else {
                this.otpError = data.message || 'Gagal mengirim kode verifikasi.';
            }
        })
        .catch(err => {
            this.otpLoading = false;
            this.otpError = 'Terjadi kesalahan jaringan saat mengirim kode.';
        });
    }
}">
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-xs font-semibold mb-3">
        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
        Registrasi Anggota PIK-R SKAKMATT
    </div>
    <h2 class="text-2xl font-black text-white tracking-tight">Pendaftaran Akun Siswa</h2>
    <p class="text-gray-400 text-xs mt-1">Lengkapi data identitas siswa untuk validasi keanggotaan</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4 text-left mt-6">
        @csrf

        <!-- Nama Lengkap & NISN -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label for="name" class="block text-xs font-semibold text-gray-300 mb-1">
                    Nama Lengkap Siswa <span class="text-emerald-400">*</span>
                </label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Nama sesuai pendaftaran anggota"
                    class="w-full !bg-black/40 border border-white/10 rounded-xl px-3.5 py-2.5 text-xs !text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all"
                    style="background-color: rgba(0, 0, 0, 0.4) !important; color: #ffffff !important;"
                    required
                />
                @error('name')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nisn" class="block text-xs font-semibold text-gray-300 mb-1">
                    NIS / NISN (Identitas Unik)
                </label>
                <input
                    id="nisn"
                    type="text"
                    name="nisn"
                    value="{{ old('nisn') }}"
                    placeholder="Contoh: 0071234567"
                    class="w-full !bg-black/40 border border-white/10 rounded-xl px-3.5 py-2.5 text-xs !text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all"
                    style="background-color: rgba(0, 0, 0, 0.4) !important; color: #ffffff !important;"
                />
                @error('nisn')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Kelas & No WA -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label for="kelas" class="block text-xs font-semibold text-gray-300 mb-1">
                    Kelas <span class="text-emerald-400">*</span>
                </label>
                <select
                    id="kelas"
                    name="kelas"
                    class="w-full !bg-black/40 border border-white/10 rounded-xl px-3.5 py-2.5 text-xs !text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all"
                    style="background-color: rgba(0, 0, 0, 0.4) !important; color: #ffffff !important;"
                    required
                >
                    <option value="" class="bg-slate-900 text-gray-400">Pilih Kelas...</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas }}" class="bg-slate-900 text-white" {{ old('kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                    @endforeach
                </select>
                @error('kelas')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-xs font-semibold text-gray-300 mb-1">
                    Nomor WhatsApp / HP <span class="text-emerald-400">*</span>
                </label>
                <input
                    id="phone"
                    type="text"
                    name="phone"
                    value="{{ old('phone') }}"
                    class="w-full !bg-black/40 border border-white/10 rounded-xl px-3.5 py-2.5 text-xs !text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all"
                    style="background-color: rgba(0, 0, 0, 0.4) !important; color: #ffffff !important;"
                    required
                />
                @error('phone')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Tempat Lahir & Tanggal Lahir -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label for="birth_place" class="block text-xs font-semibold text-gray-300 mb-1">
                    Tempat Lahir <span class="text-emerald-400">*</span>
                </label>
                <input
                    id="birth_place"
                    type="text"
                    name="birth_place"
                    value="{{ old('birth_place') }}"
                    class="w-full !bg-black/40 border border-white/10 rounded-xl px-3.5 py-2.5 text-xs !text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all"
                    style="background-color: rgba(0, 0, 0, 0.4) !important; color: #ffffff !important;"
                    required
                />
                @error('birth_place')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="birth_date" class="block text-xs font-semibold text-gray-300 mb-1">
                    Tanggal Lahir <span class="text-emerald-400">*</span>
                </label>
                <input
                    id="birth_date"
                    type="date"
                    name="birth_date"
                    value="{{ old('birth_date') }}"
                    class="w-full !bg-black/40 border border-white/10 rounded-xl px-3.5 py-2.5 text-xs !text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all"
                    style="background-color: rgba(0, 0, 0, 0.4) !important; color: #ffffff !important;"
                    required
                />
                @error('birth_date')
                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Email & Tombol Kirim OTP -->
        <div>
            <label for="email" class="block text-xs font-semibold text-gray-300 mb-1">
                Email Akun (@gmail.com) <span class="text-emerald-400">*</span>
            </label>
            <div class="flex gap-2">
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="flex-1 !bg-black/40 border border-white/10 rounded-xl px-3.5 py-2.5 text-xs !text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all"
                    style="background-color: rgba(0, 0, 0, 0.4) !important; color: #ffffff !important;"
                    required
                />
                <button
                    type="button"
                    @click="sendOtp()"
                    :disabled="otpLoading || countdown > 0"
                    class="px-3.5 py-2.5 bg-cyan-600/30 hover:bg-cyan-500/40 text-cyan-300 border border-cyan-500/40 font-bold rounded-xl text-xs transition-all shrink-0 disabled:opacity-50 flex items-center gap-1.5"
                >
                    <span x-show="otpLoading" class="w-3 h-3 border-2 border-cyan-300 border-t-transparent rounded-full animate-spin"></span>
                    <span x-show="!otpLoading && countdown === 0">Kirim Kode Verif</span>
                    <span x-show="countdown > 0" x-text="countdown + 's'"></span>
                </button>
            </div>
            @error('email')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror

            <!-- Feedback OTP Ajax -->
            <div x-show="otpMessage" class="mt-2 p-3 bg-emerald-500/15 border border-emerald-500/30 rounded-xl text-emerald-300 text-xs flex items-center gap-2" style="display: none;">
                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <span x-text="otpMessage"></span>
            </div>
            <div x-show="otpError" class="mt-2 p-3 bg-red-500/15 border border-red-500/30 rounded-xl text-red-300 text-xs flex items-center gap-2" style="display: none;">
                <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span x-text="otpError"></span>
            </div>
        </div>

        <!-- Kode Verifikasi Input -->
        <div>
            <label for="verification_code" class="block text-xs font-semibold text-gray-300 mb-1">
                Kode Verifikasi Email (6 Digit) <span class="text-emerald-400">*</span>
            </label>
            <input
                id="verification_code"
                type="text"
                name="verification_code"
                maxlength="6"
                value="{{ old('verification_code') }}"
                class="w-full !bg-black/40 border border-white/10 rounded-xl px-3.5 py-2.5 text-xs !text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all font-mono tracking-widest text-center text-sm font-bold"
                style="background-color: rgba(0, 0, 0, 0.4) !important; color: #ffffff !important;"
                required
            />
            @error('verification_code')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password & Konfirmasi -->
        <div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-300 mb-1">
                        Buat Password <span class="text-emerald-400">*</span>
                    </label>
                    <input
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        type="password"
                        name="password"
                        class="w-full !bg-black/40 border border-white/10 rounded-xl px-3.5 py-2.5 text-xs !text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all"
                        style="background-color: rgba(0, 0, 0, 0.4) !important; color: #ffffff !important;"
                        required
                    />
                    @error('password')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-gray-300 mb-1">
                        Konfirmasi Password <span class="text-emerald-400">*</span>
                    </label>
                    <input
                        id="password_confirmation"
                        :type="showPassword ? 'text' : 'password'"
                        type="password"
                        name="password_confirmation"
                        class="w-full !bg-black/40 border border-white/10 rounded-xl px-3.5 py-2.5 text-xs !text-white focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all"
                        style="background-color: rgba(0, 0, 0, 0.4) !important; color: #ffffff !important;"
                        required
                    />
                </div>
            </div>

            <!-- Checkbox Show Password -->
            <div class="flex items-center gap-2 mt-2.5">
                <input
                    type="checkbox"
                    id="show_password_check"
                    x-model="showPassword"
                    class="w-4 h-4 rounded border-white/20 bg-black/40 text-emerald-500 focus:ring-emerald-500/40 focus:ring-offset-0 cursor-pointer accent-emerald-500"
                    style="background-color: rgba(0, 0, 0, 0.4) !important;"
                />
                <label for="show_password_check" class="text-xs text-gray-400 select-none cursor-pointer hover:text-gray-200 transition-colors flex items-center gap-1.5">
                    <span>Tampilkan Password</span>
                </label>
            </div>
        </div>

        <button
            type="submit"
            class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-xl px-4 py-3 mt-4 hover:from-emerald-400 hover:to-teal-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 shadow-[0_0_15px_rgba(16,185,129,0.3)] hover:shadow-[0_0_25px_rgba(16,185,129,0.5)] transform transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0 text-xs tracking-wider uppercase"
        >
            Daftar & Ajukan Verifikasi
        </button>
    </form>

    <div class="mt-6 text-center text-xs text-gray-500">
        Sudah memiliki akun terverifikasi?
        <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 font-semibold transition-colors">
            Login di sini
        </a>
    </div>
</div>
@endsection
