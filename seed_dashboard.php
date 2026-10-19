<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\OrganizationPeriod;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

$period = OrganizationPeriod::where('is_active', true)->first();
if (!$period) {
    $period = OrganizationPeriod::first();
}

// 1. Create Events
$events = [
    [
        'organization_period_id' => $period->id,
        'title' => 'Forum Rutin Mingguan',
        'slug' => 'forum-rutin-' . time(),
        'description' => 'Forum rutin setiap hari Rabu untuk seluruh anggota PIK-R.',
        'date' => Carbon::parse('next wednesday')->format('Y-m-d'),
        'time' => '15:30:00',
        'location' => 'Ruang Multimedia',
        'quota' => 100,
        'status' => 'OPEN',
    ],
    [
        'organization_period_id' => $period->id,
        'title' => 'Diklat Anggota Baru 2026',
        'slug' => 'diklat-2026-' . time(),
        'description' => 'Pendidikan dan Pelatihan dasar untuk calon anggota baru PIK-R SKAKMATT.',
        'date' => Carbon::now()->addDays(3)->format('Y-m-d'),
        'time' => '07:30:00',
        'location' => 'Aula Utama',
        'quota' => 80,
        'status' => 'OPEN',
    ],
    [
        'organization_period_id' => $period->id,
        'title' => 'Penyuluhan Kesehatan Reproduksi',
        'slug' => 'penyuluhan-kespro-' . time(),
        'description' => 'Penyuluhan mengenai pentingnya kesehatan reproduksi remaja di lingkungan sekolah.',
        'date' => Carbon::now()->addDays(7)->format('Y-m-d'),
        'time' => '09:00:00',
        'location' => 'Lapangan Indoor',
        'quota' => 200,
        'status' => 'OPEN',
    ]
];

foreach ($events as $event) {
    Event::create($event);
}

// 2. Create Notifications for all users (or just for testing)
// We will create some dummy notifications for the current logged in user.
// Since we don't know who is logged in, let's create for all users or the first few users.

$users = User::all();

foreach ($users as $user) {
    $notifications = [
        [
            'id' => Str::uuid()->toString(),
            'type' => 'App\Notifications\EventNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $user->id,
            'data' => json_encode([
                'type' => 'task',
                'message' => 'Tugas baru: Buat proposal kegiatan akhir tahun',
                'event' => 'Manajemen Kegiatan'
            ]),
            'read_at' => null,
            'created_at' => Carbon::now()->subMinutes(30),
            'updated_at' => Carbon::now()->subMinutes(30),
        ],
        [
            'id' => Str::uuid()->toString(),
            'type' => 'App\Notifications\EventNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $user->id,
            'data' => json_encode([
                'type' => 'committee',
                'message' => 'Anda terpilih menjadi panitia Diklat',
                'event' => 'Diklat Anggota Baru 2026'
            ]),
            'read_at' => null,
            'created_at' => Carbon::now()->subHours(2),
            'updated_at' => Carbon::now()->subHours(2),
        ],
        [
            'id' => Str::uuid()->toString(),
            'type' => 'App\Notifications\GeneralNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $user->id,
            'data' => json_encode([
                'type' => 'info',
                'message' => 'Pengingat: Tagihan kas bulan September belum lunas.',
                'event' => null
            ]),
            'read_at' => null,
            'created_at' => Carbon::now()->subDays(1),
            'updated_at' => Carbon::now()->subDays(1),
        ]
    ];

    foreach ($notifications as $notification) {
        \DB::table('notifications')->insert($notification);
    }
}

echo "Successfully seeded events and notifications!";
