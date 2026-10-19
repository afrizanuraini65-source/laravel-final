<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRolesTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_roles_can_access_dashboard_and_sidebar_renders(): void
    {
        $accounts = [
            'admin_skakmatt@gmail.com',
            'afaf_ketua@gmail.com',
            'florisa_wakil@gmail.com',
            'shayla_sekretaris@gmail.com',
            'alyaa_bendahara@gmail.com',
            'nasywa_kegiatan@gmail.com',
            'sekarwangi_humas@gmail.com',
            'hanumara_pdd@gmail.com',
            'syarifah_pkwu@gmail.com',
            'benaya_konselor@gmail.com',
            'hayu_pembina@gmail.com',
            'nabila_pelatih@gmail.com',
            'aora_anggota@gmail.com',
        ];

        foreach ($accounts as $email) {
            $user = User::where('email', $email)->first();
            $this->assertNotNull($user, "User {$email} must exist.");

            $response = $this->actingAs($user)->get('/dashboard');
            $response->assertStatus(200);
            $response->assertSee($user->name);
        }
    }

    public function test_admin_settings_registration_page_renders_for_leadership(): void
    {
        $leadership = [
            'admin_skakmatt@gmail.com',
            'afaf_ketua@gmail.com',
            'shayla_sekretaris@gmail.com',
        ];

        foreach ($leadership as $email) {
            $user = User::where('email', $email)->first();
            $response = $this->actingAs($user)->get(route('admin.settings.registration.index'));
            $response->assertStatus(200);
            $response->assertSee('Pengaturan Periode Pendaftaran');
        }
    }
}
