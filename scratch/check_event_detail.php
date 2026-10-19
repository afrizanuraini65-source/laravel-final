<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\Attendance;

$e2 = Event::find(2);
echo "Event 2: " . json_encode($e2) . "\n";
echo "Attendances for Event 2: " . Attendance::where('event_id', 2)->count() . "\n";
echo "Total Attendances in DB: " . Attendance::count() . "\n";
