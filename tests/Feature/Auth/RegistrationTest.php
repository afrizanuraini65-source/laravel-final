<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->withSession([
            'reg_otp' => '123456',
            'reg_otp_email' => 'test@example.com',
            'reg_otp_expires_at' => now()->addMinutes(15),
        ])->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'kelas' => 'X PPLG 1',
            'birth_place' => 'Banyumas',
            'birth_date' => '2008-01-01',
            'phone' => '081234567890',
            'verification_code' => '123456',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.pending'));
    }
}
