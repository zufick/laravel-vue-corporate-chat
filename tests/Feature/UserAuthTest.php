<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    /**
     * Тест успешной авторизации
     */
    public function test_user_authenticates()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200);
    }

    /**
     * Тест ошибки при авторизации
     */
    public function test_user_wrong_password()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '123123',
        ]);
        $response->assertStatus(422);
    }
}
