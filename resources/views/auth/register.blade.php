@extends('layouts.guest')

@section('content')
<div class="auth-wrapper">
    @include('auth.partials.branding-panel')

    <div class="auth-right">
        <div class="auth-card">
            <h2 class="auth-heading">Create an account</h2>
            <p class="auth-subheading">Create your account and start booking trusted barbers nearby.</p>

            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf

                <div class="auth-grid auth-grid-two">
                    <div class="auth-field">
                        <label for="first_name" class="auth-label">First name</label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            class="auth-input {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                            value="{{ old('first_name') }}"
                            placeholder="Diman"
                            required
                            autofocus
                        >
                        @error('first_name')
                            <span class="auth-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="auth-field">
                        <label for="last_name" class="auth-label">Last name</label>
                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            class="auth-input {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                            value="{{ old('last_name') }}"
                            placeholder="Pun"
                            required
                        >
                        @error('last_name')
                            <span class="auth-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

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
                        autocomplete="username"
                    >
                    @error('email')
                        <span class="auth-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="auth-field">
                    <label for="phone" class="auth-label">Phone number</label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        class="auth-input {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                        value="{{ old('phone') }}"
                        placeholder="+977 98XXXXXXXX"
                    >
                    @error('phone')
                        <span class="auth-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="auth-field auth-password-field">
                    <label for="password" class="auth-label">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="auth-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Min. 8 characters"
                        required
                        autocomplete="new-password"
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

                {{-- Confirm Password --}}
                <div class="auth-field auth-password-field">
                    <label for="password_confirmation" class="auth-label">Confirm password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="auth-input"
                        placeholder="Repeat your password"
                        required
                        autocomplete="new-password"
                    >
                    <button
                        type="button"
                        class="auth-password-toggle"
                        data-password-target="password_confirmation"
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
                </div>

                {{-- Terms --}}
                <div class="auth-check-row">
                    <input type="checkbox" id="agree" name="agree" required>
                    <label for="agree">I agree to the <a href="#" class="auth-link">terms and conditions</a></label>
                </div>

                <button type="submit" class="auth-btn">Create account</button>

                <div class="auth-footer">
                    Already have an account? <a href="{{ route('login') }}">Sign in</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
