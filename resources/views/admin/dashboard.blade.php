@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="admin-title mb-1">Dashboard Overview</h2>
            <p class="admin-text-muted mb-0">Welcome back, {{ auth()->user()->name }}!</p>
        </div>
        <span class="admin-text-muted small">{{ now()->format('d M Y') }}</span>
    </div>

    {{-- stat cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-blue">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div>
                    <p class="admin-text-muted small mb-0">Total Bookings</p>
                    <h4 class="mb-0 fw-bold" style="color:var(--text-primary);">{{ $totalBookings }}</h4>
                    <small class="admin-text-muted">{{ $completedBookings }} completed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-green">
                    <i class="bi bi-shop"></i>
                </div>
                <div>
                    <p class="admin-text-muted small mb-0">Barber Shops</p>
                    <h4 class="mb-0 fw-bold" style="color:var(--text-primary);">{{ $totalShops }}</h4>
                    <small class="admin-text-muted">{{ $approvedShops }} approved &bull; {{ $pendingShops }} pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-amber">
                    <i class="bi bi-currency-rupee"></i>
                </div>
                <div>
                    <p class="admin-text-muted small mb-0">Total Revenue</p>
                    <h4 class="mb-0 fw-bold" style="color:var(--text-primary);">Rs {{ number_format($totalRevenue, 0) }}</h4>
                    <small class="admin-text-muted">From completed bookings</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-red">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <p class="admin-text-muted small mb-0">Total Users</p>
                    <h4 class="mb-0 fw-bold" style="color:var(--text-primary);">{{ $totalUsers }}</h4>
                    <small class="admin-text-muted">{{ $totalBarbers }} barbers</small>
                </div>
            </div>
        </div>
    </div>

    {{-- chart + top shops --}}
    <div class="row g-3 mb-4">

        {{-- booking trend chart --}}
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-graph-up me-2 text-primary"></i>Booking Trends</h5>
                    <span class="admin-text-muted small">Last 6 months</span>
                </div>
                <div class="admin-card-body">
                    <canvas id="bookingChart" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- top rated shops --}}
        <div class="col-lg-4">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h5><i class="bi bi-star me-2 text-primary"></i>Top Rated Shops</h5>
                </div>
                <div class="admin-card-body">
                    @forelse($topShops as $shop)
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="icon-box icon-box-amber" style="width:34px; height:34px; font-size:0.9rem;">
                                    <i class="bi bi-shop"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-medium small" style="color:var(--text-primary);">{{ $shop->name }}</p>
                                    <small class="admin-text-muted">{{ $shop->total_reviews }} reviews</small>
                                </div>
                            </div>
                            <span class="cat-count-badge">
                                <i class="bi bi-star-fill text-warning me-1" style="font-size:0.7rem;"></i>
                                {{ number_format($shop->average_rating, 1) }}
                            </span>
                        </div>
                    @empty
                        <p class="admin-text-muted small mb-0">No rated shops yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- recent bookings + recent reviews --}}
    <div class="row g-3">

        {{-- recent bookings --}}
        <div class="col-lg-7">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-clock-history me-2 text-primary"></i>Recent Bookings</h5>
                    <a href="{{ route('admin.dashboard') }}" class="admin-text-muted small">View all</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="admin-table-head">
                            <tr>
                                <th class="ps-3 py-3">Customer</th>
                                <th class="py-3">Shop</th>
                                <th class="py-3">Date</th>
                                <th class="py-3">Amount</th>
                                <th class="py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings as $booking)
                                <tr>
                                    <td class="ps-3">
                                        <p class="mb-0 fw-medium small" style="color:var(--text-primary);">{{ $booking->user->name ?? '—' }}</p>
                                    </td>
                                    <td class="small admin-text-muted">{{ $booking->barberShop->name ?? '—' }}</td>
                                    <td class="small admin-text-muted">{{ $booking->booking_date->format('d M Y') }}</td>
                                    <td class="small fw-medium" style="color:var(--text-primary);">
                                        Rs {{ number_format($booking->final_price ?? $booking->total_price, 0) }}
                                    </td>
                                    <td>
                                        @if($booking->status === 'completed')
                                            <span class="badge-active">Completed</span>
                                        @elseif($booking->status === 'pending')
                                            <span class="badge-pending">Pending</span>
                                        @elseif($booking->status === 'cancelled')
                                            <span class="badge-suspended">Cancelled</span>
                                        @else
                                            <span class="badge-info">{{ ucfirst($booking->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <p class="admin-text-muted mb-0">No bookings yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- recent reviews --}}
        <div class="col-lg-5">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-chat-square-text me-2 text-primary"></i>Recent Reviews</h5>
                    <a href="{{ route('admin.reviews.index') }}" class="admin-text-muted small">View all</a>
                </div>
                <div class="admin-card-body">
                    @forelse($recentReviews as $review)
                        <div class="mb-3 pb-3" style="border-bottom:1px solid var(--border);">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <p class="mb-0 fw-medium small" style="color:var(--text-primary);">
                                    {{ $review->user->name ?? 'Customer' }}
                                </p>
                                <div class="d-flex gap-2">
                                    @if($review->shop_rating)
                                        <span class="cat-count-badge" style="font-size:0.75rem;">
                                            <i class="bi bi-shop" style="font-size:0.7rem;"></i>
                                            {{ $review->shop_rating }}/5
                                        </span>
                                    @endif
                                    @if($review->barber_rating)
                                        <span class="badge-info" style="font-size:0.75rem;">
                                            <i class="bi bi-scissors" style="font-size:0.7rem;"></i>
                                            {{ $review->barber_rating }}/5
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <p class="admin-text-muted small mb-1">{{ $review->barberShop->name ?? '—' }}</p>
                            @if($review->comment)
                                <p class="small mb-0" style="color:var(--text-secondary);">
                                    "{{ Str::limit($review->comment, 80) }}"
                                </p>
                            @endif
                        </div>
                    @empty
                        <p class="admin-text-muted small mb-0">No reviews yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

{{-- booking trend chart script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // prepare data from controller
    var trendData = @json($bookingTrend);

    var labels = trendData.map(function(d) {
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        return months[d.month - 1];
    });

    var totals = trendData.map(function(d) {
        return d.total;
    });

    var ctx = document.getElementById('bookingChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Bookings',
                data: totals,
                backgroundColor: 'rgba(59, 130, 246, 0.15)',
                borderColor: '#3b82f6',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>

@endsection
