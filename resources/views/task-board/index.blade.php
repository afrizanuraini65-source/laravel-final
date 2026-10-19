@extends('layouts.admin')

@section('title', 'Task Board - PIK-R SKAKMATT')

@section('header', 'Task Board Kepengurusan')

@section('content')
<div class="px-6 py-8" x-data="{ 
    createModal: false, 
    editModal: false,
    updateModal: false,
    historyModal: false,
    selectedTask: null,
    editFormAction: '',
    updateFormAction: '',
    historyUpdates: []
}">
    <div class="max-w-full mx-auto">
        
        <!-- Header -->
        <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Task <span class="text-teal-400">Board</span></h1>
                <p class="text-slate-400 mt-2">Pantau dan kelola tugas setiap divisi kepengurusan.</p>
            </div>
            @hasanyrole('ADMIN|KETUA|WAKIL_KETUA|SEKRETARIS|PEMBINA')
            <button @click="createModal = true" class="px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-white font-bold text-sm shadow-[0_0_15px_rgba(6,182,212,0.4)] hover:shadow-[0_0_25px_rgba(6,182,212,0.6)] transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Tugas Baru
            </button>
            @endhasanyrole
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl font-medium">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl font-medium">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Ringkasan Card (Interaktif Filter) -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <a href="{{ route('feature.task_board', request()->except('status')) }}" 
               class="block bg-slate-800/80 backdrop-blur-md border rounded-2xl p-5 text-center shadow-lg transition-all duration-300 hover:scale-[1.03] hover:border-slate-500 {{ !request('status') ? 'border-cyan-400/80 ring-2 ring-cyan-400/40 bg-slate-800 shadow-[0_0_20px_rgba(6,182,212,0.2)]' : 'border-slate-700' }}">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2">Total Tugas</p>
                <p class="text-3xl font-black text-white">{{ $stats['total'] }}</p>
                <span class="text-[10px] {{ !request('status') ? 'text-cyan-400 font-bold' : 'text-slate-500' }} mt-1 block">Tampilkan Semua</span>
            </a>

            <a href="{{ route('feature.task_board', array_merge(request()->query(), ['status' => 'Belum Mulai'])) }}" 
               class="block bg-slate-900/50 backdrop-blur-md border rounded-2xl p-5 text-center shadow-lg transition-all duration-300 hover:scale-[1.03] hover:border-slate-500 {{ request('status') === 'Belum Mulai' ? 'border-slate-400 ring-2 ring-slate-400/50 bg-slate-800/90 shadow-[0_0_20px_rgba(148,163,184,0.2)]' : 'border-slate-700/50' }}">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2">Belum Mulai</p>
                <p class="text-3xl font-black text-slate-300">{{ $stats['belum_mulai'] }}</p>
                <span class="text-[10px] {{ request('status') === 'Belum Mulai' ? 'text-slate-300 font-bold' : 'text-slate-500' }} mt-1 block">Filter Status</span>
            </a>

            <a href="{{ route('feature.task_board', array_merge(request()->query(), ['status' => 'Sedang Dikerjakan'])) }}" 
               class="block bg-cyan-900/20 backdrop-blur-md border rounded-2xl p-5 text-center shadow-lg transition-all duration-300 hover:scale-[1.03] hover:border-cyan-400 {{ request('status') === 'Sedang Dikerjakan' ? 'border-cyan-400 ring-2 ring-cyan-400/50 bg-cyan-950/40 shadow-[0_0_20px_rgba(6,182,212,0.3)]' : 'border-cyan-500/30' }}">
                <p class="text-[10px] text-cyan-400 font-bold uppercase tracking-wider mb-2">Sedang Dikerjakan</p>
                <p class="text-3xl font-black text-cyan-400">{{ $stats['sedang_dikerjakan'] }}</p>
                <span class="text-[10px] {{ request('status') === 'Sedang Dikerjakan' ? 'text-cyan-300 font-bold' : 'text-cyan-500/70' }} mt-1 block">Filter Status</span>
            </a>

            <a href="{{ route('feature.task_board', array_merge(request()->query(), ['status' => 'Perlu Revisi'])) }}" 
               class="block bg-amber-900/20 backdrop-blur-md border rounded-2xl p-5 text-center shadow-lg transition-all duration-300 hover:scale-[1.03] hover:border-amber-400 {{ request('status') === 'Perlu Revisi' ? 'border-amber-400 ring-2 ring-amber-400/50 bg-amber-950/40 shadow-[0_0_20px_rgba(245,158,11,0.3)]' : 'border-amber-500/30' }}">
                <p class="text-[10px] text-amber-400 font-bold uppercase tracking-wider mb-2">Perlu Revisi</p>
                <p class="text-3xl font-black text-amber-400">{{ $stats['perlu_revisi'] }}</p>
                <span class="text-[10px] {{ request('status') === 'Perlu Revisi' ? 'text-amber-300 font-bold' : 'text-amber-500/70' }} mt-1 block">Filter Status</span>
            </a>

            <a href="{{ route('feature.task_board', array_merge(request()->query(), ['status' => 'Selesai'])) }}" 
               class="block bg-emerald-900/20 backdrop-blur-md border rounded-2xl p-5 text-center shadow-lg transition-all duration-300 hover:scale-[1.03] hover:border-emerald-400 {{ request('status') === 'Selesai' ? 'border-emerald-400 ring-2 ring-emerald-400/50 bg-emerald-950/40 shadow-[0_0_20px_rgba(16,185,129,0.3)]' : 'border-emerald-500/30' }}">
                <p class="text-[10px] text-emerald-400 font-bold uppercase tracking-wider mb-2">Selesai</p>
                <p class="text-3xl font-black text-emerald-400">{{ $stats['selesai'] }}</p>
                <span class="text-[10px] {{ request('status') === 'Selesai' ? 'text-emerald-300 font-bold' : 'text-emerald-500/70' }} mt-1 block">Filter Status</span>
            </a>
        </div>

        @hasanyrole('ADMIN|KETUA|WAKIL_KETUA|SEKRETARIS|PEMBINA')
        <div class="bg-slate-800/80 backdrop-blur-md border border-slate-700 rounded-2xl p-5 shadow-lg mb-8 flex flex-wrap gap-4 items-center">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-2">Progress Divisi:</span>
            @foreach($divisionProgress as $divName => $progress)
            <div class="flex items-center gap-2 bg-slate-900/50 px-3 py-1.5 rounded-lg border border-slate-700/50">
                <span class="text-xs font-bold text-white">{{ $divName }}</span>
                <span class="text-xs font-bold {{ $progress == 100 ? 'text-emerald-400' : ($progress > 50 ? 'text-cyan-400' : 'text-amber-400') }}">{{ $progress }}%</span>
            </div>
            @endforeach
        </div>
        @endhasanyrole

        <!-- Filters -->
        <div class="bg-slate-800/80 backdrop-blur-md border border-slate-700 rounded-3xl p-5 shadow-xl mb-8">
            <form action="{{ route('feature.task_board') }}" method="GET" class="flex flex-wrap gap-3 items-center">
                <input type="text" name="search" placeholder="Cari tugas..." value="{{ request('search') }}" class="bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent flex-grow md:flex-grow-0 md:w-64">
                
                @hasanyrole('ADMIN|KETUA|WAKIL_KETUA|SEKRETARIS|PEMBINA')
                <select name="division_id" class="bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    <option value="">Semua Divisi</option>
                    @foreach($allDivisions as $div)
                        <option value="{{ $div->id }}" {{ request('division_id') == $div->id ? 'selected' : '' }}>{{ $div->name }}</option>
                    @endforeach
                </select>
                @endhasanyrole

                <select name="priority" class="bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-2 text-sm text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    <option value="">Semua Prioritas</option>
                    <option value="Rendah" {{ request('priority') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                    <option value="Sedang" {{ request('priority') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="Tinggi" {{ request('priority') == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                </select>

                <button type="submit" class="px-5 py-2 bg-slate-700 text-white rounded-xl hover:bg-slate-600 transition-colors text-sm font-semibold border border-slate-600">Filter</button>
                @if(request()->anyFilled(['search', 'division_id', 'priority', 'status']))
                    <a href="{{ route('feature.task_board') }}" class="px-4 py-2 bg-red-500/20 text-red-400 rounded-xl hover:bg-red-500/30 transition-colors text-sm font-semibold border border-red-500/30">Reset</a>
                @endif
            </form>
        </div>

        <!-- Kanban Board -->
        <div class="flex overflow-x-auto pb-4 gap-6 min-h-[60vh] snap-x">
            
            @php
                $kanbanColumns = [
                    'Belum Mulai' => ['color' => 'slate', 'border' => 'border-slate-500/30'],
                    'Sedang Dikerjakan' => ['color' => 'cyan', 'border' => 'border-cyan-500/30'],
                    'Perlu Revisi' => ['color' => 'amber', 'border' => 'border-amber-500/30'],
                    'Selesai' => ['color' => 'emerald', 'border' => 'border-emerald-500/30'],
                ];
            @endphp

            @foreach($kanbanColumns as $statusName => $style)
            <div class="flex-shrink-0 w-80 flex flex-col snap-center">
                <div class="flex items-center justify-between mb-4 px-2">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">{{ $statusName }}</h3>
                    <span class="bg-{{$style['color']}}-900/50 text-{{$style['color']}}-400 text-xs font-bold px-2.5 py-0.5 rounded-full border {{ $style['border'] }}">
                        {{ $columns[$statusName]->count() }}
                    </span>
                </div>
                
                <div class="bg-slate-900/30 border border-slate-700/50 rounded-3xl p-3 flex-1 overflow-y-auto space-y-4">
                    @forelse($columns[$statusName] as $task)
                    <div class="bg-slate-800/90 border border-slate-700 shadow-lg rounded-2xl p-5 hover:border-cyan-500/30 transition-all group">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-[10px] font-bold px-2 py-1 rounded-md uppercase tracking-wider
                                {{ $task->priority == 'Tinggi' ? 'bg-red-500/20 text-red-400' : ($task->priority == 'Sedang' ? 'bg-amber-500/20 text-amber-400' : 'bg-slate-700/80 text-gray-300') }}">
                                {{ $task->priority }}
                            </span>
                            
                            @if(auth()->user()->hasRole(['ADMIN', 'KETUA', 'WAKIL_KETUA', 'SEKRETARIS', 'PEMBINA']) || auth()->id() == $task->assigned_to_user_id)
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button" @click='
                                    updateFormAction = "{{ route('task_board.update_progress', $task->id) }}";
                                    selectedTask = @json($task);
                                    updateModal = true;
                                ' class="p-1.5 text-cyan-400 hover:bg-cyan-500/20 rounded-lg transition-colors" title="Update Progress">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </button>
                                
                                @hasanyrole('ADMIN|KETUA|WAKIL_KETUA|SEKRETARIS|PEMBINA')
                                <button type="button" @click='
                                    editFormAction = "{{ route('task_board.update', $task->id) }}";
                                    selectedTask = @json($task);
                                    editModal = true;
                                ' class="p-1.5 text-amber-400 hover:bg-amber-500/20 rounded-lg transition-colors" title="Edit Tugas">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                
                                <form action="{{ route('task_board.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-400 hover:bg-red-500/20 rounded-lg transition-colors" title="Hapus Tugas">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                                @endhasanyrole
                            </div>
                            @endif
                        </div>
                        
                        <h4 class="text-sm font-bold text-white mb-2 leading-snug">{{ $task->title }}</h4>
                        
                        <div class="text-[11px] text-slate-400 mb-4 space-y-1">
                            <p class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg> {{ $task->division->name ?? 'Semua Divisi' }}</p>
                            <p class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> PIC: <span class="text-slate-300">{{ $task->assignee->name ?? '-' }}</span></p>
                            <p class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 {{ \Carbon\Carbon::parse($task->deadline)->isPast() && $task->status != 'Selesai' ? 'text-red-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> <span class="{{ \Carbon\Carbon::parse($task->deadline)->isPast() && $task->status != 'Selesai' ? 'text-red-400 font-semibold' : '' }}">{{ \Carbon\Carbon::parse($task->deadline)->translatedFormat('d M Y') }}</span></p>
                        </div>
                        
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Progress</span>
                                <span class="text-xs font-bold text-white">{{ $task->progress }}%</span>
                            </div>
                            <div class="w-full bg-slate-900 rounded-full h-1.5 overflow-hidden">
                                <div class="h-1.5 rounded-full {{ $task->progress == 100 ? 'bg-emerald-500' : 'bg-cyan-500' }}" style="width: {{ $task->progress }}%"></div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-3 border-t border-slate-700/50">
                            <span class="text-[10px] text-slate-500 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $task->updated_at->diffForHumans() }}
                            </span>
                            
                            <button type="button" @click='
                                historyUpdates = {!! json_encode($task->updates->map(function($u) { return ["user" => $u->user->name, "progress" => $u->progress, "status" => $u->status, "catatan" => $u->catatan, "kendala" => $u->kendala, "date" => $u->created_at->translatedFormat("d M Y H:i")]; })) !!};
                                selectedTask = {!! json_encode(["title" => $task->title]) !!};
                                historyModal = true;
                            ' class="text-[10px] font-bold text-slate-400 hover:text-cyan-400 transition-colors uppercase tracking-wider flex items-center gap-1">
                                Riwayat
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center h-24 border-2 border-dashed border-slate-700/50 rounded-2xl">
                        <span class="text-xs text-slate-500 font-medium">Kosong</span>
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>

    <!-- Buat Tugas Modal -->
    @hasanyrole('ADMIN|KETUA|WAKIL_KETUA|SEKRETARIS|PEMBINA')
    <div x-show="createModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="createModal" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="createModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="createModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-[#0F172A] border border-slate-700 rounded-3xl text-left overflow-hidden shadow-[0_0_40px_rgba(0,0,0,0.5)] transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                <form action="{{ route('task_board.store') }}" method="POST">
                    @csrf
                    <div class="px-6 py-6 border-b border-slate-700/50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white">Buat Tugas Baru</h3>
                            <button type="button" @click="createModal = false" class="text-slate-400 hover:text-white transition-colors"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                        </div>
                    </div>
                    <div class="px-8 py-6 space-y-5 h-[60vh] overflow-y-auto custom-scrollbar">
                        <div>
                            <label class="block text-sm font-bold text-slate-400 mb-2">Nama Tugas</label>
                            <input type="text" name="title" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-400 mb-2">Deskripsi</label>
                            <textarea name="description" rows="3" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-400 mb-2">Divisi</label>
                                <select name="division_id" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                    <option value="">-- Pilih Divisi --</option>
                                    @foreach($allDivisions as $div)
                                        <option value="{{ $div->id }}">{{ $div->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-400 mb-2">Koordinator / PIC</label>
                                <select name="assigned_to_user_id" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                    <option value="">-- Pilih PIC --</option>
                                    @foreach($potentialAssignees as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->member->kelas ?? '-' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-400 mb-2">Deadline</label>
                                <input type="date" name="deadline" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-400 mb-2">Prioritas</label>
                                <select name="priority" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                    <option value="Sedang">Sedang</option>
                                    <option value="Rendah">Rendah</option>
                                    <option value="Tinggi">Tinggi</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-400 mb-2">Status Awal</label>
                                <select name="status" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                    <option value="Belum Mulai">Belum Mulai</option>
                                    <option value="Sedang Dikerjakan">Sedang Dikerjakan</option>
                                    <option value="Perlu Revisi">Perlu Revisi</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-400 mb-2">Progress Awal (%)</label>
                                <input type="number" name="progress" value="0" min="0" max="100" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                    <div class="px-8 py-5 bg-slate-900/80 border-t border-slate-700/50 flex justify-end gap-3">
                        <button type="button" @click="createModal = false" class="px-5 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-bold text-sm shadow-sm hover:bg-slate-700 border border-slate-600">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-white font-bold text-sm shadow-lg">Simpan Tugas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Tugas Modal -->
    <div x-show="editModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="editModal" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="editModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="editModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-[#0F172A] border border-slate-700 rounded-3xl text-left overflow-hidden shadow-[0_0_40px_rgba(0,0,0,0.5)] transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                <form :action="editFormAction" method="POST" x-show="selectedTask">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-6 border-b border-slate-700/50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white">Edit Tugas</h3>
                            <button type="button" @click="editModal = false" class="text-slate-400 hover:text-white transition-colors"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                        </div>
                    </div>
                    <div class="px-8 py-6 space-y-5 h-[60vh] overflow-y-auto custom-scrollbar">
                        <div>
                            <label class="block text-sm font-bold text-slate-400 mb-2">Nama Tugas</label>
                            <input type="text" name="title" :value="selectedTask?.title" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-400 mb-2">Deskripsi</label>
                            <textarea name="description" rows="3" x-text="selectedTask?.description" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-400 mb-2">Divisi</label>
                                <select name="division_id" :value="selectedTask?.division_id" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                    @foreach($allDivisions as $div)
                                        <option value="{{ $div->id }}">{{ $div->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-400 mb-2">Koordinator / PIC</label>
                                <select name="assigned_to_user_id" :value="selectedTask?.assigned_to_user_id" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                    @foreach($potentialAssignees as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-400 mb-2">Deadline</label>
                                <input type="date" name="deadline" :value="selectedTask ? selectedTask.deadline.substring(0,10) : ''" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-400 mb-2">Prioritas</label>
                                <select name="priority" :value="selectedTask?.priority" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                    <option value="Sedang">Sedang</option>
                                    <option value="Rendah">Rendah</option>
                                    <option value="Tinggi">Tinggi</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="px-8 py-5 bg-slate-900/80 border-t border-slate-700/50 flex justify-end gap-3">
                        <button type="button" @click="editModal = false" class="px-5 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-bold text-sm shadow-sm hover:bg-slate-700 border border-slate-600">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold text-sm shadow-lg">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endhasanyrole

    <!-- Update Progress Modal -->
    <div x-show="updateModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="updateModal" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="updateModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="updateModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-[#0F172A] border border-slate-700 rounded-3xl text-left overflow-hidden shadow-[0_0_40px_rgba(0,0,0,0.5)] transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form :action="updateFormAction" method="POST" x-show="selectedTask">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-6 border-b border-slate-700/50 bg-slate-900/30">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-white">Update Perkembangan</h3>
                                <p class="text-xs text-slate-400 mt-1" x-text="selectedTask?.title"></p>
                            </div>
                            <button type="button" @click="updateModal = false" class="text-slate-400 hover:text-white transition-colors bg-slate-800 p-2 rounded-xl"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                        </div>
                    </div>
                    <div class="px-8 py-6 space-y-5">
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-400 mb-2">Status Tugas</label>
                                <select name="status" :value="selectedTask?.status" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                    <option value="Belum Mulai">Belum Mulai</option>
                                    <option value="Sedang Dikerjakan">Sedang Dikerjakan</option>
                                    <option value="Perlu Revisi">Perlu Revisi</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-400 mb-2">Progress (%)</label>
                                <input type="number" name="progress" :value="selectedTask?.progress" min="0" max="100" required class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-400 mb-2">Catatan Perkembangan</label>
                            <textarea name="catatan" rows="3" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent" placeholder="Jelaskan apa yang sudah dikerjakan..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-400 mb-2">Kendala / Hambatan (Opsional)</label>
                            <textarea name="kendala" rows="2" class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-red-500 focus:border-transparent" placeholder="Tuliskan kendala jika ada..."></textarea>
                        </div>
                    </div>
                    <div class="px-8 py-5 bg-slate-900/80 border-t border-slate-700/50 flex justify-end gap-3">
                        <button type="button" @click="updateModal = false" class="px-5 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-bold text-sm shadow-sm hover:bg-slate-700 border border-slate-600">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-emerald-500 text-white font-bold text-sm shadow-lg">Kirim Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Riwayat Update Modal -->
    <div x-show="historyModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="historyModal" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="historyModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="historyModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-[#0F172A] border border-slate-700 rounded-3xl text-left overflow-hidden shadow-[0_0_40px_rgba(0,0,0,0.5)] transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                <div class="px-6 py-6 border-b border-slate-700/50 bg-slate-900/30">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-white">Riwayat Update Tugas</h3>
                            <p class="text-xs text-slate-400 mt-1" x-text="selectedTask?.title"></p>
                        </div>
                        <button type="button" @click="historyModal = false" class="text-slate-400 hover:text-white transition-colors bg-slate-800 p-2 rounded-xl"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>
                </div>
                <div class="px-8 py-6 h-[60vh] overflow-y-auto custom-scrollbar bg-slate-900/20">
                    <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-700 before:to-transparent">
                        
                        <template x-if="historyUpdates.length === 0">
                            <div class="text-center py-8 text-slate-500 italic relative z-10 bg-[#0F172A] border border-slate-800 rounded-2xl mx-10">Belum ada riwayat update.</div>
                        </template>

                        <template x-for="(update, index) in historyUpdates" :key="index">
                            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                <!-- Icon -->
                                <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-[#0F172A] bg-slate-800 text-slate-500 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2" :class="update.progress == 100 ? 'text-emerald-400' : 'text-cyan-400'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <!-- Card -->
                                <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-slate-800/80 backdrop-blur-md p-5 rounded-2xl border border-slate-700 shadow">
                                    <div class="flex flex-col sm:flex-row justify-between items-start mb-2 gap-2">
                                        <div class="font-bold text-white text-sm flex items-center gap-2">
                                            <span x-text="update.user"></span>
                                            <span class="text-[10px] px-2 py-0.5 rounded-full border border-slate-600 bg-slate-900/50 text-slate-300" x-text="update.status"></span>
                                        </div>
                                        <time class="text-xs font-bold text-cyan-500/70" x-text="update.date"></time>
                                    </div>
                                    <div class="mb-2">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Progress</span>
                                            <span class="text-xs font-bold text-white" x-text="update.progress + '%'"></span>
                                        </div>
                                        <div class="w-full bg-slate-900 rounded-full h-1">
                                            <div class="h-1 rounded-full bg-cyan-500" :style="'width: ' + update.progress + '%'"></div>
                                        </div>
                                    </div>
                                    <p class="text-sm text-slate-300 mt-3" x-show="update.catatan">
                                        <span class="font-bold text-slate-500 block text-[10px] uppercase mb-1">Catatan:</span>
                                        <span x-text="update.catatan"></span>
                                    </p>
                                    <p class="text-sm text-red-300/80 mt-3 p-3 bg-red-900/10 border border-red-500/20 rounded-xl" x-show="update.kendala">
                                        <span class="font-bold text-red-500/70 block text-[10px] uppercase mb-1">Kendala:</span>
                                        <span x-text="update.kendala"></span>
                                    </p>
                                </div>
                            </div>
                        </template>

                    </div>
                </div>
                <div class="px-8 py-5 bg-slate-900/80 border-t border-slate-700/50 flex justify-end">
                    <button type="button" @click="historyModal = false" class="px-5 py-2.5 rounded-xl bg-slate-800 text-slate-300 font-bold text-sm shadow-sm hover:bg-slate-700 border border-slate-600">Tutup Riwayat</button>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
