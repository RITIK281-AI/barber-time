<nav class="navbar navbar-expand-lg fixed-top trimtime-nav">
    <div class="container">

        <!-- brand -->
        <a class="navbar-brand trimtime-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/BarberTime_logo.svg') }}" class="trimtime-brand-logo" alt="BarberTime">
            <span class="trimtime-brand-text">BarberTime</span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">

            <!-- center nav links -->
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link trimtime-link {{ request()->routeIs('home') ? 'tt-active' : '' }}"
                        href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link trimtime-link {{ request()->routeIs('frontend.shops.index', 'frontend.shops.show') ? 'tt-active' : '' }}"
                        href="{{ route('frontend.shops.index') }}">Find Barber Shops</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link trimtime-link {{ request()->routeIs('how-it-works') ? 'tt-active' : '' }}"
                        href="{{ route('how-it-works') }}">How It Works</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link trimtime-link {{ request()->routeIs('frontend.shops.partner.*') ? 'tt-active' : '' }}"
                        href="{{ route('frontend.shops.partner.create') }}">Become a Partner</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link trimtime-link {{ request()->routeIs('contact*') ? 'tt-active' : '' }}"
                        href="{{ route('contact.index') }}">Contact</a>
                </li>

                @auth
                <li class="nav-item">
                    <a class="nav-link trimtime-link {{ request()->routeIs('frontend.bookings.*', 'dashboard') ? 'tt-active' : '' }}"
                        href="{{ route('frontend.bookings.index') }}">My Bookings</a>
                </li>
                @endauth
            </ul>

            <!-- right: auth buttons or user dropdown -->
            <div class="d-flex gap-2 align-items-center">
                @auth
                    <div class="dropdown">
                        <button class="btn tt-user-btn dropdown-toggle d-flex align-items-center gap-2"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="tt-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="d-none d-lg-inline">{{ explode(' ', auth()->user()->name)[0] }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end tt-dropdown shadow-sm">
                            <li>
                                <span class="dropdown-item-text text-muted small px-3 py-1">
                                    {{ auth()->user()->email }}
                                </span>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn tt-btn-ghost">Log In</a>
                    <a href="{{ route('register') }}" class="btn tt-btn-primary">Sign Up</a>
                @endauth
            </div>

        </div>
    </div>
</nav>
