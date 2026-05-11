@extends('layouts.guest')

@section('content')
<div class="auth-wrapper">

    {{-- ── Left Panel ── --}}
    <div class="auth-left">
        <div class="auth-logo-circle">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <circle cx="6" cy="6" r="3"/>
                <circle cx="6" cy="18" r="3"/>
                <line x1="20" y1="4" x2="8.12" y2="15.88"/>
                <line x1="14.47" y1="14.48" x2="20" y2="20"/>
                <line x1="8.12" y1="8.12" x2="12" y2="12"/>
            </svg>
        </div>
        <div class="auth-brand-name">TrimTime</div>
        <div class="auth-brand-tag">Premium Barber Booking Platform</div>
        <ul class="auth-features">
            <li><span class="auth-feat-dot"></span> Book top-rated barbers in Nepal</li>
            <li><span class="auth-feat-dot"></span> Real-time availability & scheduling</li>
            <li><span class="auth-feat-dot"></span> Manage appointments effortlessly</li>
            <li><span class="auth-feat-dot"></span> Secure payments via Khalti</li>
            <li><span class="auth-feat-dot"></span> Ratings & reviews after each visit</li>
        </ul>
    </div>

    {{-- ── Right Panel ── --}}
    <div class="auth-right">
        <div class="auth-card">

            {{-- Envelope icon --}}
            <div style="
                width: 64px; height: 64px;
                background: #fff3e6;
                border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                margin-bottom: 1.25rem;
            ">
                <svg viewBox="0 0 24 24" width="28" height="28" fill="none"
                     stroke="#e55c00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                    <path d="M2 7l10 7 10-7"/>
                </svg>
            </div>

            <h2 class="auth-heading">Verify your email</h2>
            <p class="auth-subheading">
                Thanks for signing up! Before you get started, please verify your email address
                by clicking the link we just sent you.
            </p>

            {{-- Resend success message --}}
            @if (session('status') === 'verification-link-sent')
                <div class="auth-alert auth-alert-success">
                    A new verification link has been sent to your email address.
                </div>
            @endif

            <div style="display:flex; flex-direction:column; gap:10px; margin-top:0.5rem;">

                {{-- Resend button --}}
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="auth-btn">Resend verification email</button>
                </form>

                {{-- Log out --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="auth-btn-ghost">Sign out</button>
                </form>

            </div>

        </div>
    </div>

</div>
@endsection
