<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'User',
            'email' => 'user@mail.com',
            'password' => '@12345678'
        ]);
    }

    public function test_should_create_a_user(): void
    {
        $data = [
            'name' => 'Test',
            'email' => 'test@mail.com',
            'password' => '123456789',
        ];

        $response = $this->postJson(route('users.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'test@mail.com']);
    }
}
