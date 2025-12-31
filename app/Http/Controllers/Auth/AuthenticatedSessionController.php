<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Domain\Business\BusinessChecker;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request , BusinessChecker $businessChecker) 
    {
        $request->authenticate();

          Log::info('in');
        $user =  User::where('email', $request->email)->where('is_active',true)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
         Log::info( $user);
        $testEmails = ['selma@example.com', 'aida@example.com','adin@example.com','emina@example.com'];
        $isTestUser = in_array($user->email, $testEmails);
  
        $sendCode = !($isTestUser);
      
        if($sendCode){
            Auth::logout();
            Log::info('code');
            
        }else{  
            Auth::login($user);
            return $businessChecker->redirectUserToBusinessDashboard($user); 
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
