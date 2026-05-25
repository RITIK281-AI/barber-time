<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class OtpPasswordController extends Controller
{
    // ── Step 1: Show email form ───────────────────────────────────────────────

    public function showEmailForm(): View
    {
        return view('auth.forgot-password');
    }

    // ── Step 1: Send OTP to email ─────────────────────────────────────────────

    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'No account found with this email address.',
        ]);

        // Generate 6-digit OTP
        $otp = strval(random_int(100000, 999999));

        // Delete any existing OTP for this email
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Store new OTP (plain text — we compare directly)
        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => $otp,
            'created_at' => now(),
        ]);

        // Send OTP email
        Mail::send('emails.otp', ['otp' => $otp], function ($mail) use ($request) {
            $mail->to($request->email)
                 ->subject('BarberTime — Your Password Reset OTP');
        });

        // Store email in session for next steps
        session(['otp_email' => $request->email]);

        return redirect()->route('password.otp')
            ->with('status', 'OTP sent! Please check your email.');
    }

    // ── Step 2: Show OTP form ─────────────────────────────────────────────────

    public function showOtpForm(): View|RedirectResponse
    {
        if (! session('otp_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-otp');
    }

    // ── Step 2: Verify OTP ────────────────────────────────────────────────────

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $email = session('otp_email');

        if (! $email) {
            return redirect()->route('password.request')
                ->withErrors(['otp' => 'Session expired. Please start again.']);
        }

        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        // Check OTP exists
        if (! $record) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please request a new one.']);
        }

        // Check OTP is correct
        if ($record->token !== $request->otp) {
            return back()->withErrors(['otp' => 'Incorrect OTP. Please try again.']);
        }

        // Check OTP is within 5 minutes
        $createdAt = \Carbon\Carbon::parse($record->created_at);
        if ($createdAt->diffInMinutes(now()) >= 5) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.'])
                ->with('expired', true);
        }

        // OTP is valid — mark as verified in session
        session(['otp_verified' => true]);

        return redirect()->route('password.reset');
    }

    // ── Step 3: Show new password form ───────────────────────────────────────

    public function showResetForm(): View|RedirectResponse
    {
        if (! session('otp_email') || ! session('otp_verified')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    // ── Step 3: Save new password ─────────────────────────────────────────────

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                Password::min(9)->numbers()->symbols()->rules(['regex:/[A-Z]/']),
            ],
        ]);

        $email = session('otp_email');

        if (! $email || ! session('otp_verified')) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Session expired. Please start again.']);
        }

        // Update user password
        User::where('email', $email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Clean up — delete OTP record and session data
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        session()->forget(['otp_email', 'otp_verified']);

        return redirect()->route('login')
            ->with('status', 'Password reset successfully! Please sign in.');
    }
}
