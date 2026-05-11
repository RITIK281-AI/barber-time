@extends('layouts.guest')

@section('content')
<div class="auth-wrapper">
    @include('auth.partials.branding-panel')

    <div class="auth-right">
        <div class="auth-card">
            <h2 class="auth-heading">Welcome back</h2>
            <p class="auth-subheading">Sign in to continue managing your bookings and schedule.</p>

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                {{-- Email --}}
                <div class="auth-field">
                    <label for="email" class="auth-label">Email address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="auth-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        value="{{ old('email') }}"
                        placeholder="you@example.com"
                        required
                        autofocus
                        autocomplete="username"
                    >
                    @error('email')
                        <span class="auth-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="auth-field auth-password-field">
                    <div class="auth-label-row">
                        <label for="password" class="auth-label">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="auth-link">Forgot password?</a>
                        @endif
                    </div>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="auth-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >
                    <button
                        type="button"
                        class="auth-password-toggle"
                        data-password-target="password"
                        aria-label="Show password"
                        aria-pressed="false"
                    >
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

                {{-- Remember me --}}
                <div class="auth-check-row">
                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Remember me for 30 days</label>
                </div>

                <button type="submit" class="auth-btn">Sign in</button>

                <div class="auth-footer">
                    Don't have an account? <a href="{{ route('register') }}">Create one</a>
                </div>

                <div class="auth-divider">
                    <span>or</span>
                </div>

                <a href="{{ route('auth.google') }}" class="auth-google-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" class="auth-google-icon" aria-hidden="true">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continue with Google
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
