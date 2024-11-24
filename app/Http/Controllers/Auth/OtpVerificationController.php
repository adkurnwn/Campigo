<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TemporaryUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class OtpVerificationController extends Controller
{
    public function showVerificationForm(Request $request)
    {
        $email = $request->email;
        $tempUser = TemporaryUser::where('email', $email)->first();

        if (!$tempUser) {
            return redirect()->route('register')
                ->withErrors(['email' => 'Registrasi tidak valid. Silakan daftar ulang.']);
        }

        if (!$tempUser->isOtpValid()) {
            $tempUser->delete();
            return redirect()->route('register')
                ->withErrors(['email' => 'OTP telah kadaluarsa. Silakan daftar ulang.']);
        }

        return view('auth.verify-otp', ['email' => $email]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $tempUser = TemporaryUser::where('email', $request->email)->first();

        if (!$tempUser || !$tempUser->isOtpValid()) {
            return back()->withErrors(['otp' => 'OTP tidak valid atau telah kadaluarsa.']);
        }

        if ($tempUser->otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP tidak sesuai.']);
        }

        // Create verified user
        $user = User::create([
            'name' => $tempUser->name,
            'email' => $tempUser->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        // Delete temporary user
        $tempUser->delete();

        // Log the user in
        Auth::login($user);

        return redirect()->route('home')
            ->with('status', 'Akun berhasil dibuat dan terverifikasi!');
    }

    public function resend(Request $request)
    {
        $tempUser = TemporaryUser::where('email', $request->email)->first();

        if (!$tempUser) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Generate new OTP
        $otp = sprintf("%06d", mt_rand(1, 999999));
        
        // Update temporary user
        $tempUser->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        // Resend OTP notification
        Notification::route('mail', $tempUser->email)
            ->notify(new VerifyEmailOtp($otp));

        return back()->with('status', 'Kode OTP baru telah dikirim.');
    }
}