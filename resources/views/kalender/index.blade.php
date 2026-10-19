@extends('layouts.admin')

@section('title', 'Kalender - PIK-R SKAKMATT')

@section('header', 'Kalender Kegiatan')

@section('content')
@php
    $startOfMonth = $currentDate->copy()->startOfMonth();
    $endOfMonth = $currentDate->copy()->endOfMonth();
    $startDayOfWeek = $startOfMonth->dayOfWeek; // 0 (Sun) to 6 (Sat)
    $daysInMonth = $currentDate->daysInMonth;
    $prevMonth = $currentDate->copy()->subMonth();
    $nextMonth = $currentDate->copy()->addMonth();

    $categoryColors = [
        'Kegiatan' => 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]',
        'Rapat' => 'bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.6)]',
        'Deadline' => 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.6)]',
        'Event' => 'bg-purple-500 shadow-[0_0_8px_rgba(168,85,247,0.6)]',
        'Forum Rutin' => 'bg-teal-400 shadow-[0_0_8px_rgba(45,212,191,0.6)]',
        'Program Kerja' => 'bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.6)]',
    ];
@endphp
<div class="px-6 py-8">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header & Nav -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Kalender <span class="text-teal-400">Kegiatan</span></h1>
                <p class="text-slate-400 mt-2">Jadwal program kerja, rapat, dan event PIK-R.</p>
            </div>
            
            <div class="flex gap-2">
                @hasanyrole('ADMIN|KETUA|KEGIATAN|PEMBINA')
                <button onclick="openModal('addModal')" class="bg-teal-500 hover:bg-teal-600 text-slate-900 px-4 py-2 rounded-lg font-bold flex items-center gap-2 mr-4 transition shadow-[0_0_15px_rgba(20,184,166,0.2)]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Agenda
                </button>
                @endhasanyrole

                <a href="{{ route('feature.kalender', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}" class="bg-slate-800 border border-slate-700 text-white p-2 rounded-lg hover:bg-slate-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div class="bg-slate-800 border border-slate-700 text-white px-6 py-2 rounded-lg font-bold flex items-center min-w-[150px] justify-center">
                    {{ $currentDate->translatedFormat('F Y') }}
                </div>
                <a href="{{ route('feature.kalender', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" class="bg-slate-800 border border-slate-700 text-white p-2 rounded-lg hover:bg-slate-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-4 bg-emerald-500/20 border border-emerald-500 text-emerald-400 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif
        
        @if ($errors->any())
        <div class="mb-4 bg-rose-500/20 border border-rose-500 text-rose-400 px-4 py-3 rounded relative" role="alert">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-slate-900/60 rounded-3xl p-6 border border-slate-700/50 shadow-xl backdrop-blur-sm">
            <!-- Days of Week -->
            <div class="grid grid-cols-7 gap-2 md:gap-4 mb-4 text-center">
                <div class="text-rose-400 font-bold text-sm">Min</div>
                <div class="text-slate-400 font-semibold text-sm">Sen</div>
                <div class="text-slate-400 font-semibold text-sm">Sel</div>
                <div class="text-slate-400 font-semibold text-sm">Rab</div>
                <div class="text-slate-400 font-semibold text-sm">Kam</div>
                <div class="text-slate-400 font-semibold text-sm">Jum</div>
                <div class="text-slate-400 font-semibold text-sm">Sab</div>
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-2 md:gap-4">
                <!-- Padding previous month -->
                @for ($i = 0; $i < $startDayOfWeek; $i++)
                    <div class="aspect-square md:h-32 bg-slate-800/10 rounded-xl md:rounded-2xl border border-slate-800/30"></div>
                @endfor

                <!-- Current Month Days -->
                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $dateStr = clone $currentDate;
                        $dateStr->day($day);
                        $formattedDate = $dateStr->format('Y-m-d');
                        $isToday = $dateStr->isToday();
                        
                        $dayAgendas = $agendas->filter(function($a) use ($formattedDate) {
                            return $a->date->format('Y-m-d') == $formattedDate;
                        });
                    @endphp

                    <div onclick="showDayDetails('{{ $formattedDate }}')" class="aspect-square md:h-32 {{ $isToday ? 'bg-teal-900/30 border-teal-500 shadow-[0_0_15px_rgba(20,184,166,0.1)]' : 'bg-slate-800/40 border-slate-700/50' }} hover:bg-slate-700/80 transition-colors border rounded-xl md:rounded-2xl p-2 cursor-pointer flex flex-col relative group">
                        
                        <div class="flex justify-between items-start">
                            <span class="text-sm md:text-lg font-bold {{ $isToday ? 'text-teal-400' : 'text-slate-300' }}">{{ $day }}</span>
                            @if($dayAgendas->count() > 0)
                                <span class="text-[10px] font-bold bg-slate-700 text-slate-300 px-1.5 py-0.5 rounded hidden md:block">{{ $dayAgendas->count() }} agenda</span>
                            @endif
                        </div>

                        <!-- Dots for mobile -->
                        <div class="flex flex-wrap gap-1 mt-auto md:hidden justify-center pb-1">
                            @foreach($dayAgendas->take(3) as $agenda)
                                <div class="w-1.5 h-1.5 rounded-full {{ $categoryColors[$agenda->category] ?? 'bg-slate-400' }}"></div>
                            @endforeach
                            @if($dayAgendas->count() > 3)
                                <div class="w-1.5 h-1.5 rounded-full bg-slate-500"></div>
                            @endif
                        </div>

                        <!-- Labels for desktop -->
                        <div class="hidden md:flex flex-col gap-1 mt-2 overflow-hidden">
                            @foreach($dayAgendas->take(3) as $agenda)
                                <div class="text-[10px] truncate px-1.5 py-0.5 rounded border border-white/5 font-medium flex items-center gap-1 bg-slate-800/80 text-slate-200">
                                    <div class="w-1.5 h-1.5 rounded-full {{ $categoryColors[$agenda->category] ?? 'bg-slate-400' }} shrink-0"></div>
                                    <span class="truncate">{{ $agenda->title }}</span>
                                </div>
                            @endforeach
                            @if($dayAgendas->count() > 3)
                                <div class="text-[10px] text-slate-400 px-1 font-medium">+{{ $dayAgendas->count() - 3 }} lainnya</div>
                            @endif
                        </div>
                    </div>

                    <!-- Hidden container for day details (used by JS modal) -->
                    <div id="day-data-{{ $formattedDate }}" class="hidden">
                        <div class="day-title text-xl font-bold text-white mb-4">{{ $dateStr->translatedFormat('l, d F Y') }}</div>
                        <div class="space-y-3">
                            @forelse($dayAgendas as $agenda)
                                <div class="bg-slate-800 p-4 rounded-xl border border-slate-700 flex flex-col sm:flex-row justify-between gap-4">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <div class="w-2 h-2 rounded-full {{ $categoryColors[$agenda->category] ?? 'bg-slate-400' }}"></div>
                                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ $agenda->category }}</span>
                                            <span class="text-xs px-2 py-0.5 rounded-full bg-slate-900 border border-slate-700 text-slate-300">{{ $agenda->status }}</span>
                                        </div>
                                        <h4 class="text-lg font-bold text-white">{{ $agenda->title }}</h4>
                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-sm text-slate-400">
                                            @if($agenda->start_time)
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    {{ substr($agenda->start_time, 0, 5) }} {{ $agenda->end_time ? '- ' . substr($agenda->end_time, 0, 5) : '' }}
                                                </div>
                                            @endif
                                            @if($agenda->location)
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                    {{ $agenda->location }}
                                                </div>
                                            @endif
                                        </div>
                                        @if($agenda->description)
                                            <p class="mt-2 text-sm text-slate-300">{{ $agenda->description }}</p>
                                        @endif
                                    </div>
                                    @hasanyrole('ADMIN|KETUA|KEGIATAN|PEMBINA')
                                    <div class="flex gap-2 self-start">
                                        <button onclick="editAgenda({{ $agenda->id }}, {{ json_encode($agenda) }})" class="p-2 bg-blue-500/10 text-blue-400 hover:bg-blue-500 hover:text-white rounded-lg transition border border-blue-500/20">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <button onclick="deleteAgenda({{ $agenda->id }})" class="p-2 bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white rounded-lg transition border border-rose-500/20">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                    @endhasanyrole
                                </div>
                            @empty
                                <div class="text-center py-8 text-slate-400">Tidak ada agenda pada tanggal ini.</div>
                            @endforelse
                        </div>
                    </div>
                @endfor
            </div>

            <!-- Legend -->
            <div class="mt-6 flex flex-wrap gap-4 pt-4 border-t border-slate-700/50">
                @foreach($categoryColors as $name => $colorClass)
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-300">
                        <div class="w-2.5 h-2.5 rounded-full {{ $colorClass }}"></div>
                        {{ $name }}
                    </div>
                @endforeach
            </div>
            
            <!-- Agenda Mendatang -->
            <div class="mt-10 pt-8 border-t border-slate-700/50">
                <h4 class="text-white font-bold text-xl mb-6">Agenda Mendatang</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($upcomingAgendas as $agenda)
                        <div class="flex flex-col sm:flex-row gap-4 bg-slate-800/50 p-5 rounded-2xl border border-slate-700 hover:border-slate-500 transition-colors">
                            <div class="w-16 h-16 rounded-xl bg-slate-900 border border-slate-700 flex flex-col items-center justify-center shrink-0 shadow-inner">
                                <span class="text-xs font-bold text-slate-400 uppercase leading-none">{{ $agenda->date->translatedFormat('M') }}</span>
                                <span class="text-2xl font-black text-white leading-none mt-1">{{ $agenda->date->format('d') }}</span>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-1.5 h-1.5 rounded-full {{ $categoryColors[$agenda->category] ?? 'bg-slate-400' }}"></div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $agenda->category }}</span>
                                </div>
                                <h5 class="text-white font-bold text-base leading-tight mb-2">{{ $agenda->title }}</h5>
                                <div class="flex flex-wrap gap-x-3 gap-y-1 text-xs text-slate-400 font-medium">
                                    @if($agenda->start_time)
                                        <span>⏰ {{ substr($agenda->start_time, 0, 5) }}</span>
                                    @endif
                                    @if($agenda->location)
                                        <span class="truncate max-w-[150px]">📍 {{ $agenda->location }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-6 text-slate-400 bg-slate-800/30 rounded-2xl border border-slate-700 border-dashed">
                            Belum ada agenda mendatang.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- Modal Day Details -->
<div id="dayModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal('dayModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl bg-slate-900 rounded-3xl border border-slate-700 shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-slate-800 flex justify-between items-center bg-slate-900/50">
            <h3 class="text-xl font-bold text-white">Detail Tanggal</h3>
            <button onclick="closeModal('dayModal')" class="text-slate-400 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto" id="dayModalContent">
            <!-- Content injected via JS -->
        </div>
    </div>
</div>

@hasanyrole('ADMIN|KETUA|KEGIATAN|PEMBINA')
<!-- Modal Form (Add/Edit) -->
<div id="addModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal('addModal')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-xl bg-slate-900 rounded-3xl border border-slate-700 shadow-2xl overflow-hidden max-h-[95vh] flex flex-col">
        <div class="p-6 border-b border-slate-800 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white" id="modalTitle">Tambah Agenda Baru</h3>
            <button onclick="closeModal('addModal')" class="text-slate-400 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="agendaForm" action="{{ route('kalender.store') }}" method="POST" class="overflow-y-auto">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Nama Agenda <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" id="formTitle" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Tanggal <span class="text-rose-500">*</span></label>
                        <input type="date" name="date" id="formDate" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Kategori <span class="text-rose-500">*</span></label>
                        <select name="category" id="formCategory" required class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                            <option value="Kegiatan">Kegiatan</option>
                            <option value="Rapat">Rapat</option>
                            <option value="Deadline">Deadline</option>
                            <option value="Event">Event</option>
                            <option value="Forum Rutin">Forum Rutin</option>
                            <option value="Program Kerja">Program Kerja</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Jam Mulai</label>
                        <input type="time" name="start_time" id="formStartTime" class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Jam Selesai</label>
                        <input type="time" name="end_time" id="formEndTime" class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Lokasi</label>
                    <input type="text" name="location" id="formLocation" class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Divisi Penanggung Jawab</label>
                    <select name="division_id" id="formDivision" class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                        <option value="">Pilih Divisi (Opsional)</option>
                        @foreach($divisions as $div)
                            <option value="{{ $div->id }}">{{ $div->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div id="statusContainer" class="hidden">
                    <label class="block text-sm font-medium text-slate-300 mb-1">Status</label>
                    <select name="status" id="formStatus" class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500">
                        <option value="Terjadwal">Terjadwal</option>
                        <option value="Berlangsung">Berlangsung</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Deskripsi Tambahan</label>
                    <textarea name="description" id="formDescription" rows="3" class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500"></textarea>
                </div>
            </div>
            
            <div class="p-6 border-t border-slate-800 bg-slate-900/50 flex justify-end gap-3">
                <button type="button" onclick="closeModal('addModal')" class="px-5 py-2.5 rounded-xl text-slate-300 font-bold hover:bg-slate-800 transition">Batal</button>
                <button type="submit" class="bg-teal-500 hover:bg-teal-600 text-slate-900 px-6 py-2.5 rounded-xl font-bold transition shadow-lg">Simpan Agenda</button>
            </div>
        </form>
    </div>
</div>

<!-- Form Hapus -->
<form id="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

@endhasanyrole

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        if(id === 'addModal') {
            document.getElementById('agendaForm').reset();
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('agendaForm').action = "{{ route('kalender.store') }}";
            document.getElementById('modalTitle').innerText = 'Tambah Agenda Baru';
            document.getElementById('statusContainer').classList.add('hidden');
        }
    }

    function showDayDetails(date) {
        let content = document.getElementById('day-data-' + date);
        if(content) {
            document.getElementById('dayModalContent').innerHTML = content.innerHTML;
            openModal('dayModal');
        }
    }

    @hasanyrole('ADMIN|KETUA|KEGIATAN|PEMBINA')
    function editAgenda(id, data) {
        closeModal('dayModal'); // Close details if open
        openModal('addModal');
        
        document.getElementById('modalTitle').innerText = 'Edit Agenda';
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('agendaForm').action = "/feature/Kalender/" + id;
        
        document.getElementById('formTitle').value = data.title;
        // Format date to YYYY-MM-DD
        let d = new Date(data.date);
        let month = '' + (d.getMonth() + 1);
        let day = '' + d.getDate();
        let year = d.getFullYear();
        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;
        
        document.getElementById('formDate').value = [year, month, day].join('-');
        
        document.getElementById('formCategory').value = data.category;
        document.getElementById('formStartTime').value = data.start_time ? data.start_time.substring(0, 5) : '';
        document.getElementById('formEndTime').value = data.end_time ? data.end_time.substring(0, 5) : '';
        document.getElementById('formLocation').value = data.location || '';
        document.getElementById('formDivision').value = data.division_id || '';
        document.getElementById('formDescription').value = data.description || '';
        
        document.getElementById('statusContainer').classList.remove('hidden');
        document.getElementById('formStatus').value = data.status || 'Terjadwal';
    }

    function deleteAgenda(id) {
        if(confirm('Apakah Anda yakin ingin menghapus agenda ini?')) {
            let form = document.getElementById('deleteForm');
            form.action = "/feature/Kalender/" + id;
            form.submit();
        }
    }
    @endhasanyrole
</script>
@endsection
