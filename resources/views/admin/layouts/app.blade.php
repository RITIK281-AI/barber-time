<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrimTime Admin Panel</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom Admin Theme -->
    <link rel="stylesheet" href="{{ asset('site/css/admin-theme.css') }}">
</head>

<body class="admin-layout">

    <div class="d-flex" style="min-height: 100vh;">

        <!-- Sidebar -->
        <aside class="admin-sidebar" style="height: 100vh; position: sticky; top: 0; z-index: 1040;">
            <div class="sidebar-brand-area p-4 pb-2">
                <div class="admin-brand-wrapper d-flex align-items-center gap-2">
                    <img src="{{ asset('images/TrimTime_logo.svg') }}" class="admin-logo" alt="TrimTime" style="height: 38px; width: auto;">
                    <span class="admin-brand fw-bold fs-5 text-white">TrimTime Admin</span>
                </div>
            </div>

            <div class="sidebar-profile mt-3">
                <div class="profile-avatar">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <div class="profile-info">
                    <div class="profile-name">{{ auth()->user()->name ?? 'Super Admin' }}</div>
                    <div class="profile-email">{{ auth()->user()->email ?? 'trimtime66@gmail.com' }}</div>
                    <div class="profile-role">System Administrator</div>
                </div>
            </div>

            <ul class="admin-menu mt-2">
                <li>
                    <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.barbershops.*') ? 'active' : '' }}" href="{{ route('admin.barbershops.index') }}">
                        <i class="bi bi-shop"></i>
                        <span>Barber Shops</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.partners.*') ? 'active' : '' }}" href="{{ route('admin.partners.index') }}">
                        <i class="bi bi-shop"></i>
                        <span>Partner Applications</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.barbers.*') ? 'active' : '' }}" href="{{ route('admin.barbers.index') }}">
                        <i class="bi bi-scissors"></i>
                        <span>Barbers</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people"></i>
                        <span>Customers</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                        <i class="bi bi-calendar2-check"></i>
                        <span>Bookings</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.category.*') ? 'active' : '' }}" href="{{ route('admin.category.index') }}">
                        <i class="bi bi-gear"></i>
                        <span>Categories</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
                        <i class="bi bi-star"></i>
                        <span>Reviews</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}" href="{{ route('admin.analytics.index') }}">
                        <i class="bi bi-graph-up"></i>
                        <span>Analytics</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                        <i class="bi bi-cash-stack"></i>
                        <span>Payments</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.contact.*') ? 'active' : '' }}" href="{{ route('admin.contact.index') }}">
                        <i class="bi bi-envelope"></i>
                        <span>Contact Messages</span>
                    </a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" href="{{ route('admin.profile.edit') }}">
                        <i class="bi bi-person-circle"></i>
                        <span>My Profile</span>
                    </a>
                </li>
            </ul>

            <div class="admin-logout">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area Wrapper -->
        <div class="content-area flex-fill d-flex flex-column" style="min-width: 0;">

            <!-- Top Navbar -->
            <nav class="admin-navbar">
                <div class="topbar-left d-flex align-items-center">
                <button class="sidebar-toggle d-lg-none me-3" type="button">
                    <i class="bi bi-list fs-3"></i>
                </button>
                    <img src="{{ asset('images/TrimTime_logo.svg') }}" class="admin-top-logo d-lg-none me-2" alt="TrimTime">
                    <div class="d-none d-md-block">
                        <h5 class="mb-0 fw-bold text-dark">@yield('title', 'Dashboard')</h5>
                        <small class="text-muted">{{ date('d M Y') }}</small>
                    </div>
                </div>

                <div class="admin-nav-right">
                    <span class="admin-user">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <div class="dropdown">
                        <a href="#" class="text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-4"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}">Profile</a></li>
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
            </nav>

            <!-- Main Content -->
            <main class="admin-content flex-fill">
                @yield('content')
            </main>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
