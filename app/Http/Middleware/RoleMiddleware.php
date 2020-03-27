<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{

    public function handle($request, Closure $next, ...$roles)
    {
        $user = $request->user();
        foreach ($roles as $role) {
            if ($user->role == $role)
                return $next($request);
        }
        return response(['unauthorized' => 'Không có quyền thực hiện thao tác này'], 401);
    }
}
