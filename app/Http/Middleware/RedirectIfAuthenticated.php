<?php

namespace App\Http\Middleware;

use App\Domain\Business\BusinessChecker;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user(); 

                $businessChecker = app(BusinessChecker::class);
                return $businessChecker->redirectUserToBusinessDashboard($user);
            }
        }

        return $next($request);
    }
}
