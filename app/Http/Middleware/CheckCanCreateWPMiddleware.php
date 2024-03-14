<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCanCreateWPMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * @var \App\Models\User $user
         */
        $user = Auth::user();
        if($user->role_id === config('roles.admin')) {
            return $next($request);
        } else {
            return abort(\Illuminate\Http\Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }
}
