<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Member;
use App\Models\Event;
use App\Models\Attendance;

echo "Total Users: " . User::count() . "\n";
echo "Total Members: " . Member::count() . "\n";
echo "Events:\n";
foreach (Event::all() as $e) {
    echo "ID {$e->id}: {$e->title} ({$e->date})\n";
}

$afriza = User::where('name', 'like', '%Afriza%')->first();
if ($afriza) {
    echo "Afriza found: ID {$afriza->id}, email {$afriza->email}, role: {$afriza->role}\n";
}

$afaf = User::where('name', 'like', '%Afaf%')->first();
if ($afaf) {
    echo "Afaf found: ID {$afaf->id}, email {$afaf->email}, role: {$afaf->role}\n";
}
