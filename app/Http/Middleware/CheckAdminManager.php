<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        if ($user->role_id === RoleEnum::ADMIN || $user->role_id === RoleEnum::MANAGER) {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => 'Access denied',
        ], 403);
    }
}
