<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateAdmin
{
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('admins')->check()) {
            return redirect()->route('admin.login'); // Redirect to admin login page
        }

        return $next($request);
    }
}
