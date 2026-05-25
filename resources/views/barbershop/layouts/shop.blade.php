<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberBook - Shop Admin</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom Barber Shop Theme -->
    <link rel="stylesheet" href="{{ asset('site/css/barbershop-theme.css') }}">
</head>
<body class="barbershop-layout">

<div class="barbershop-shell">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <img src="{{ asset('images/BarberTime_logo.svg') }}" class="sidebar-brand-logo" alt="BarberTime">
                <span>BarberTime Shop</span>
            </div>

            <div class="sidebar-profile mt-4">
                <div class="profile-avatar">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="profile-info">
                    <div class="profile-name">{{ auth()->user()->name ?? 'Shop Admin' }}</div>
                    <div class="profile-email">{{ auth()->user()->email ?? 'admin@shop.com' }}</div>
                    <div class="profile-role">Shop Administrator</div>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav mt-4">
            <a href="{{ route('shop.dashboard') }}" class="{{ request()->routeIs('shop.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('shop.barbers.index') }}" class="{{ request()->routeIs('shop.barbers.*') ? 'active' : '' }}">
                <i class="bi bi-scissors"></i>
                <span>Barbers</span>
            </a>
            <a href="{{ route('shop.services.index') }}" class="{{ request()->routeIs('shop.services.*') ? 'active' : '' }}">
                <i class="bi bi-list-check"></i>
                <span>Services</span>
            </a>
            <a href="{{ route('shop.bookings.index') }}" class="{{ request()->routeIs('shop.bookings.*') ? 'active' : '' }}">
                <i class="bi bi-calendar2-check"></i>
                <span>Bookings</span>
            </a>
            <a href="{{ route('shop.schedule.index') }}" class="{{ request()->routeIs('shop.schedule.*') ? 'active' : '' }}">
                <i class="bi bi-calendar2-week"></i>
                <span>Schedule</span>
            </a>
            <a href="{{ route('shop.payments.index') }}" class="{{ request()->routeIs('shop.payments.*') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i>
                <span>Payments</span>
            </a>
            <a href="{{ route('shop.reviews.index') }}" class="{{ request()->routeIs('shop.reviews.*') ? 'active' : '' }}">
                <i class="bi bi-star-half"></i>
                <span>Reviews</span>
            </a>
            <a href="{{ route('shop.profile.edit') }}" class="{{ request()->routeIs('shop.profile.*') ? 'active' : '' }}">
                <i class="bi bi-shop-window"></i>
                <span>Shop Profile</span>
            </a>
        </nav>

        <div class="sidebar-footer mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Log Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="content-area flex-fill d-flex flex-column">
        <!-- Top Bar -->
        <header class="topbar">
            <div class="topbar-left d-flex align-items-center">
                <button class="sidebar-toggle d-lg-none me-3" type="button">
                    <i class="bi bi-list fs-3"></i>
                </button>
                <img src="{{ asset('images/BarberTime_logo.svg') }}" class="sidebar-brand-logo d-lg-none me-2" alt="BarberTime">
                <div class="d-none d-md-block">
                    <h5 class="mb-0 fw-bold text-dark">@yield('title', 'Dashboard')</h5>
                    <small class="text-muted">{{ date('d M Y') }}</small>
                </div>
            </div>
            <div class="topbar-right">
                <div class="user-info d-flex align-items-center gap-2">
                    <span>{{ auth()->user()->name ?? 'Shop' }}</span>
                    <div class="dropdown">
                        <a href="#" class="text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-4"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><a class="dropdown-item" href="{{ route('shop.profile.edit') }}">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="content-body flex-fill">
            @yield('content')
        </main>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
