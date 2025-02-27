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
        if(!$text && $text !== "0")
            return;

        $user = $request->user();
        $message = Message::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'text' => $text
        ]);
        MessageEvent::dispatch($message, $room->id, $user->id, $user->name, $text);
        return response(['message' => $message])->setStatusCode(201);
    }

    public function index(Request $request, Room $room)
    {
        $messages = Message::where('room_id', $room->id)->latest()->paginate(10);
        return response($messages);
    }

    public function delete(Request $request, Message $message)
    {
        MessageDeleteEvent::dispatch($message->room_id, $message->id);
        $message->delete();
        return response(['message' => 'Сообщение удалено.']);
    }
}
