<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CanJoinRoom
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
        if(!$request->user()->canJoinRoom($request->route('room')->id))
            return response(['error' => 'Forbidden'])->setStatusCode(403);

        return $next($request);
    }
}
