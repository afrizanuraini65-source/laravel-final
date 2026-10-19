@extends('layouts.admin')

@section('title', 'Data Klien - SKAKMATT')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-emerald-400">
                Data Klien Konseling
            </h2>
            <p class="text-gray-400 text-sm mt-1">Daftar klien dan riwayat sesi konseling sebaya.</p>
        </div>
        <button class="px-4 py-2 bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 text-white rounded-xl font-semibold shadow-lg shadow-teal-500/30 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Klien Baru
        </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#0B1221] border border-gray-800 rounded-2xl p-6 relative overflow-hidden group hover:border-teal-500/50 transition-colors">
            <div class="absolute top-0 right-0 w-32 h-32 bg-teal-500/5 rounded-bl-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 bg-teal-500/20 text-teal-400 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-3xl font-bold text-white">12</h3>
                    <p class="text-gray-400 text-sm">Total Klien Aktif</p>
                </div>
            </div>
        </div>
        
        <div class="bg-[#0B1221] border border-gray-800 rounded-2xl p-6 relative overflow-hidden group hover:border-blue-500/50 transition-colors">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-bl-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 bg-blue-500/20 text-blue-400 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-3xl font-bold text-white">5</h3>
                    <p class="text-gray-400 text-sm">Sesi Minggu Ini</p>
                </div>
            </div>
        </div>

        <div class="bg-[#0B1221] border border-gray-800 rounded-2xl p-6 relative overflow-hidden group hover:border-emerald-500/50 transition-colors">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-bl-full -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 bg-emerald-500/20 text-emerald-400 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-3xl font-bold text-white">28</h3>
                    <p class="text-gray-400 text-sm">Sesi Selesai</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Client Table -->
    <div class="bg-[#0B1221] border border-gray-800 rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-gray-800 flex justify-between items-center">
            <h3 class="text-lg font-bold text-white">Daftar Klien</h3>
            <div class="relative">
                <input type="text" placeholder="Cari klien..." class="bg-gray-900 border border-gray-700 text-white text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-64 pl-10 p-2.5">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
                <thead class="text-xs text-gray-400 uppercase bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-4">Nama Klien</th>
                        <th scope="col" class="px-6 py-4">Kelas</th>
                        <th scope="col" class="px-6 py-4">Status</th>
                        <th scope="col" class="px-6 py-4">Sesi Terakhir</th>
                        <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @php
                        // Get 5 random members to act as clients
                        $clients = \App\Models\Member::with('user')->inRandomOrder()->limit(5)->get();
                        $statuses = [
                            ['label' => 'Aktif', 'color' => 'text-teal-400 bg-teal-400/10 border-teal-400/20'],
                            ['label' => 'Selesai', 'color' => 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20'],
                            ['label' => 'Menunggu', 'color' => 'text-yellow-400 bg-yellow-400/10 border-yellow-400/20']
                        ];
                    @endphp

                    @foreach($clients as $idx => $client)
                    @php
                        $status = $statuses[$idx % count($statuses)];
                        $lastSession = \Carbon\Carbon::now()->subDays(rand(1, 14))->format('d M Y');
                        // Use class property or fallback
                        $kelas = isset($client->class) ? $client->class : 'X PPLG ' . rand(1, 3);
                    @endphp
                    <tr class="hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center text-white font-bold text-xs">
                                    {{ substr($client->user->name ?? $client->name ?? 'K', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-white font-semibold">{{ $client->user->name ?? $client->name ?? 'Klien Anonim' }}</div>
                                    <div class="text-xs text-gray-500">ID: KLN-{{ str_pad($client->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $kelas }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-md border {{ $status['color'] }}">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                            {{ $status['label'] == 'Menunggu' ? '-' : $lastSession }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <button class="text-teal-400 hover:text-teal-300 font-medium text-sm transition-colors">Detail</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination Dummy -->
        <div class="p-4 border-t border-gray-800 flex justify-between items-center text-sm text-gray-400">
            <span>Menampilkan 1-5 dari 12 klien</span>
            <div class="flex gap-1">
                <button class="px-3 py-1 rounded bg-gray-800 hover:bg-gray-700 disabled:opacity-50" disabled>&laquo;</button>
                <button class="px-3 py-1 rounded bg-teal-500/20 text-teal-400 font-bold border border-teal-500/30">1</button>
                <button class="px-3 py-1 rounded bg-gray-800 hover:bg-gray-700">2</button>
                <button class="px-3 py-1 rounded bg-gray-800 hover:bg-gray-700">3</button>
                <button class="px-3 py-1 rounded bg-gray-800 hover:bg-gray-700">&raquo;</button>
            </div>
        </div>
    </div>
</div>
@endsection
