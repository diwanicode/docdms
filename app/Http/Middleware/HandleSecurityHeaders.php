<?php

namespace App\Http\Middleware;

use Closure;

class HandleSecurityHeaders
{
    public function handle($request, Closure $next)
    { 
        if (app()->environment('local')) {
            return $next($request);
        }
        return $response
            ->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('X-Frame-Options', 'DENY')
            ->header('X-XSS-Protection', '1; mode=block')
            ->header('Referrer-Policy', 'no-referrer') 
            ->header('Permissions-Policy', 'accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=()');
    }
}
