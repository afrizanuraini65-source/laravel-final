@extends('layouts.admin')

@section('title', 'Daftar Ulang (Admin) - PIK-R SKAKMATT')

@section('header', 'Pengelolaan Daftar Ulang')

@section('content')
<div class="px-6 py-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Daftar <span class="text-teal-400">Ulang</span></h1>
            <p class="text-slate-400 mt-2">Manajemen konfirmasi keaktifan anggota PIK-R SKAKMATT.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Ringkasan Card -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            <div class="bg-slate-800/80 backdrop-blur-md border border-slate-700 rounded-2xl p-5 text-center shadow-lg">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2">Total Anggota</p>
                <p class="text-3xl font-black text-white">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-indigo-900/20 backdrop-blur-md border border-indigo-500/30 rounded-2xl p-5 text-center shadow-lg">
                <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider mb-2">Sudah Daftar</p>
                <p class="text-3xl font-black text-indigo-400">{{ $stats['sudah'] }}</p>
            </div>
            <div class="bg-slate-900/50 backdrop-blur-md border border-slate-700/50 rounded-2xl p-5 text-center shadow-lg">
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-2">Belum Daftar</p>
                <p class="text-3xl font-black text-slate-400">{{ $stats['belum'] }}</p>
            </div>
            <div class="bg-amber-900/20 backdrop-blur-md border border-amber-500/30 rounded-2xl p-5 text-center shadow-lg">
                <p class="text-[10px] text-amber-500 font-bold uppercase tracking-wider mb-2">Menunggu</p>
                <p class="text-3xl font-black text-amber-400">{{ $stats['menunggu'] }}</p>
            </div>
            <div class="bg-emerald-900/20 backdrop-blur-md border border-emerald-500/30 rounded-2xl p-5 text-center shadow-lg">
                <p class="text-[10px] text-emerald-500 font-bold uppercase tracking-wider mb-2">Terverifikasi</p>
                <p class="text-3xl font-black text-emerald-400">{{ $stats['terverifikasi'] }}</p>
            </div>
            <div class="bg-red-900/20 backdrop-blur-md border border-red-500/30 rounded-2xl p-5 text-center shadow-lg">
                <p class="text-[10px] text-red-500 font-bold uppercase tracking-wider mb-2">Tdk Lanjut</p>
                <p class="text-3xl font-black text-red-400">{{ $stats['tidak'] }}</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-slate-800/80 backdrop-blur-md border border-slate-700 rounded-3xl p-6 shadow-xl mb-8">
            <form action="{{ route('feature.daftar_ulang') }}" method="GET" class="flex flex-wrap gap-4 items-center">
                <input type="text" name="search" placeholder="Cari nama anggota..." value="{{ request('search') }}" class="bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent flex-grow md:flex-grow-0 md:w-64">
                
                <input type="text" name="kelas" placeholder="Filter kelas..." value="{{ request('kelas') }}" class="bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent w-full md:w-32">
                
                <select name="status" class="bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent w-full md:w-auto">
                    <option value="">Semua Status</option>
                    <option value="Belum Daftar Ulang" {{ request('status') == 'Belum Daftar Ulang' ? 'selected' : '' }}>Belum Daftar Ulang</option>
                    <option value="Menunggu Verifikasi" {{ request('status') == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="Terverifikasi" {{ request('status') == 'Terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="Tidak Melanjutkan" {{ request('status') == 'Tidak Melanjutkan' ? 'selected' : '' }}>Tidak Melanjutkan</option>
                </select>
                
                <button type="submit" class="px-5 py-2.5 bg-slate-700 text-white rounded-xl hover:bg-slate-600 transition-colors text-sm font-semibold border border-slate-600">Filter</button>
                
                @if(request()->anyFilled(['search', 'kelas', 'status']))
                    <a href="{{ route('feature.daftar_ulang') }}" class="px-5 py-2.5 bg-red-500/20 text-red-400 rounded-xl hover:bg-red-500/30 transition-colors text-sm font-semibold border border-red-500/30">Reset</a>
                @endif
            </form>
        </div>

        <!-- Tabel Data -->
        <div class="bg-slate-800/80 backdrop-blur-md border border-slate-700 rounded-3xl shadow-xl overflow-hidden" x-data="{ viewModal: false, selectedData: null }">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900/50 border-b border-slate-700">
                            <th class="py-4 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider w-16">No</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Anggota</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider">Kelas & Jurusan</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Status</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        @forelse($users as $index => $u)
                        <tr class="hover:bg-slate-800/50 transition-colors">
                            <td class="py-4 px-6 text-sm text-slate-400">{{ $index + 1 }}</td>
                            <td class="py-4 px-6">
                                <p class="text-sm font-bold text-white">{{ $u->name }}</p>
                                <p class="text-xs text-slate-500">{{ $u->email }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-sm font-semibold text-slate-300">{{ $u->member->kelas ?? '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $u->member->jurusan ?? '-' }}</p>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if(!$u->reregistration)
                                    <span class="text-xs font-bold px-3 py-1.5 rounded-lg border shadow-sm outline-none bg-slate-800 text-slate-400 border-slate-600">
                                        Belum Daftar
                                    </span>
                                @else
                                    <span class="text-xs font-bold px-3 py-1.5 rounded-lg border shadow-sm outline-none transition-colors
                                        {{ $u->reregistration->status_verifikasi == 'Terverifikasi' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 
                                          ($u->reregistration->status_verifikasi == 'Menunggu Verifikasi' ? 'bg-amber-500/20 text-amber-400 border-amber-500/30' : 
                                          'bg-red-500/20 text-red-400 border-red-500/30') }}">
                                        {{ $u->reregistration->status_verifikasi }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center flex items-center justify-center gap-2">
                                @if($u->reregistration)
                                <button type="button" @click="viewModal = true; selectedData = {{ json_encode([
                                    'name' => $u->name,
                                    'email' => $u->reregistration->email,
                                    'no_hp' => $u->reregistration->no_hp,
                                    'kelas' => $u->reregistration->kelas,
                                    'jurusan' => $u->reregistration->jurusan,
                                    'status_keaktifan' => $u->reregistration->status_keaktifan ? 'Ya, masih aktif' : 'Tidak melanjutkan',
                                    'alasan' => $u->reregistration->alasan_tidak_melanjutkan,
                                    'tanggal' => \Carbon\Carbon::parse($u->reregistration->tanggal_daftar_ulang)->translatedFormat('d M Y H:i'),
                                ]) }}" class="px-3 py-1.5 bg-cyan-500/10 text-cyan-400 hover:bg-cyan-500/20 border border-cyan-500/20 rounded-lg text-xs font-bold transition-colors">
                                    Detail
                                </button>

                                <form action="{{ route('daftar_ulang.update_status', $u->reregistration->id) }}" method="POST" class="inline-flex">
                                    @csrf
                                    @method('PUT')
                                    <select name="status_verifikasi" onchange="this.form.submit()" class="text-xs font-bold px-2 py-1.5 rounded-lg border shadow-sm outline-none bg-slate-900 border-slate-600 text-white">
                                        <option value="Menunggu Verifikasi" {{ $u->reregistration->status_verifikasi == 'Menunggu Verifikasi' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="Terverifikasi" {{ $u->reregistration->status_verifikasi == 'Terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                                        <option value="Tidak Melanjutkan" {{ $u->reregistration->status_verifikasi == 'Tidak Melanjutkan' ? 'selected' : '' }}>Tidak Lanjut</option>
                                    </select>
                                </form>
                                @else
                                    <span class="text-xs text-slate-500 italic">Menunggu anggota</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                Tidak ada data yang sesuai filter.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Detail Modal -->
            <div x-show="viewModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="viewModal" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="viewModal = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    
                    <div x-show="viewModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-[#0F172A] border border-slate-700 rounded-3xl text-left overflow-hidden shadow-[0_0_40px_rgba(0,0,0,0.5)] transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                        <div class="px-6 py-6 border-b border-slate-700/50">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-white" id="modal-title">Detail Daftar Ulang</h3>
                                <button type="button" @click="viewModal = false" class="text-slate-400 hover:text-white transition-colors">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        </div>
                        <div class="px-6 py-6 space-y-4 text-sm text-slate-300">
                            <template x-if="selectedData">
                                <div>
                                    <div class="grid grid-cols-3 gap-2 mb-3">
                                        <span class="text-slate-500">Nama</span>
                                        <span class="col-span-2 font-bold text-white" x-text="selectedData.name"></span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 mb-3">
                                        <span class="text-slate-500">Kelas</span>
                                        <span class="col-span-2 font-bold text-white" x-text="selectedData.kelas"></span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 mb-3">
                                        <span class="text-slate-500">Jurusan</span>
                                        <span class="col-span-2 text-white" x-text="selectedData.jurusan"></span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 mb-3">
                                        <span class="text-slate-500">No HP</span>
                                        <span class="col-span-2 text-white" x-text="selectedData.no_hp || '-'"></span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 mb-3">
                                        <span class="text-slate-500">Email</span>
                                        <span class="col-span-2 text-white" x-text="selectedData.email"></span>
                                    </div>
                                    <hr class="border-slate-700 my-4">
                                    <div class="grid grid-cols-3 gap-2 mb-3">
                                        <span class="text-slate-500">Keaktifan</span>
                                        <span class="col-span-2 font-bold text-cyan-400" x-text="selectedData.status_keaktifan"></span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 mb-3" x-show="selectedData.alasan">
                                        <span class="text-slate-500">Alasan</span>
                                        <span class="col-span-2 text-red-400 italic" x-text="selectedData.alasan"></span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 mb-1">
                                        <span class="text-slate-500">Waktu Kirim</span>
                                        <span class="col-span-2 text-slate-400 text-xs" x-text="selectedData.tanggal"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="px-6 py-4 bg-slate-900/50 border-t border-slate-700/50 flex justify-end gap-3">
                            <button type="button" @click="viewModal = false" class="px-5 py-2.5 rounded-xl bg-slate-700 text-white font-bold text-sm shadow-sm hover:bg-slate-600 transition-all border border-slate-600">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
