@extends('layouts.admin')

@section('title', 'Penjadwalan P2KS')
@section('header', 'Penjadwalan P2KS')

@section('content')
<div class="px-6 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10">
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Penjadwalan <span class="text-teal-400">P2KS</span></h1>
            <p class="text-slate-400 mt-2">Sistem penjadwalan acak anggota untuk piket P2KS.</p>
        </div>

        @if(session('success'))
            <div class="bg-teal-500/20 border border-teal-500 text-teal-400 px-4 py-3 rounded-lg mb-6 shadow-lg shadow-teal-500/10">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500 text-red-400 px-4 py-3 rounded-lg mb-6 shadow-lg shadow-red-500/10">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if($canEdit)
        <div class="bg-slate-800/80 backdrop-blur-md rounded-3xl p-10 mb-12 border border-slate-700 shadow-xl text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-teal-500/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex flex-col items-center justify-center">
                <h2 class="text-2xl font-black text-white mb-4">Spin Wheel P2KS</h2>
                <p class="text-slate-400 mb-8 max-w-lg mx-auto">Sistem akan memilih anggota secara acak. Anggota yang sudah terpilih tidak akan terpilih lagi dalam 2 bulan ke depan.</p>
                
                @if($eligibleUsers->isEmpty())
                    <div class="bg-slate-700/50 p-6 rounded-2xl text-slate-300">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Semua anggota sudah pernah mendapat giliran dalam 2 bulan terakhir.
                    </div>
                @else
                    <!-- Spin Wheel UI (Menyala & Rapi) -->
                    <div class="relative w-80 h-80 sm:w-[400px] sm:h-[400px] mb-12 mt-6 mx-auto">
                        <!-- Glow Effect di Belakang Roda -->
                        <div class="absolute inset-0 bg-teal-500/30 blur-[40px] rounded-full animate-pulse z-0"></div>
                        <div class="absolute inset-0 bg-emerald-400/20 blur-[60px] rounded-full animate-pulse delay-75 z-0"></div>
                        
                        <!-- Pointer/Arrow (Top Center) -->
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-4 z-30 w-8 h-12 bg-white flex items-center justify-center rounded-b-full shadow-[0_4px_15px_rgba(0,0,0,0.5)]" style="clip-path: polygon(0 0, 100% 0, 50% 100%);"></div>
                        
                        <!-- Wheel Container -->
                        <div class="w-full h-full rounded-full border-[10px] border-slate-800 shadow-[0_0_30px_rgba(20,184,166,0.6),inset_0_0_20px_rgba(0,0,0,0.8)] overflow-hidden relative z-20">
                            <canvas id="wheelCanvas" width="400" height="400" class="w-full h-full"></canvas>
                        </div>
                        
                        <!-- Center Dot -->
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-14 h-14 bg-slate-800 border-4 border-slate-700 rounded-full z-30 shadow-[0_0_15px_rgba(0,0,0,0.8)] flex items-center justify-center">
                            <div class="w-5 h-5 bg-teal-400 rounded-full shadow-[0_0_10px_rgba(45,212,191,0.8)]"></div>
                        </div>
                    </div>

                    <form action="{{ route('p2ks.spin') }}" method="POST" id="spinForm" class="mt-8 relative z-30">
                        @csrf
                        <button type="submit" id="spinBtn" class="bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-black py-4 px-12 rounded-full shadow-[0_0_25px_rgba(20,184,166,0.5)] hover:shadow-[0_0_40px_rgba(20,184,166,0.8)] hover:-translate-y-1 transition-all duration-300 inline-flex items-center gap-3 text-xl w-72 justify-center border border-teal-300/30 tracking-widest uppercase">
                            <svg class="w-6 h-6 animate-spin hidden" id="spinIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg class="w-6 h-6" id="normalIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span id="spinText">SPIN RODA</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @endif

        <!-- Riwayat Penjadwalan (Dikelompokkan Per Bulan & Per Hari) -->
        <div>
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h2 class="text-xl md:text-2xl font-black text-white flex items-center gap-2.5">
                    <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Riwayat Penjadwalan Piket
                </h2>
                
                @if(isset($stats))
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 bg-slate-800/80 border border-slate-700 text-xs font-bold text-white rounded-xl">
                        Total: <strong class="text-teal-400">{{ $stats['total'] }}</strong>
                    </span>
                    <span class="px-3 py-1 bg-amber-500/10 border border-amber-500/30 text-xs font-bold text-amber-400 rounded-xl">
                        Menunggu: <strong>{{ $stats['menunggu'] }}</strong>
                    </span>
                    <span class="px-3 py-1 bg-teal-500/10 border border-teal-500/30 text-xs font-bold text-teal-300 rounded-xl">
                        Selesai: <strong>{{ $stats['hadir'] }}</strong>
                    </span>
                    <span class="px-3 py-1 bg-red-500/10 border border-red-500/30 text-xs font-bold text-red-400 rounded-xl">
                        Tidak Hadir: <strong>{{ $stats['tidak_hadir'] }}</strong>
                    </span>
                </div>
                @endif
            </div>

            @forelse($groupedSchedules as $monthName => $days)
                <div class="mb-10 space-y-5">
                    <!-- Header Bulan -->
                    <div class="flex items-center justify-between bg-gradient-to-r from-[#121B2D] via-slate-900 to-[#121B2D] border border-teal-500/30 rounded-2xl px-6 py-4 shadow-lg shadow-teal-500/5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-teal-500/20 border border-teal-500/40 flex items-center justify-center text-teal-400 shadow-[0_0_15px_rgba(20,184,166,0.3)]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg md:text-xl font-extrabold text-white tracking-wide uppercase">{{ $monthName }}</h3>
                                <p class="text-xs text-slate-400">{{ count($days) }} Hari Terjadwal</p>
                            </div>
                        </div>
                        <span class="px-3.5 py-1.5 rounded-full bg-teal-500/10 border border-teal-500/30 text-teal-300 text-xs font-bold shadow-[0_0_10px_rgba(20,184,166,0.2)]">
                            {{ $days->flatten()->count() }} Anggota Terjadwal
                        </span>
                    </div>

                    <!-- Kelompok Hari dalam Bulan -->
                    <div class="space-y-4">
                        @foreach($days as $dayName => $daySchedules)
                            <div class="bg-slate-800/80 backdrop-blur-md rounded-2xl border border-slate-700/80 overflow-hidden shadow-xl hover:border-teal-500/40 transition-all duration-300">
                                <!-- Day Bar Header -->
                                <div class="bg-slate-900/70 px-6 py-3.5 border-b border-slate-700/60 flex flex-wrap items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <span class="w-2.5 h-2.5 rounded-full bg-teal-400 shadow-[0_0_8px_rgba(45,212,191,0.8)]"></span>
                                        <h4 class="text-sm md:text-base font-bold text-white tracking-wide">
                                            {{ $dayName }}
                                        </h4>
                                        @php
                                            $firstDate = \Carbon\Carbon::parse($daySchedules->first()->scheduled_date);
                                        @endphp
                                        @if($firstDate->isToday())
                                            <span class="text-[10px] font-black uppercase px-2.5 py-0.5 rounded-md bg-teal-500/20 text-teal-300 border border-teal-400/30 shadow-[0_0_8px_rgba(20,184,166,0.3)]">Hari Ini</span>
                                        @endif
                                    </div>
                                    <span class="text-xs font-semibold text-slate-400 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        {{ $daySchedules->count() }} Anggota Piket
                                    </span>
                                </div>

                                <!-- Table of members for this day -->
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-sm text-slate-300">
                                        <thead class="bg-slate-900/40 text-[11px] uppercase font-bold text-slate-400 border-b border-slate-700/40">
                                            <tr>
                                                <th class="px-6 py-3.5 w-16 text-center">No</th>
                                                <th class="px-6 py-3.5">Nama Anggota</th>
                                                <th class="px-6 py-3.5">Kelas / Divisi</th>
                                                <th class="px-6 py-3.5">Status Piket</th>
                                                @if($canEdit)
                                                <th class="px-6 py-3.5 text-right pr-8">Kelola Status</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-700/40">
                                            @foreach($daySchedules as $idx => $schedule)
                                            <tr class="hover:bg-slate-700/20 transition-colors">
                                                <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-teal-400 to-emerald-500 flex items-center justify-center text-white font-extrabold shadow-md shrink-0">
                                                            {{ substr($schedule->user->name ?? 'A', 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <span class="font-bold text-white block">{{ $schedule->user->name ?? '-' }}</span>
                                                            <span class="text-xs text-slate-400">{{ $schedule->user->email ?? '' }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="text-xs font-semibold text-slate-300 bg-slate-900/60 px-3 py-1.5 rounded-lg border border-slate-700/60">
                                                        {{ $schedule->user->member->kelas ?? ($schedule->user->kelas ?? ($schedule->user->member->division->name ?? 'Anggota PIK-R')) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if($schedule->status == 'menunggu')
                                                        <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-400 text-xs font-bold border border-amber-500/30 shadow-[0_0_10px_rgba(245,158,11,0.2)] inline-flex items-center gap-1.5">
                                                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            Menunggu
                                                        </span>
                                                    @elseif($schedule->status == 'hadir')
                                                        <span class="px-3 py-1 rounded-full bg-teal-500/20 text-teal-400 text-xs font-bold border border-teal-500/30 shadow-[0_0_10px_rgba(20,184,166,0.2)] inline-flex items-center gap-1.5">
                                                            <svg class="w-3.5 h-3.5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            Piket Selesai
                                                        </span>
                                                    @elseif($schedule->status == 'tidak_hadir')
                                                        <span class="px-3 py-1 rounded-full bg-red-500/20 text-red-400 text-xs font-bold border border-red-500/30 shadow-[0_0_10px_rgba(239,68,68,0.2)] inline-flex items-center gap-1.5">
                                                            <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            Tidak Hadir
                                                        </span>
                                                    @else
                                                        <span class="px-3 py-1 rounded-full bg-slate-500/20 text-slate-400 text-xs font-bold border border-slate-500/30 inline-flex items-center gap-1.5">
                                                            Batal
                                                        </span>
                                                    @endif
                                                </td>
                                                @if($canEdit)
                                                <td class="px-6 py-4 text-right pr-8">
                                                    <div class="flex items-center justify-end gap-2">
                                                        <form action="{{ route('p2ks.status', $schedule) }}" method="POST">
                                                            @csrf @method('PUT')
                                                            <input type="hidden" name="status" value="hadir">
                                                            <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-teal-500/10 text-teal-400 hover:bg-teal-500 hover:text-white transition-colors border border-teal-500/20 hover:shadow-[0_0_10px_rgba(20,184,166,0.5)]" title="Tandai Selesai Piket">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('p2ks.status', $schedule) }}" method="POST">
                                                            @csrf @method('PUT')
                                                            <input type="hidden" name="status" value="tidak_hadir">
                                                            <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition-colors border border-red-500/20 hover:shadow-[0_0_10px_rgba(239,68,68,0.5)]" title="Tandai Tidak Hadir">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('p2ks.status', $schedule) }}" method="POST">
                                                            @csrf @method('PUT')
                                                            <input type="hidden" name="status" value="batal">
                                                            <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-500/10 text-slate-400 hover:bg-slate-500 hover:text-white transition-colors border border-slate-500/20" title="Batalkan Jadwal">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                                @endif
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-slate-800/80 backdrop-blur-md rounded-3xl p-12 text-center border border-slate-700 shadow-xl">
                    <svg class="w-12 h-12 mb-3 opacity-20 text-teal-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <span class="text-slate-400 font-medium">Belum ada riwayat penjadwalan piket P2KS.</span>
                </div>
            @endforelse
        </div>
    </div>
</div>

@if($canEdit && !$eligibleUsers->isEmpty())
<!-- SweetAlert2 untuk Popup Hasil -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const users = @json($eligibleUsers->values()->toArray());
        const canvas = document.getElementById("wheelCanvas");
        if (!canvas) return;
        const ctx = canvas.getContext("2d");
        
        const numSlices = users.length;
        const arc = Math.PI * 2 / numSlices;
        
        // Palette of colors
        const colors = [
            '#0d9488', '#059669', '#0f766e', '#047857', '#115e59', '#065f46',
            '#14b8a6', '#10b981', '#34d399', '#2dd4bf'
        ];

        // Draw wheel
        function drawWheel() {
            ctx.clearRect(0, 0, 400, 400);
            for (let i = 0; i < numSlices; i++) {
                let angle = i * arc;
                ctx.beginPath();
                ctx.fillStyle = colors[i % colors.length];
                ctx.moveTo(200, 200);
                ctx.arc(200, 200, 200, angle, angle + arc, false);
                ctx.lineTo(200, 200);
                ctx.fill();
                ctx.save();
                
                // Draw text
                ctx.translate(200 + Math.cos(angle + arc / 2) * 140, 
                              200 + Math.sin(angle + arc / 2) * 140);
                ctx.rotate(angle + arc / 2);
                ctx.fillStyle = "white";
                ctx.font = "bold 14px 'Inter', sans-serif";
                ctx.textAlign = "right"; // Right align towards center
                // Truncate name if too long
                let name = users[i].name;
                if(name.length > 15) name = name.substring(0, 15) + '...';
                ctx.fillText(name, 50, 5);
                ctx.restore();
            }
        }
        
        drawWheel();

        const form = document.getElementById('spinForm');
        let isSpinning = false;

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (isSpinning) return;
            isSpinning = true;

            const btn = document.getElementById('spinBtn');
            const spinIcon = document.getElementById('spinIcon');
            const normalIcon = document.getElementById('normalIcon');
            const spinText = document.getElementById('spinText');
            
            btn.classList.add('opacity-75', 'cursor-not-allowed', 'scale-95');
            btn.classList.remove('hover:-translate-y-1', 'hover:shadow-[0_0_30px_rgba(20,184,166,0.6)]');
            spinIcon.classList.remove('hidden');
            normalIcon.classList.add('hidden');
            spinText.innerText = "SPINNING...";

            // AJAX Request
            fetch("{{ route('p2ks.spin') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify({ user_ids: users.map(u => u.id) })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Temukan index user terpilih
                    const targetIndex = users.findIndex(u => u.id === data.user.id);
                    if (targetIndex === -1) {
                        location.reload();
                        return;
                    }

                    // Logika Rotasi:
                    // Panah ada di atas (-90 deg atau 270 deg pada canvas)
                    // Slice 0 mulai dari 0 deg (kanan) ke arc.
                    // Center angle dari slice target:
                    const arcDeg = 360 / numSlices;
                    const centerAngle = (targetIndex * arcDeg) + (arcDeg / 2);
                    
                    // Untuk membawa centerAngle ke posisi atas (-90 deg), kita butuh merotasi canvas sebesar:
                    // Rotasi = 270 - centerAngle
                    // Tambahkan beberapa putaran penuh untuk efek spin (contoh 5 putaran)
                    const extraSpins = 360 * 8; // 8 putaran
                    const targetRotation = extraSpins + (270 - centerAngle);

                    canvas.style.transition = "transform 5s cubic-bezier(0.25, 0.1, 0.15, 1)";
                    canvas.style.transform = `rotate(${targetRotation}deg)`;

                    // Tunggu animasi selesai
                    setTimeout(() => {
                        Swal.fire({
                            title: 'Anggota Terpilih!',
                            text: data.user.name,
                            icon: 'success',
                            background: '#1e293b',
                            color: '#fff',
                            confirmButtonColor: '#0d9488'
                        }).then(() => {
                            location.reload();
                        });
                    }, 5000);

                } else {
                    Swal.fire('Error', data.error || 'Terjadi kesalahan.', 'error').then(()=>location.reload());
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Gagal memproses acakan.', 'error').then(()=>location.reload());
            });
        });
    });
</script>
@endif
@endsection
