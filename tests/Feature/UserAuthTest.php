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
        $response->assertSee("token");
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

    /**
     * Тест получения информации о пользователе
     */
    public function test_user_get_info()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->get('/api/user');
        $response->assertJson(['email' => $user->email]);
    }

    public function test_user_logout()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->get('/api/logout');
        $response->assertStatus(200);
    }
}
