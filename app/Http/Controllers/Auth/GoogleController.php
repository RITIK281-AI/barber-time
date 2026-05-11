<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // Redirect to Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle Google callback
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['google' => 'Google login failed. Please try again.']);
        }

        // Find existing user by email
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Block non-customers from using Google login
            if ($user->role !== 'user') {
                return redirect()->route('login')
                    ->withErrors(['google' => 'Google login is only available for customers.']);
            }
        } else {
            // Create new customer account
            $user = User::create([
                'name'              => $googleUser->getName(),
                'email'             => $googleUser->getEmail(),
                'password'          => bcrypt(\Illuminate\Support\Str::random(24)),
                'role'              => 'user',
                'email_verified_at' => now(), // Google already verified the email
            ]);
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
