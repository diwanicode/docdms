<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Domain\Business\BusinessChecker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request, BusinessChecker $businessChecker): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
             return $businessChecker->redirectUserToBusinessDashboard($request->user()); 
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
