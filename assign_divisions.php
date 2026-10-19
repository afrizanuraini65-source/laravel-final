<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Member;
use App\Models\Division;

$divisions = Division::all();
// Get ALL members that don't have a division
$members = Member::with('user')->whereNull('division_id')->get();

$roles = ['KEGIATAN', 'HUMAS', 'PDD', 'PKWU', 'KONSELOR_SEBAYA'];

$idx = 0;
foreach ($members as $member) {
    if (!$member->user) continue;

    // Skip Nasywa if she was already updated, but just in case
    if (strpos(strtolower($member->user->name), 'nasywa') !== false) {
        $div = Division::where('name', 'Kegiatan')->first();
        $member->division_id = $div->id;
        $member->save();
        $member->user->assignRole('KEGIATAN');
        $member->user->email = 'nasywa_kegiatan@gmail.com';
        $member->user->save();
        continue;
    }

    $roleName = $roles[$idx % count($roles)];
    $div = null;
    if ($roleName == 'KEGIATAN') $div = Division::where('name', 'Kegiatan')->first();
    if ($roleName == 'HUMAS') $div = Division::where('name', 'Hubungan Masyarakat')->first();
    if ($roleName == 'PDD') $div = Division::where('name', 'Publikasi, Dekorasi & Dokumentasi (PDD)')->first();
    if ($roleName == 'PKWU') $div = Division::where('name', 'Prakarya & Wirausaha (PKWU)')->first();
    if ($roleName == 'KONSELOR_SEBAYA') $div = Division::where('name', 'Konselor Sebaya')->first();
    
    if ($div) {
        $member->division_id = $div->id;
        $member->save();
    }
    
    $member->user->assignRole($roleName);
    
    $firstName = strtolower(explode(' ', trim($member->user->name))[0]);
    $roleClean = strtolower(str_replace('_', '', $roleName));
    if (strpos($roleClean, 'konselor') !== false) {
        $roleClean = 'konselor';
    }

    $newEmail = $firstName . '_' . $roleClean . '@gmail.com';
    $member->user->email = $newEmail;
    $member->user->save();
    
    echo "Assigned " . $member->user->name . " to " . $roleName . " -> " . $newEmail . "\n";
    $idx++;
}
echo "Done assigning divisions and roles to ALL remaining members.";
