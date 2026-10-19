<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\P2ksSchedule;
use App\Models\User;
use Carbon\Carbon;

echo "Current P2KS schedules count: " . P2ksSchedule::count() . "\n";

$users = User::whereHas('member')->pluck('id')->toArray();
if (empty($users)) {
    $users = User::pluck('id')->toArray();
}

// Sample dates to enrich the schedule:
$additionalSchedules = [
    // Oktober 2026
    ['date' => '2026-10-10', 'status' => 'hadir'],
    ['date' => '2026-10-10', 'status' => 'hadir'],
    ['date' => '2026-10-08', 'status' => 'hadir'],
    ['date' => '2026-10-05', 'status' => 'hadir'],
    ['date' => '2026-10-05', 'status' => 'tidak_hadir'],

    // September 2026
    ['date' => '2026-09-28', 'status' => 'hadir'],
    ['date' => '2026-09-28', 'status' => 'hadir'],
    ['date' => '2026-09-22', 'status' => 'hadir'],
    ['date' => '2026-09-15', 'status' => 'hadir'],
    ['date' => '2026-09-15', 'status' => 'hadir'],

    // Agustus 2026
    ['date' => '2026-08-28', 'status' => 'hadir'],
    ['date' => '2026-08-28', 'status' => 'hadir'],
    ['date' => '2026-08-18', 'status' => 'hadir'],
    ['date' => '2026-08-10', 'status' => 'hadir'],
];

$added = 0;
foreach ($additionalSchedules as $item) {
    // Pick random user
    $randomUserId = $users[array_rand($users)];
    
    // Check if user already has schedule on this date
    $exists = P2ksSchedule::where('user_id', $randomUserId)->whereDate('scheduled_date', $item['date'])->exists();
    if (!$exists) {
        P2ksSchedule::create([
            'user_id' => $randomUserId,
            'scheduled_date' => $item['date'],
            'status' => $item['status'],
        ]);
        $added++;
    }
}

echo "Added {$added} extra historical schedules!\n";
echo "Total P2KS schedules now: " . P2ksSchedule::count() . "\n";
