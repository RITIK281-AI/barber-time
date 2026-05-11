@extends('layouts.guest')

@section('content')
<div class="auth-wrapper">
    @include('auth.partials.branding-panel')

    <div class="auth-right">
        <div class="auth-card auth-card-compact">

            <h2 class="auth-heading">Set New Password</h2>
            <p class="auth-subheading">
                Choose a strong password for your TrimTime account.
            </p>

            <form method="POST" action="{{ route('password.store') }}" novalidate>
                @csrf

                {{-- New Password --}}
                <div class="auth-field auth-password-field">
                    <div class="auth-label-row">
                        <label for="password" class="auth-label">New Password</label>
                    </div>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="auth-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Minimum 9 chars with uppercase, number, and symbol"
                        required
                        autofocus
                        autocomplete="new-password"
                    >
                    <button type="button" class="auth-password-toggle" data-password-target="password" aria-label="Show password" aria-pressed="false">
                        <span class="icon-eye" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="presentation" focusable="false">
                                <path d="M1.5 12s3.8-6.5 10.5-6.5S22.5 12 22.5 12s-3.8 6.5-10.5 6.5S1.5 12 1.5 12z"></path>
                                <circle cx="12" cy="12" r="3.5"></circle>
                            </svg>
                        </span>
                        <span class="icon-eye-off" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="presentation" focusable="false">
                                <path d="M3 3l18 18"></path>
                                <path d="M10.6 6a10.4 10.4 0 0 1 1.4-.1C18.7 5.9 22.5 12 22.5 12a20.8 20.8 0 0 1-3.3 4.2"></path>
                                <path d="M6.1 6.1A20.2 20.2 0 0 0 1.5 12s3.8 6.5 10.5 6.5a9.8 9.8 0 0 0 5.1-1.4"></path>
                            </svg>
                        </span>
                    </button>
                    @error('password')
                        <span class="auth-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="auth-field auth-password-field">
                    <div class="auth-label-row">
                        <label for="password_confirmation" class="auth-label">Confirm New Password</label>
                    </div>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="auth-input"
                        placeholder="Repeat your password"
                        required
                        autocomplete="new-password"
                    >
                    <button type="button" class="auth-password-toggle" data-password-target="password_confirmation" aria-label="Show password" aria-pressed="false">
                        <span class="icon-eye" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="presentation" focusable="false">
                                <path d="M1.5 12s3.8-6.5 10.5-6.5S22.5 12 22.5 12s-3.8 6.5-10.5 6.5S1.5 12 1.5 12z"></path>
                                <circle cx="12" cy="12" r="3.5"></circle>
                            </svg>
                        </span>
                        <span class="icon-eye-off" aria-hidden="true">
                            <svg viewBox="0 0 24 24" role="presentation" focusable="false">
                                <path d="M3 3l18 18"></path>
                                <path d="M10.6 6a10.4 10.4 0 0 1 1.4-.1C18.7 5.9 22.5 12 22.5 12a20.8 20.8 0 0 1-3.3 4.2"></path>
                                <path d="M6.1 6.1A20.2 20.2 0 0 0 1.5 12s3.8 6.5 10.5 6.5a9.8 9.8 0 0 0 5.1-1.4"></path>
                            </svg>
                        </span>
                    </button>
                </div>

                <button type="submit" class="auth-btn">
                    Reset Password
                </button>

                <div class="auth-footer">
                    <a href="{{ route('login') }}">Back to Sign in</a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
