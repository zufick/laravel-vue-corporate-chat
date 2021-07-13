<?php

namespace App\Http\Controllers;

use App\Events\UserRoomsUpdated;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        UserRoomsUpdated::dispatch($request->user());
    }
}
