<?php

namespace Tests\Feature;

use App\Events\UserRoomsUpdated;
use App\Models\Room;
use App\Models\RoomModerator;
use App\Models\RoomUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class RoomTest extends TestCase
{
    /**
     * Получает ли пользователь список своих комнат
     */
    public function test_sees_rooms()
    {
        Event::fake();
        $user = User::factory()->create();
        $user->joinGeneralRooms();
        $response = $this->actingAs($user)->get('/api/rooms');
        Event::assertDispatched(function (UserRoomsUpdated $event) use($user){
            $userRoomIds = collect($user->rooms->toArray())->pluck("id")->sort()->values();
            $eventRoomIds = collect($event->rooms)->pluck("id")->sort()->values();
            return $event->userId === $user->id && $userRoomIds == $eventRoomIds;
        });
        $response->assertStatus(200);
    }

    /**
     * Тест создания комнаты
     */
    public function test_room_stores()
    {
        Event::fake();
        $roomName = 'new_room';
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->post('/api/rooms', ['name' => $roomName]);
        Event::assertDispatched(function (UserRoomsUpdated $event) use($roomName){
            foreach ($event->rooms as $room){
                if($room['name'] == $roomName)
                    return true;
            }
            return false;
        });
    }

    /**
     * Тест удаления комнаты
     */
    public function test_room_deletes()
    {
        $roomName = 'new_room';
        $roomId = 3;
        $user = User::factory()->create();
        $this->actingAs($user)
            ->post('/api/rooms', ['name' => $roomName]);


        Event::fake();

        $response = $this->actingAs($user)
            ->delete('/api/rooms/'.$roomId);

        Event::assertDispatched(function (UserRoomsUpdated $event) use($roomName){
            foreach ($event->rooms as $room){
                if($room['name'] == $roomName)
                    return false;
            }
            return true;
        });
    }

    /**
     * Тест невозможности удаления комнаты для обычного пользователя
     */
    public function test_forbidden_room_deletion()
    {
        $roomName = 'new_room';
        $roomId = 3;
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $this->actingAs($user)
            ->post('/api/rooms', ['name' => $roomName]);


        Event::fake();

        $response = $this->actingAs($user2)
            ->delete('/api/rooms/'.$roomId);
        $response->assertStatus(403);
    }

    /**
     * Тест получения списка всех пользователей для упралвения комнатой
     */
    public function test_sees_room_users()
    {
        $roomName = 'new_room';
        $user = User::factory()->create();
        RoomModerator::create(['user_id' => $user->id, 'room_id' => 1]);
        $response = $this->actingAs($user)->get('/api/rooms/1');
        $response->assertJsonFragment(['id' => $user->id, 'name' => $user->name]);
    }

    /**
     * Тест обновления комнаты
     */
    public function test_room_updates()
    {
        $roomName = 'new_room';
        $roomId = 3;
        $user = User::factory()->create();
        $this->actingAs($user)
            ->post('/api/rooms', ['name' => $roomName]);

        Event::fake();
        $updatedRoomName = "updated_room";
        $this->actingAs($user)
            ->patch('/api/rooms/'.$roomId, ['name' => $updatedRoomName]);
        Event::assertDispatched(function (UserRoomsUpdated $event) use($updatedRoomName){
            foreach ($event->rooms as $room){
                if($room['name'] == $updatedRoomName)
                    return true;
            }
            return false;
        });
    }

    /**
     * Тест приглашения пользователя в комнату
     */
    public function test_user_invitable()
    {
        $roomName = 'new_room';
        $roomId = 3;
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->actingAs($user1)
            ->post('/api/rooms', ['name' => $roomName]);

        Event::fake();
        $this->actingAs($user1)
            ->post('/api/rooms/'.$roomId.'/'.$user2->id.'/invite');
        Event::assertDispatched(function (UserRoomsUpdated $event) use($roomName, $user2){
            if($event->userId == $user2->id) {
                foreach ($event->rooms as $room) {
                    if ($room['name'] == $roomName)
                        return true;
                }
                return false;
            }
        });
    }

    /**
     * Тест удаления пользователя из комнаты
     */
    public function test_user_kickable()
    {
        $roomName = 'new_room';
        $roomId = 3;
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $room = Room::create(['name' => 'new_room', 'owner_id' => $user1->id]);
        RoomUser::create(['room_id' => $room->id, 'user_id' => $user1->id]);
        RoomUser::create(['room_id' => $room->id, 'user_id' => $user2->id]);

        Event::fake();
        $this->actingAs($user1)
            ->post('/api/rooms/'.$roomId.'/'.$user2->id.'/kick');
        Event::assertDispatched(function (UserRoomsUpdated $event) use($roomName, $user2){
            if($event->userId == $user2->id) {
                foreach ($event->rooms as $room) {
                    if ($room['name'] == $roomName)
                        return false;
                }
                return true;
            }
        });
    }

    /**
     * Тест добавления модератора в комнату
     */
    public function test_user_make_moderator()
    {
        $roomName = 'new_room';
        $roomId = 3;
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $room = Room::create(['name' => 'new_room', 'owner_id' => $user1->id]);
        RoomUser::create(['room_id' => $room->id, 'user_id' => $user1->id]);
        RoomUser::create(['room_id' => $room->id, 'user_id' => $user2->id]);

        Event::fake();
        $this->actingAs($user1)
            ->post('/api/rooms/'.$roomId.'/'.$user2->id.'/moder');
        Event::assertDispatched(function (UserRoomsUpdated $event) use($roomName, $user2){
            if($event->userId == $user2->id) {
                foreach ($event->rooms as $room) {
                    if ($room['canModerateRoom'] == true)
                        return true;
                }
                return false;
            }
        });
    }

    /**
     * Тест удаления модератора из комнаты
     */
    public function test_user_remove_moderator()
    {
        $roomName = 'new_room';
        $roomId = 3;
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $room = Room::create(['name' => 'new_room', 'owner_id' => $user1->id]);
        RoomUser::create(['room_id' => $room->id, 'user_id' => $user1->id]);
        RoomUser::create(['room_id' => $room->id, 'user_id' => $user2->id]);
        RoomModerator::create(['room_id' => $room->id, 'user_id' => $user2->id]);

        Event::fake();
        $this->actingAs($user1)
            ->post('/api/rooms/'.$roomId.'/'.$user2->id.'/demoder');
        Event::assertDispatched(function (UserRoomsUpdated $event) use($roomName, $user2){
            if($event->userId == $user2->id) {
                foreach ($event->rooms as $room) {
                    if ($room['canModerateRoom'] == false)
                        return true;
                }
                return false;
            }
        });
    }
}
