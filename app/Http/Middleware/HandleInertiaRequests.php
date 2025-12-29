<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Log;
use App\Domain\Business\BusinessChecker;
use App\Models\Business;

class HandleInertiaRequests extends Middleware
{
    public function __construct(public BusinessChecker $businessChecker)
    {
    }
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
     public function share(Request $request): array
    {
        $user = $request->user();
        $businessSlug = $request->route('business');  
        $business = $this->resolveBusinessFromRoute( $request);
        $language = session('language', 'bs');
        $translations = getTranslations($business?->lang,$language);

        return [
            ...parent::share($request),
            'auth' => [
                'user' => fn () =>  $user
                ?  $user->only('id', 'name', 'email')
                : null,
            ],
            'permissions' => fn () => $user 
                             ? $this->businessChecker->employeeAllPermissions($user)
                             : [],          
            'flash' =>  $this->getFlashMessages($request),
            'language' => $language,
            'translations' => $translations,
            'cspNonce' => csp_nonce(),
        ];
    }
    protected function getFlashMessages(Request $request): array
    {
        return [
            'message' => $request->session()->get('message'),
            'success' => $request->session()->get('success'),
            'error' => $request->session()->get('error'),
            'warning' => $request->session()->get('warning'),
        ];
    }

    protected function resolveBusinessFromRoute(Request $request): ?Business
    {
        $slug = $request->route('business');
     
        if (! $slug || $slug === 'appspecific') {
            Log::info('No valid business slug found, skipping resolution.');
            return null;  
        }
        if ($slug instanceof Business) {           
            return $slug;
        }
        if (is_string($slug)) {
            try {
                return $this->businessChecker->getBusinessBySlug($slug);
            } catch (ModelNotFoundException $e) {
                Log::warning("Business not found for slug: $slug");
                return null;
            }
        }

        return null;
    }
}
