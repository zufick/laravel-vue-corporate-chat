<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Models\Message;
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
        MessageEvent::dispatch($room->id, $user->id, $user->name, $text);
        $message = \App\Models\Message::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'text' => $text
        ]);
    }

    public function index(Request $request, Room $room)
    {
        $messages = Message::where('room_id', $room->id)->get();
        return response(['messages'=>$messages]);
    }
}
