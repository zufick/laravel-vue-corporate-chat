<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\Room;
use App\Models\RoomModerator;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class MessageTest extends TestCase
{
    /**
     * Тест просмотра главного канала
     */
    public function test_sees_general_room()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->get('/api/messages/1');
        $response->assertJson(['current_page' => 1]);
    }

    /**
     * Тест сохранения сообщения
     */
    public function test_message_stores()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->post("/api/messages/1", [
                'text' => 'test'
            ]);

        $response = $this->actingAs($user)
            ->get("/api/messages/1");
        $response->assertJson([
            'data' => array(
                ['text' => "test"]
            )
        ]);
    }

    /**
     * Тест невозможности отправки сообщения в каналы, в которых не участвует пользователь
     */
    public function test_cannot_store_message_in_forbidden_room()
    {
        $user = User::factory()->create();
        $room = Room::create(['name' => 'Private room', 'owner_id' => 1]);
        $response = $this->actingAs($user)
            ->post("/api/messages/".$room->id, [
                'text' => 'test'
            ]);

        $response->assertStatus(403);
    }

    /**
     * Тест модерации сообщения модератором
     */
    public function test_can_moderate_message()
    {
        $user = User::factory()->create();
        $moderator = User::factory()->create();
        RoomModerator::create(['user_id' => $moderator->id, 'room_id' => 1]);

        $this->actingAs($user)
        ->post("/api/messages/1", [
            'text' => 'test'
        ]);

        $response = $this->actingAs($moderator)
        ->delete("/api/message/1");

        $response->assertStatus(200);
    }

    /**
     * Тест модерации своего сообщения
     */
    public function test_can_moderate_own_message()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post("/api/messages/1", [
                'text' => 'test'
            ]);

        $response = $this->actingAs($user)
            ->delete("/api/message/1");

        $response->assertStatus(200);
    }

    /**
     * Тест невозможности модерации чужого сообщения от обычного пользователя
     */
    public function test_cannot_moderate_others_messages()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->actingAs($user1)
            ->post("/api/messages/1", [
                'text' => 'test'
            ]);

        $response = $this->actingAs($user2)
            ->delete("/api/message/1");

        $response->assertStatus(403);
    }
}
