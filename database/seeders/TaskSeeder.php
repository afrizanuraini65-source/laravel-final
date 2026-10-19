<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\TaskUpdate;
use App\Models\User;
use App\Models\Division;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereHas('member')->get();
        if ($users->isEmpty()) {
            return;
        }

        $divisions = Division::all();
        if ($divisions->isEmpty()) {
            return;
        }

        // We'll create some dummy tasks
        $tasksData = [
            [
                'title' => 'Menyiapkan Poster Seminar',
                'description' => 'Membuat desain poster untuk seminar kesehatan mental minggu depan.',
                'status' => 'Sedang Dikerjakan',
                'priority' => 'Tinggi',
                'progress' => 70,
                'days_offset' => 5, // 5 days from now
            ],
            [
                'title' => 'Mencari Pemateri',
                'description' => 'Menghubungi psikolog untuk acara seminar bulanan.',
                'status' => 'Belum Mulai',
                'priority' => 'Tinggi',
                'progress' => 0,
                'days_offset' => 3,
            ],
            [
                'title' => 'Membuat Proposal Kegiatan',
                'description' => 'Menyusun proposal kegiatan kemah bakti PIK-R.',
                'status' => 'Perlu Revisi',
                'priority' => 'Sedang',
                'progress' => 90,
                'days_offset' => 2,
            ],
            [
                'title' => 'Menyiapkan Absensi Peserta',
                'description' => 'Membuat form absensi untuk peserta forum rutin.',
                'status' => 'Selesai',
                'priority' => 'Rendah',
                'progress' => 100,
                'days_offset' => -1,
            ],
            [
                'title' => 'Rapat Evaluasi',
                'description' => 'Mempersiapkan materi rapat evaluasi program kerja.',
                'status' => 'Sedang Dikerjakan',
                'priority' => 'Sedang',
                'progress' => 40,
                'days_offset' => 7,
            ],
        ];

        foreach ($tasksData as $data) {
            $division = $divisions->random();
            // Try to find a user in this division
            $pic = $users->filter(function($u) use ($division) {
                return optional($u->member)->division_id == $division->id;
            })->first();

            // If no user in this division, pick any user
            if (!$pic) {
                $pic = $users->random();
            }

            $task = Task::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'division_id' => $division->id,
                'assigned_to_user_id' => $pic->id,
                'deadline' => Carbon::now()->addDays($data['days_offset']),
                'priority' => $data['priority'],
                'status' => $data['status'],
                'progress' => $data['progress'],
            ]);

            // Add an initial update
            TaskUpdate::create([
                'task_id' => $task->id,
                'user_id' => $pic->id,
                'progress' => $data['progress'],
                'status' => $data['status'],
                'catatan' => 'Update progress terakhir dari PIC.',
            ]);
        }
    }
}
