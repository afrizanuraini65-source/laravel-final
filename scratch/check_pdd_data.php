<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Division;
use App\Models\User;
use App\Models\Member;

$divs = Division::all(['id', 'name']);
echo "DIVISIONS:\n";
foreach ($divs as $d) {
    echo "ID: {$d->id} - {$d->name}\n";
}

$pddDiv = Division::where('name', 'like', '%PDD%')->orWhere('name', 'like', '%Publikasi%')->first();
if ($pddDiv) {
    echo "\nPDD Division ID: {$pddDiv->id} - {$pddDiv->name}\n";
    $members = Member::where('division_id', $pddDiv->id)->with('user')->get();
    echo "PDD Members count: " . $members->count() . "\n";
}

$pddUsers = User::role('PDD')->get();
echo "Users with PDD role count: " . $pddUsers->count() . "\n";
foreach ($pddUsers as $u) {
    echo "- User: {$u->name} ({$u->email})\n";
}
