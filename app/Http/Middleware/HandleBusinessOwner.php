<?php

namespace App\Http\Middleware;

use Closure;
use App\Domain\Business\BusinessChecker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class HandleBusinessOwner
{
    public function __construct(protected BusinessChecker $businessChecker)
    {
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $business = $request->route('business'); // Business model from route model binding
      
        if (!$user || !$business) {
            return redirect()->route('welcome')->with('message', 'Unauthorized access!'); 
        }
          Log::info($business); 
          Log::info($user);
        if (is_string($business)) {
            $business = $this->businessChecker->getBusinessBySlug($business);
        }
        $hasBusinessAccess = $this->businessChecker->employeeHasBusinessAccess($business,$user);
        
        if (!$hasBusinessAccess) {
            return redirect()->route('welcome')->with('message', 'Unauthorized access!');
        }

        return $next($request);
    }
}
