<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (in_array($request->user()->role, $roles)) {
            return $next($request);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
