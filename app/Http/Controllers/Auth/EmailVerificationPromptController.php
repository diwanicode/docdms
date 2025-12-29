<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Domain\Business\BusinessChecker;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request, BusinessChecker $businessChecker): RedirectResponse|Response
    {
        return $request->user()->hasVerifiedEmail()
                    ? $businessChecker->redirectUserToBusinessDashboard($request->user())
                    : Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
    }
}
