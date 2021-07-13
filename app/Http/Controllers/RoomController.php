<?php

namespace App\Http\Controllers;

use App\Events\UserRoomsUpdated;
use App\Models\Room;
use App\Models\RoomModerator;
use App\Models\RoomUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    /**
     * Вызов WebSocket для передачи списка каналов пользователю
     * @param Request $request
     */
    public function index(Request $request)
    {
        UserRoomsUpdated::dispatch($request->user());
    }

    /**
     * Добавление канала
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);
        if($validator->fails())
            return response(['errors' => $validator->errors()]);

        $roomData = $validator->validated();
        $roomData['owner_id'] = Auth::user()->id;
        $room = Room::create($roomData);
        $room->addAdmins();
        $room->addUser(Auth::user());
        $room->dispatchRoomUpdateToUsers();

    }

    public function update(Request $request, Room $room)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);
        if($validator->fails())
            return response(['errors' => $validator->errors()]);

        $room->update(['name' => $request->input('name')]);
        $room->dispatchRoomUpdateToUsers();
    }

    /**
     * Удаление канала
     * @param Request $request
     * @param Room $room
     */
    public function delete(Request $request, Room $room)
    {
        $roomUsers = $room->users;
        $room->delete();
        foreach($roomUsers as $roomUser){
            UserRoomsUpdated::dispatch($roomUser);
        }
    }

    /**
     * Получение списка всех пользователей для управления каналом
     * @param Request $request
     * @param Room $room
     * @return User[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function allUsersForRoom(Request $request, Room $room)
    {
        $users = User::all()->map(function($user) use($room){
            return [
                'id' => $user->id,
                'name' => $user->name,
                'canJoinRoom' => $user->canJoinRoom($room->id),
                'canModerateRoom' => $user->canModerateRoom($room->id),
                'admin' => $user->admin
            ];
        });
        return $users;
    }

    public function inviteUser(Request $request, Room $room, User $user)
    {
        try {
            RoomUser::create(['room_id' => $room->id, 'user_id' => $user->id]);
            UserRoomsUpdated::dispatch($user);
        }catch (\Exception $exception) {
            Log::info("Duplicate user in room $room->id $user->id");
        }
    }

    public function kickUser(Request $request, Room $room, User $user)
    {
        $roomUser = RoomUser::where(['room_id' => $room->id, 'user_id' => $user->id])->first();
        if($roomUser && !$user->admin){
            $roomUser->delete();
            UserRoomsUpdated::dispatch($user);
        }
    }

    public function moderUser(Request $request, Room $room, User $user)
    {
        try {
            RoomModerator::create(['room_id' => $room->id, 'user_id' => $user->id]);
            UserRoomsUpdated::dispatch($user);
        }catch (\Exception $exception) {
            Log::info("Duplicate user mod in room $room->id $user->id");
        }
    }

    public function demoderUser(Request $request, Room $room, User $user)
    {
        $roomModer = RoomModerator::where(['room_id' => $room->id, 'user_id' => $user->id])->first();
        if($roomModer && !$user->admin){
            $roomModer->delete();
            UserRoomsUpdated::dispatch($user);
        }
    }
}
