<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Models\Room;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request, Room $room)
    {
        $text = $request->input('text');
        if(!$text)
            return;

        $user = $request->user();
        $message = \App\Models\Message::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'text' => $text
        ]);
        MessageEvent::dispatch($message, $user->id, $user->name, $text);
    }
}
