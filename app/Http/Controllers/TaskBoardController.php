<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskUpdate;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskBoardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Task::with(['division', 'assignee', 'updates.user']);

        // Filter based on role: Pimpinan & Sekretaris melihat seluruh tugas
        if (!$user->hasRole(['ADMIN', 'KETUA', 'WAKIL_KETUA', 'SEKRETARIS', 'PEMBINA'])) {
            // Member or Koordinator: only see their division tasks
            $divisionId = $user->member->division_id ?? null;
            if ($divisionId) {
                $query->where('division_id', $divisionId);
            } else {
                // If they don't have a division, they see nothing
                $query->where('id', 0);
            }
        }

        // Apply Search
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Apply Filters
        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        // Clone query for stats calculation before status filtering
        $statsTasks = (clone $query)->get();

        // Global stats (only related to the tasks the user can see)
        $stats = [
            'total' => $statsTasks->count(),
            'belum_mulai' => $statsTasks->where('status', 'Belum Mulai')->count(),
            'sedang_dikerjakan' => $statsTasks->where('status', 'Sedang Dikerjakan')->count(),
            'perlu_revisi' => $statsTasks->where('status', 'Perlu Revisi')->count(),
            'selesai' => $statsTasks->where('status', 'Selesai')->count(),
        ];

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->orderBy('deadline', 'asc')->get();

        // Organize tasks by status for Kanban
        $columns = [
            'Belum Mulai' => $tasks->where('status', 'Belum Mulai'),
            'Sedang Dikerjakan' => $tasks->where('status', 'Sedang Dikerjakan'),
            'Perlu Revisi' => $tasks->where('status', 'Perlu Revisi'),
            'Selesai' => $tasks->where('status', 'Selesai'),
        ];

        // Division progress summaries
        $divisionProgress = [];
        $divisions = Division::with('tasks')->get();
        
        if ($user->hasRole(['ADMIN', 'KETUA', 'WAKIL_KETUA', 'SEKRETARIS', 'PEMBINA'])) {
            foreach ($divisions as $div) {
                $divTasks = $div->tasks;
                if ($divTasks->count() > 0) {
                    $avgProgress = $divTasks->avg('progress');
                    $divisionProgress[$div->name] = round($avgProgress);
                } else {
                    $divisionProgress[$div->name] = 0;
                }
            }
        }

        $allDivisions = Division::all();
        $potentialAssignees = User::whereHas('member')->get(); // This will be filtered on frontend based on division

        return view('task-board.index', compact(
            'columns', 'stats', 'divisionProgress', 'allDivisions', 'potentialAssignees', 'user'
        ));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasRole(['ADMIN', 'KETUA', 'WAKIL_KETUA', 'SEKRETARIS', 'PEMBINA'])) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'division_id' => 'required|exists:divisions,id',
            'assigned_to_user_id' => 'required|exists:users,id',
            'deadline' => 'required|date',
            'priority' => 'required|in:Rendah,Sedang,Tinggi',
            'status' => 'required|in:Belum Mulai,Sedang Dikerjakan,Perlu Revisi,Selesai',
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $task = Task::create($validated);

        // Record initial update
        TaskUpdate::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'progress' => $task->progress,
            'status' => $task->status,
            'catatan' => 'Tugas baru dibuat.',
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil dibuat.');
    }

    public function update(Request $request, Task $task)
    {
        if (!Auth::user()->hasRole(['ADMIN', 'KETUA'])) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'division_id' => 'required|exists:divisions,id',
            'assigned_to_user_id' => 'required|exists:users,id',
            'deadline' => 'required|date',
            'priority' => 'required|in:Rendah,Sedang,Tinggi',
        ]);

        $task->update($validated);

        return redirect()->back()->with('success', 'Tugas berhasil diperbarui.');
    }

    public function updateProgress(Request $request, Task $task)
    {
        $user = Auth::user();

        // Check if user is PIC (Koordinator) or Admin/Ketua
        $isPic = $task->assigned_to_user_id == $user->id;
        $isAdminOrKetua = $user->hasRole(['ADMIN', 'KETUA']);

        if (!$isPic && !$isAdminOrKetua) {
            abort(403, 'Hanya Koordinator/PIC atau Admin yang dapat mengupdate tugas ini.');
        }

        $validated = $request->validate([
            'progress' => 'required|integer|min:0|max:100',
            'status' => 'required|in:Belum Mulai,Sedang Dikerjakan,Perlu Revisi,Selesai',
            'catatan' => 'nullable|string',
            'kendala' => 'nullable|string',
        ]);

        $task->update([
            'progress' => $request->progress,
            'status' => $request->status,
        ]);

        TaskUpdate::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'progress' => $request->progress,
            'status' => $request->status,
            'catatan' => $request->catatan,
            'kendala' => $request->kendala,
        ]);

        return redirect()->back()->with('success', 'Progress tugas berhasil diperbarui.');
    }

    public function destroy(Task $task)
    {
        if (!Auth::user()->hasRole(['ADMIN', 'KETUA'])) {
            abort(403);
        }

        $task->delete();
        return redirect()->back()->with('success', 'Tugas berhasil dihapus.');
    }
}
