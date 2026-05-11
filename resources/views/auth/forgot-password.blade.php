@extends('layouts.guest')

@section('content')
<div class="auth-wrapper">
    @include('auth.partials.branding-panel')

    <div class="auth-right">
        <div class="auth-card auth-card-compact">

            <h2 class="auth-heading">Forgot Password?</h2>
            <p class="auth-subheading">
                Enter your registered email address and we will send you a 6-digit OTP to reset your password.
            </p>

            @if (session('status'))
                <div class="auth-alert auth-alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" novalidate>
                @csrf

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
                        autocomplete="email"
                    >
                    @error('email')
                        <span class="auth-error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="auth-btn">
                    Send OTP
                </button>

                <div class="auth-footer">
                    Remember your password? <a href="{{ route('login') }}">Back to Sign in</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
