<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
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

        if ($user && ($user->id === $id || $user->role_id === RoleEnum::ADMIN)) {
            return $next($request);
        }

        return abort(HttpResponse::HTTP_FORBIDDEN);
    }
}
