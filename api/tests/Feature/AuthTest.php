<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'User',
            'email' => 'user@mail.com',
            'password' => '@12345678',
        ]);
    }

    public function test_should_login(): void
    {
        $data = [
            'email' => $this->user->email,
            'password' => '@12345678',
        ];

        $response = $this->postJson(route('login'), $data);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token', 'expires_in']);
    }

    public function test_can_refresh_token(): void
    {
        $token = auth()->login($this->user);

        $response = $this->postJson(route('refresh'), [], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token', 'expires_in']);
    }


    public function test_should_not_login(): void
    {
        $data = [
            'email' => $this->user->email,
            'password' => 'WRONG_PASSWORD',
        ];

        $response = $this->postJson(route('login'), $data);
        $response->assertStatus(401);
    }

    public function test_should_return_user_authenticated(): void
    {
        $response = $this->actingAs($this->user)->getJson(route('me'));
        $response->assertStatus(200);
        $response->assertJsonStructure(['id', 'name', 'email']);
        $response->assertJson(['id' => $this->user->id]);
    }

}
