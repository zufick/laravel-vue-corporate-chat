<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CanModerateMessage
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
        $message =  $request->route('message');
        $user = $request->user();

        if(!$message || (!$user->canModerateRoom($message->room_id) && $message->user_id !== $user->id))
            return response(['error' => 'Forbidden'])->setStatusCode(403);

        return $next($request);
    }
}
