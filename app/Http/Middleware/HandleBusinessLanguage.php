<?php

namespace App\Http\Middleware;

use App\Models\BusinessTranslation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\Business;
use Inertia\Inertia;
use Inertia\Middleware;

class HandleBusinessLanguage extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $business = $request->route('business');
        // Skip if no business is bound to the route

        if (!$business || !($business instanceof Business)) {
            Log::info('No business model found in route, skipping language resolution.');
            $business=null;
        }

        $language =$business->lang ?? 'bs'; 
        $data = getTranslations($language);

        session(['language' => $data['language']]);

        Inertia::share([
            'language' => $data['language'],
            'translations' => $data['translations'],
        ]);

        return $next($request);
    }
}
