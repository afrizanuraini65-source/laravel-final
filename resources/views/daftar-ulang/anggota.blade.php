@extends('layouts.admin')

@section('title', 'Daftar Ulang - PIK-R SKAKMATT')

@section('header', 'Daftar Ulang Anggota')

@section('content')
<div class="px-6 py-8" x-data="{ formOpen: false, statusAktif: '1' }">
    <div class="max-w-4xl mx-auto">
        
        <div class="mb-10">
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Daftar <span class="text-teal-400">Ulang</span></h1>
            <p class="text-slate-400 mt-2">Konfirmasi keaktifan dan perbarui data anggota Anda.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl font-medium flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl font-medium">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-slate-800/80 backdrop-blur-md border border-slate-700 rounded-3xl p-8 shadow-xl">
            @if(!$reregistration)
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-900/80 border-2 border-slate-700 text-slate-400 mb-4 shadow-inner">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Halo, {{ $user->name }}!</h2>
                    <p class="text-slate-400 max-w-lg mx-auto">Anda belum melakukan daftar ulang untuk periode ini. Silakan klik tombol di bawah untuk mengonfirmasi keaktifan Anda dan memperbarui data.</p>
                    
                    <div class="mt-4 flex justify-center">
                        <span class="px-4 py-2 bg-slate-900 border border-slate-700 text-slate-400 font-bold text-sm rounded-xl uppercase tracking-wider">
                            Status: Belum Daftar Ulang
                        </span>
                    </div>

                    <div class="mt-8">
                        <button @click="formOpen = true" class="px-8 py-3.5 bg-gradient-to-r from-cyan-500 to-emerald-500 text-white font-bold text-base rounded-2xl shadow-[0_0_20px_rgba(6,182,212,0.4)] hover:shadow-[0_0_30px_rgba(6,182,212,0.6)] hover:-translate-y-1 transition-all flex items-center gap-2 mx-auto">
                            Daftar Ulang Sekarang
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>
            @else
                <!-- Sudah Daftar Ulang -->
                <div>
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 border-b border-slate-700/50 pb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-1">Data Daftar Ulang Anda</h2>
                            <p class="text-slate-400 text-sm">Dikirim pada: {{ \Carbon\Carbon::parse($reregistration->tanggal_daftar_ulang)->translatedFormat('d F Y, H:i') }}</p>
                        </div>
                        <div>
                            <span class="px-4 py-2 font-bold text-sm rounded-xl uppercase tracking-wider border shadow-sm
                                {{ $reregistration->status_verifikasi == 'Terverifikasi' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 
                                  ($reregistration->status_verifikasi == 'Menunggu Verifikasi' ? 'bg-amber-500/20 text-amber-400 border-amber-500/30' : 
                                  'bg-red-500/20 text-red-400 border-red-500/30') }}">
                                Status: {{ $reregistration->status_verifikasi }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                        <div>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Lengkap</p>
                            <p class="text-lg font-bold text-white">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Keaktifan</p>
                            <p class="text-lg font-bold {{ $reregistration->status_keaktifan ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $reregistration->status_keaktifan ? 'Ya, masih aktif' : 'Tidak melanjutkan' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Kelas</p>
                            <p class="text-lg font-medium text-slate-300">{{ $reregistration->kelas ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Jurusan</p>
                            <p class="text-lg font-medium text-slate-300">{{ $reregistration->jurusan ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">Email</p>
                            <p class="text-lg font-medium text-slate-300">{{ $reregistration->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">No. HP</p>
                            <p class="text-lg font-medium text-slate-300">{{ $reregistration->no_hp ?? '-' }}</p>
                        </div>
                        @if(!$reregistration->status_keaktifan && $reregistration->alasan_tidak_melanjutkan)
                        <div class="md:col-span-2 mt-2 p-4 bg-red-900/10 border border-red-500/20 rounded-xl">
                            <p class="text-sm font-bold text-red-500/70 uppercase tracking-wider mb-1">Alasan Tidak Melanjutkan</p>
                            <p class="text-base text-red-200/80 italic">"{{ $reregistration->alasan_tidak_melanjutkan }}"</p>
                        </div>
                        @endif
                    </div>
                    
                    <div class="mt-10 p-5 bg-slate-900/50 border border-slate-700 rounded-2xl">
                        <p class="text-sm text-slate-400 flex items-center gap-2">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Data daftar ulang Anda telah tersimpan. Jika ada kesalahan data, silakan hubungi Admin atau Ketua.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Form -->
    @if(!$reregistration)
    <div x-show="formOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="formOpen" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="formOpen = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="formOpen" x-transition.scale.origin.bottom class="inline-block align-bottom bg-[#0F172A] border border-slate-700 rounded-3xl text-left overflow-hidden shadow-[0_0_40px_rgba(0,0,0,0.5)] transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                <form action="{{ route('daftar_ulang.store') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin data yang diisikan sudah benar? Data yang sudah dikirim tidak dapat diubah sendiri.');">
                    @csrf
                    <div class="px-6 py-6 border-b border-slate-700/50 bg-slate-900/30">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-white" id="modal-title">Formulir Daftar Ulang</h3>
                                <p class="text-xs text-slate-400 mt-1">Perbarui data Anda jika ada perubahan.</p>
                            </div>
                            <button type="button" @click="formOpen = false" class="text-slate-400 hover:text-white transition-colors bg-slate-800 p-2 rounded-xl">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="px-8 py-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-400 mb-2">Nama Lengkap</label>
                                <input type="text" value="{{ $user->name }}" class="w-full bg-slate-900/30 border border-slate-700/50 rounded-xl px-4 py-3 text-slate-400 cursor-not-allowed" readonly>
                                <p class="text-[10px] text-slate-500 mt-1">*Nama tidak dapat diubah</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-400 mb-2">Email</label>
                                <input type="email" name="email" value="{{ $user->email }}" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-400 mb-2">Kelas</label>
                                <input type="text" name="kelas" value="{{ $user->member->kelas ?? '' }}" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" required placeholder="Contoh: XII PPLG 2">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-400 mb-2">Jurusan</label>
                                <input type="text" value="{{ $user->member->jurusan ?? '' }}" class="w-full bg-slate-900/30 border border-slate-700/50 rounded-xl px-4 py-3 text-slate-400 cursor-not-allowed" readonly>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-slate-400 mb-2">No. HP / WhatsApp</label>
                                <input type="text" name="no_hp" value="{{ $user->member->phone ?? '' }}" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all" placeholder="Contoh: 08123456789">
                            </div>
                        </div>

                        <div class="p-6 bg-slate-900/50 border border-slate-700 rounded-2xl">
                            <label class="block text-base font-bold text-white mb-4">Apakah Anda masih aktif sebagai anggota PIK-R?</label>
                            
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border rounded-xl cursor-pointer transition-all"
                                    :class="statusAktif === '1' ? 'bg-cyan-500/10 border-cyan-500/50' : 'bg-slate-800 border-slate-700 hover:bg-slate-700/50'">
                                    <input type="radio" name="status_keaktifan" value="1" x-model="statusAktif" class="w-5 h-5 text-cyan-500 focus:ring-cyan-500 bg-slate-900 border-slate-600">
                                    <span class="ml-3 font-bold text-white">Ya, saya masih aktif</span>
                                </label>
                                
                                <label class="flex items-center p-4 border rounded-xl cursor-pointer transition-all"
                                    :class="statusAktif === '0' ? 'bg-red-500/10 border-red-500/50' : 'bg-slate-800 border-slate-700 hover:bg-slate-700/50'">
                                    <input type="radio" name="status_keaktifan" value="0" x-model="statusAktif" class="w-5 h-5 text-red-500 focus:ring-red-500 bg-slate-900 border-slate-600">
                                    <span class="ml-3 font-bold text-white">Tidak, saya tidak melanjutkan</span>
                                </label>
                            </div>

                            <div x-show="statusAktif === '0'" x-transition class="mt-4">
                                <label class="block text-sm font-bold text-slate-400 mb-2">Alasan tidak melanjutkan (Opsional)</label>
                                <textarea name="alasan_tidak_melanjutkan" rows="3" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all" placeholder="Tuliskan alasan singkat..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-8 py-5 bg-slate-900/80 border-t border-slate-700/50 flex justify-end gap-4">
                        <button type="button" @click="formOpen = false" class="px-6 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-bold text-sm shadow-sm hover:bg-slate-700 hover:text-white transition-all border border-slate-600">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-white font-bold text-sm transition-all shadow-lg"
                            :class="statusAktif === '1' ? 'bg-gradient-to-r from-cyan-500 to-emerald-500 hover:shadow-[0_0_20px_rgba(6,182,212,0.6)]' : 'bg-gradient-to-r from-red-500 to-orange-500 hover:shadow-[0_0_20px_rgba(239,68,68,0.6)]'">
                            Kirim Daftar Ulang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
