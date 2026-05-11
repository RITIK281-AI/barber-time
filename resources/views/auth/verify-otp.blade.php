@extends('layouts.guest')

@section('content')
<div class="auth-wrapper">
    @include('auth.partials.branding-panel')

    <div class="auth-right">
        <div class="auth-card auth-card-compact">

            <h2 class="auth-heading">Enter OTP</h2>
            <p class="auth-subheading">
                We sent a 6-digit OTP to <strong>{{ session('otp_email') }}</strong>.
                It expires in <strong>5 minutes</strong>.
            </p>

            @if (session('status'))
                <div class="auth-alert auth-alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.otp.verify') }}" novalidate>
                @csrf

                <div class="auth-field">
                    <label for="otp" class="auth-label">6-Digit OTP</label>
                    <input
                        type="text"
                        id="otp"
                        name="otp"
                        class="auth-input auth-input-otp {{ $errors->has('otp') ? 'is-invalid' : '' }}"
                        placeholder="Enter your OTP"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        required
                        autofocus
                        autocomplete="off"
                    >
                    @error('otp')
                        <span class="auth-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="auth-timer">
                    OTP expires in: <span id="countdown" class="auth-timer-value">05:00</span>
                </div>

                <button type="submit" class="auth-btn">
                    Verify OTP
                </button>

            </form>

            <div class="auth-footer auth-footer-top-gap">
                Didn't receive it?
                <form method="POST" action="{{ route('password.email') }}" class="auth-inline-form">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('otp_email') }}">
                    <button type="submit" class="auth-link-btn">
                        Resend OTP
                    </button>
                </form>
            </div>

            <div class="auth-footer">
                <a href="{{ route('password.request') }}">Use a different email</a>
            </div>

        </div>
    </div>

</div>

<script>
    // 5-minute countdown timer
    (function () {
        var seconds = 300;
        var display  = document.getElementById('countdown');

        if (!display) return;

        var interval = setInterval(function () {
            seconds--;
            var m = Math.floor(seconds / 60);
            var s = seconds % 60;
            display.textContent = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;

            if (seconds <= 0) {
                clearInterval(interval);
                display.textContent = '00:00';
                display.classList.add('auth-timer-expired');
                display.closest('.auth-timer').innerHTML = '<span class="auth-expired-note">OTP has expired. Please request a new one.</span>';
            }
        }, 1000);
    })();
</script>

@endsection
