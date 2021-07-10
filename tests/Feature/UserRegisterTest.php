<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    public function test_registration_success(){
        $response = $this->postJson('/api/register', [
            'name' => 'John',
            'email' => 'john@mail.com',
            'password' => '123456'
        ]);

        $response->assertStatus(201);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_registration_fails_validation_email_unique()
    {
        for ($i = 0; $i <= 1; $i++) {
            $response = $this->postJson('/api/register', [
                'name' => 'John2',
                'email' => 'john@mail.com',
                'password' => '123456'
            ]);
        }

        $response->assertStatus(422);
    }
}
