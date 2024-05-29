<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

class TestUserValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $id = getLastSegmentRegex(request()->getPathInfo());

        if ($user) {
            if ($user->id === $id || $user->role_id === RoleEnum::ADMIN) {
                return $next($request);
            }
            if ($user->role_id === RoleEnum::MANAGER && User::where('id', $id)->where('wp_id', '=', $user->wp_id)->exists()) {
                return $next($request);
            }
        }

        return abort(HttpResponse::HTTP_FORBIDDEN);
    }
}
