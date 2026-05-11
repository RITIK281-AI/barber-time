@extends('barbershop.layouts.shop')

@section('content')

<div class="container-fluid px-0">

    {{-- header --}}
    <div class="mb-5">
        <h1 class="page-title fw-bold mb-1">Dashboard</h1>
        <p class="page-subtitle text-muted">
            Welcome back, {{ auth()->user()->name }}! Here's what's happening today.
        </p>
    </div>

    {{-- pending bookings alert --}}
    @if($pendingCount > 0)
        <div class="dash-alert mb-4">
            <i class="bi bi-bell-fill me-2"></i>
            You have <strong>{{ $pendingCount }}</strong> pending
            {{ Str::plural('booking', $pendingCount) }} waiting for action.
            <a href="{{ route('shop.bookings.index') }}" class="dash-alert-link ms-2">View all →</a>
        </div>
    @endif

    {{-- stat cards --}}
    <div class="row g-4 mb-5">

        <div class="col-xl-3 col-md-6">
            <div class="stat-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-header mb-1">Today's Bookings</div>
                        <div class="stat-value">{{ $todayBookings }}</div>
                    </div>
                    <div class="stat-icon-wrap stat-icon-blue">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                </div>
                <div class="stat-note mt-2">Appointments today</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-header mb-1">Monthly Earnings</div>
                        <div class="stat-value">Rs {{ number_format($monthlyEarnings) }}</div>
                    </div>
                    <div class="stat-icon-wrap stat-icon-green">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                </div>
                <div class="stat-note mt-2">From completed bookings</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-header mb-1">Active Barbers</div>
                        <div class="stat-value">{{ $activeBarbers }}</div>
                    </div>
                    <div class="stat-icon-wrap stat-icon-purple">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
                <div class="stat-note mt-2">Available or busy</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-header mb-1">Total Services</div>
                        <div class="stat-value">{{ $totalServices }}</div>
                    </div>
                    <div class="stat-icon-wrap stat-icon-orange">
                        <i class="bi bi-scissors"></i>
                    </div>
                </div>
                <div class="stat-note mt-2">Services offered</div>
            </div>
        </div>

    </div>

    {{-- second row: chart + upcoming + quick stats --}}
    <div class="row g-4 mb-4">

        {{-- weekly bookings chart --}}
        <div class="col-lg-8">
            <div class="stat-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div>
                        <h5 class="fw-bold mb-0">Weekly Bookings</h5>
                        <p class="text-muted small mb-0">Bookings over the last 7 days</p>
                    </div>
                    <a href="{{ route('shop.bookings.index') }}" class="dash-link small">
                        View all <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="mt-4" style="height: 280px; position: relative;">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>
        </div>

        {{-- shop overview side card --}}
        <div class="col-lg-4">
            <div class="stat-card h-100">
                <h5 class="fw-bold mb-1">Shop Overview</h5>
                <p class="text-muted small mb-4">Your shop at a glance</p>

                <div class="overview-row">
                    <span class="overview-label">Status</span>
                    @if($shop->status === 'approved')
                        <span class="shop-status-badge status-approved">
                            <i class="bi bi-check-circle me-1"></i>Approved
                        </span>
                    @elseif($shop->status === 'pending')
                        <span class="shop-status-badge status-pending">
                            <i class="bi bi-hourglass-split me-1"></i>Pending
                        </span>
                    @else
                        <span class="shop-status-badge status-suspended">
                            <i class="bi bi-slash-circle me-1"></i>{{ ucfirst($shop->status) }}
                        </span>
                    @endif
                </div>

                <div class="overview-row">
                    <span class="overview-label">Rating</span>
                    <span class="overview-value">
                        <i class="bi bi-star-fill text-warning me-1"></i>
                        {{ number_format($averageRating, 1) }}
                        <span class="text-muted small">({{ $shop->total_reviews }})</span>
                    </span>
                </div>

                <div class="overview-row">
                    <span class="overview-label">All-time Bookings</span>
                    <span class="overview-value">{{ $totalBookings }}</span>
                </div>

                <div class="overview-row">
                    <span class="overview-label">Pending Now</span>
                    <span class="overview-value text-warning fw-bold">{{ $pendingCount }}</span>
                </div>

                <div class="overview-row">
                    <span class="overview-label">District</span>
                    <span class="overview-value">{{ $shop->district ?? '—' }}</span>
                </div>

                <div class="mt-4">
                    <a href="{{ route('shop.profile.edit') }}" class="btn-shop-secondary w-100 justify-content-center">
                        <i class="bi bi-pencil me-1"></i> Edit Shop Profile
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- upcoming bookings table --}}
    <div class="row g-4">
        <div class="col-12">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-0">Upcoming Bookings</h5>
                        <p class="text-muted small mb-0">Next confirmed and pending appointments</p>
                    </div>
                    <a href="{{ route('shop.bookings.index') }}" class="dash-link small">
                        View all <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                @forelse($upcomingBookings as $booking)
                    <div class="upcoming-row">
                        {{-- customer avatar initial --}}
                        <div class="upcoming-avatar">
                            {{ strtoupper(substr($booking->user->name ?? 'C', 0, 1)) }}
                        </div>

                        {{-- customer and barber info --}}
                        <div class="upcoming-info flex-fill">
                            <div class="fw-semibold" style="color:var(--text-primary);">
                                {{ $booking->user->name ?? 'Customer' }}
                            </div>
                            <div class="small text-muted">
                                <i class="bi bi-scissors me-1"></i>
                                {{ $booking->barber->name ?? 'Any barber' }}
                            </div>
                        </div>

                        {{-- date and time --}}
                        <div class="upcoming-time text-end">
                            <div class="fw-medium" style="color:var(--text-primary);">
                                {{ $booking->booking_date->format('d M Y') }}
                            </div>
                            <div class="small text-muted">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                            </div>
                        </div>

                        {{-- status badge --}}
                        <div class="ms-3">
                            @if($booking->status === 'confirmed')
                                <span class="dash-badge badge-confirmed">Confirmed</span>
                            @elseif($booking->status === 'pending')
                                <span class="dash-badge badge-pending">Pending</span>
                            @else
                                <span class="dash-badge badge-other">{{ ucfirst($booking->status) }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-x fs-1 opacity-50 mb-3 d-block"></i>
                        No upcoming bookings
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

{{-- Chart.js via CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('weeklyChart').getContext('2d');

    // data passed from controller
    const labels = @json($weeklyData['labels']);
    const counts = @json($weeklyData['counts']);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Bookings',
                data: counts,
                backgroundColor: 'rgba(37, 99, 235, 0.15)',
                borderColor: 'rgba(37, 99, 235, 0.85)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y} bookings`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: '#64748b' },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    ticks: { color: '#64748b' },
                    grid: { display: false }
                }
            }
        }
    });
</script>

@endsection
