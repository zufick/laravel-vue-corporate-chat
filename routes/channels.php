<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

//Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});

Broadcast::channel('chat.{roomId}', function (\App\Models\User $user, $roomId) {
    if ($user->canJoinRoom($roomId)) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'admin' => $user->admin,
            'canModerateRoom' => $user->canModerateRoom($roomId)
        ];
    }
});

Broadcast::channel('user.rooms.{userId}', function (\App\Models\User $user, $userId) {
    return $user->id == $userId;
});
