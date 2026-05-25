@extends('frontend.layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class="dashboard-wrapper">
    <div class="container py-4">
        <div class="row g-4">

            {{-- sidebar --}}
            <div class="col-md-3">
                <div class="profile-sidebar">
                    <div class="profile-sidebar-header">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                 alt="Photo" class="profile-avatar mb-2">
                        @else
                            <div class="profile-avatar-placeholder">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="fw-bold fs-6">{{ auth()->user()->name }}</div>
                        <div class="small opacity-75">{{ auth()->user()->email }}</div>
                        <div class="loyalty-badge">
                            <i class="bi bi-star-fill"></i>
                            {{ auth()->user()->loyalty_points ?? 0 }} Loyalty Points
                        </div>
                    </div>

                    <ul class="nav flex-column sidebar-nav py-2">
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'bookings' ? 'active' : '' }}"
                               href="{{ route('dashboard') }}?tab=bookings">
                                <i class="bi bi-calendar-check"></i> My Bookings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'profile' ? 'active' : '' }}"
                               href="{{ route('dashboard') }}?tab=profile">
                                <i class="bi bi-person"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'notifications' ? 'active' : '' }}"
                               href="{{ route('dashboard') }}?tab=notifications">
                                <i class="bi bi-bell"></i> Notifications
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'favourites' ? 'active' : '' }}"
                            href="{{ route('favourites.index') }}">
                                <i class="bi bi-heart"></i> Favourites
                            </a>
                        </li>
                        <li class="nav-item border-top mt-2 pt-1">
                            <a class="nav-link" style="color: var(--trim-blue);"
                               href="{{ route('frontend.shops.index') }}">
                                <i class="bi bi-scissors"></i> Book Appointment
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- main panel --}}
            <div class="col-md-9">

                {{-- flash messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show rounded-3 mb-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- load the correct tab partial --}}
                @include('frontend.dashboard.' . $activeTab)

            </div>
        </div>
    </div>
</div>
@endsection
