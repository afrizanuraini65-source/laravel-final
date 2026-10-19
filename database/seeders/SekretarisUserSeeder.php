<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\OrganizationPeriod;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SekretarisUserSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'SEKRETARIS']);

        $user = User::firstOrCreate(
            ['email' => 'sekretaris@gmail.com'],
            [
                'name' => 'Dewi Sekretaris',
                'password' => Hash::make('password'),
                'role' => 'SEKRETARIS',
            ]
        );

        if (! $user->hasRole('SEKRETARIS')) {
            $user->assignRole('SEKRETARIS');
        }

        $period = OrganizationPeriod::where('is_active', true)->first();
        $posSekre = Position::firstOrCreate(['title' => 'Sekretaris']);

        if ($period && $posSekre) {
            Member::firstOrCreate([
                'user_id' => $user->id,
                'organization_period_id' => $period->id,
            ], [
                'position_id' => $posSekre->id,
                'name' => 'Dewi Sekretaris',
            ]);
        }
    }
}
