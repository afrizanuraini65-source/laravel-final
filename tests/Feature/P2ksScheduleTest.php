<?php

namespace Tests\Feature;

use App\Models\P2ksSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class P2ksScheduleTest extends TestCase
{
    protected array $pengurusRoles = [
        'ADMIN', 'PEMBINA', 'PELATIH', 'KETUA', 'WAKIL_KETUA',
        'SEKRETARIS', 'BENDAHARA', 'KEGIATAN', 'HUMAS', 'PDD',
        'PKWU', 'KONSELOR_SEBAYA',
    ];

    public function test_ketua_and_wakil_ketua_have_can_edit_access(): void
    {
        $ketua = User::where('email', 'afaf_ketua@gmail.com')->first();
        $wakil = User::where('email', 'florisa_wakil@gmail.com')->first();

        $this->assertNotNull($ketua);
        $this->assertNotNull($wakil);

        $responseKetua = $this->actingAs($ketua)->get(route('p2ks.index'));
        $responseKetua->assertStatus(200);
        $responseKetua->assertSee('Spin Wheel P2KS');
        $responseKetua->assertSee('Kelola Status');

        $responseWakil = $this->actingAs($wakil)->get(route('p2ks.index'));
        $responseWakil->assertStatus(200);
        $responseWakil->assertSee('Spin Wheel P2KS');
        $responseWakil->assertSee('Kelola Status');
    }

    public function test_admin_and_other_roles_cannot_see_management_controls(): void
    {
        $otherUsers = [
            'admin_skakmatt@gmail.com',
            'shayla_sekretaris@gmail.com',
            'alyaa_bendahara@gmail.com',
            'hayu_pembina@gmail.com',
            'nasywa_kegiatan@gmail.com',
            'aora_anggota@gmail.com',
        ];

        foreach ($otherUsers as $email) {
            $user = User::where('email', $email)->first();
            $this->assertNotNull($user, "User {$email} must exist");

            $response = $this->actingAs($user)->get(route('p2ks.index'));
            $response->assertStatus(200);
            $response->assertDontSee('Spin Wheel P2KS');
            $response->assertDontSee('Kelola Status');
        }
    }

    public function test_eligible_candidates_only_include_non_pengurus_members(): void
    {
        $ketua = User::where('email', 'afaf_ketua@gmail.com')->first();

        $response = $this->actingAs($ketua)->get(route('p2ks.index'));
        $response->assertStatus(200);

        /** @var Collection $eligibleUsers */
        $eligibleUsers = $response->viewData('eligibleUsers');

        $this->assertNotEmpty($eligibleUsers, 'Eligible users list should not be empty');

        foreach ($eligibleUsers as $candidate) {
            // Must have USER role
            $this->assertTrue($candidate->hasRole('USER'), "Candidate {$candidate->name} must have role USER");

            // Must NOT have any pengurus roles
            $this->assertFalse(
                $candidate->hasAnyRole($this->pengurusRoles),
                "Candidate {$candidate->name} must NOT have any pengurus role"
            );
        }
    }

    public function test_non_ketua_cannot_spin_or_update_status(): void
    {
        $admin = User::where('email', 'admin_skakmatt@gmail.com')->first();
        $sekre = User::where('email', 'shayla_sekretaris@gmail.com')->first();
        $user = User::where('email', 'aora_anggota@gmail.com')->first();

        $schedule = P2ksSchedule::first();
        $this->assertNotNull($schedule);

        // Spin attempt
        $this->actingAs($admin)
            ->postJson(route('p2ks.spin'))
            ->assertStatus(403);

        $this->actingAs($sekre)
            ->postJson(route('p2ks.spin'))
            ->assertStatus(403);

        $this->actingAs($user)
            ->postJson(route('p2ks.spin'))
            ->assertStatus(403);

        // Update status attempt
        $this->actingAs($admin)
            ->put(route('p2ks.status', $schedule), ['status' => 'hadir'])
            ->assertSessionHas('error');

        $this->actingAs($user)
            ->put(route('p2ks.status', $schedule), ['status' => 'hadir'])
            ->assertSessionHas('error');
    }

    public function test_ketua_can_update_status(): void
    {
        $ketua = User::where('email', 'afaf_ketua@gmail.com')->first();
        $schedule = P2ksSchedule::first();
        $this->assertNotNull($schedule);

        $response = $this->actingAs($ketua)
            ->put(route('p2ks.status', $schedule), ['status' => 'hadir']);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('hadir', $schedule->fresh()->status);
    }

    public function test_ketua_can_spin_wheel_and_candidate_is_strictly_non_pengurus(): void
    {
        $ketua = User::where('email', 'afaf_ketua@gmail.com')->first();
        $this->assertNotNull($ketua);

        // Clear schedules created today so we don't hit the daily limit of 2
        P2ksSchedule::whereDate('scheduled_date', Carbon::today())->delete();

        $response = $this->actingAs($ketua)
            ->postJson(route('p2ks.spin'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'user' => ['id', 'name', 'email'],
            'message',
        ]);

        $selectedUserId = $response->json('user.id');
        $selectedUser = User::find($selectedUserId);

        $this->assertNotNull($selectedUser);
        $this->assertTrue($selectedUser->hasRole('USER'));
        $this->assertFalse($selectedUser->hasAnyRole($this->pengurusRoles));

        $this->assertDatabaseHas('p2ks_schedules', [
            'user_id' => $selectedUserId,
            'status' => 'menunggu',
        ]);
    }
}
