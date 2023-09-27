<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleHasPermission
{
    public function handle($request, Closure $next, $permission)
    {
        // Check if the user is authenticated.
        if (!Auth::check()) {
            return redirect()->route('landing'); // Redirect to the login page or return an error response.
        }

        // Check if the user has the specified permission for any of their roles.
        if (!Auth::user()->hasPermissionTo($permission)) {
            abort(403, 'Unauthorized'); // Return a 403 Forbidden response if the user doesn't have the permission.
        }

        return $next($request);
    }
}
