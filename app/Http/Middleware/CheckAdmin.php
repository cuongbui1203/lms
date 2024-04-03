<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->role_id === RoleEnum::ADMIN) {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => 'Access denied',
        ], 403);
    }
}
