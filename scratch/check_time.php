<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Carbon now: " . \Carbon\Carbon::now()->toDateTimeString() . "\n";
echo "Hour: " . \Carbon\Carbon::now()->hour . "\n";
echo "Timezone: " . config('app.timezone') . "\n";
