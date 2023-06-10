<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ... $roles): Response
    {
        $user = \Auth::user();

        if($user->isAdmin()){
            return $next($request);
        }

        foreach($roles as $role) {
            if($user->hasRole((int)$role)){
                return $next($request);
            }
        }

        return redirect('/');
    }
}
