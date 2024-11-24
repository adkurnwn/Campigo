<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TemporaryUser;
use App\Notifications\VerifyEmailOtp;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
        ]);

        // Delete any existing temporary registration for this email
        TemporaryUser::where('email', $request->email)->delete();

        // Generate OTP
        $otp = sprintf("%06d", mt_rand(1, 999999));

        // Store temporary user data
        $tempUser = TemporaryUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        // Send OTP notification
        Notification::route('mail', $request->email)
            ->notify(new VerifyEmailOtp($otp));

        return redirect()->route('verify.otp.form', ['email' => $request->email])
            ->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }
}
