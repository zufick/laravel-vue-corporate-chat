<?php

namespace App\Http\Controllers;

use App\Events\MessageDeleteEvent;
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
        $message = \App\Models\Message::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'text' => $text
        ]);
        MessageEvent::dispatch($message->id, $room->id, $user->id, $user->name, $text);
        return response(['message_id' => $message->id])->setStatusCode(201);
    }

    public function index(Request $request, Room $room)
    {
        #$messages = Message::where('room_id', $room->id)->paginate(5);
        $messages = Message::where('room_id', $room->id)->get();
        return response(['messages'=>$messages]);
    }

    public function delete(Request $request, Message $message)
    {
        MessageDeleteEvent::dispatch($message->room_id, $message->id);
        $message->delete();
        return response(['message' => 'Сообщение удалено.']);
    }
}
