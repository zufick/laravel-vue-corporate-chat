<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CanModerateRoom
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $room =  $request->route('room');
        if(!$room || !$request->user()->canModerateRoom($room->id))
            return response(['error' => 'Forbidden'])->setStatusCode(403);

        return $next($request);
    }
}
