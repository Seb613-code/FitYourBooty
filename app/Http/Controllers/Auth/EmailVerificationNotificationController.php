<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (\Throwable $exception) {
            Log::warning('Email verification resend failed.', [
                'user_id' => $request->user()?->id,
                'error' => $exception->getMessage(),
            ]);

            return back()->with('status', 'verification-link-send-failed');
        }

        return back()->with('status', 'verification-link-sent');
    }
}
