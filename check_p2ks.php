<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$schedules = \App\Models\P2ksSchedule::with('user')->get();
foreach ($schedules as $s) {
    echo "ID: {$s->id}, User: {$s->user->name}, Date: {$s->scheduled_date}, Status: {$s->status}\n";
}
