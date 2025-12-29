<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use App\Domain\Business\BusinessChecker;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request, BusinessChecker $businessChecker): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $businessChecker->redirectUserToBusinessDashboard($user);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return $businessChecker->redirectUserToBusinessDashboard($user);
    }
}
