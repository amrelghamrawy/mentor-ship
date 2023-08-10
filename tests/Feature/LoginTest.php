<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_login_with_correct_credentials_return_ok()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertOk();
        $response->assertJsonStructure(['access_token']);
    }

    public function test_login_with_no_credentials_return_invalid_credentials()
    {

        $response = $this->postJson('/api/login', [
            'email' => 'incorrect@email.com',
            'password' => 'password',
        ]);
        $response->assertStatus(422);
        $response->assertJsonFragment(['error' => 'the provided credentials are incorrect']);
    }
}
