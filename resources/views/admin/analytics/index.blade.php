@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="admin-title mb-1">Analytics</h2>
            <p class="admin-text-muted mb-0">Overview of bookings, revenue and shop performance.</p>
        </div>
        <span class="admin-text-muted small">Last 12 months</span>
    </div>

    {{-- summary cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-blue">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div>
                    <p class="admin-text-muted small mb-0">Total Bookings</p>
                    <h4 class="mb-0 fw-bold" style="color:var(--text-primary);">{{ $totalBookings }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-green">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <p class="admin-text-muted small mb-0">Completed</p>
                    <h4 class="mb-0 fw-bold" style="color:var(--text-primary);">{{ $completedBookings }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-red">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div>
                    <p class="admin-text-muted small mb-0">Cancelled</p>
                    <h4 class="mb-0 fw-bold" style="color:var(--text-primary);">{{ $cancelledBookings }}</h4>
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
                </div>
            </div>
        </div>
    </div>

    {{-- bookings chart + revenue chart --}}
    <div class="row g-3 mb-4">

        {{-- bookings per month --}}
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-bar-chart me-2 text-primary"></i>Bookings Per Month</h5>
                </div>
                <div class="admin-card-body">
                    <canvas id="bookingsChart" height="120"></canvas>
                </div>
            </div>
        </div>

        {{-- revenue per month --}}
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-graph-up me-2 text-primary"></i>Revenue Per Month</h5>
                </div>
                <div class="admin-card-body">
                    <canvas id="revenueChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- booking status + payment methods --}}
    <div class="row g-3 mb-4">

        {{-- booking status breakdown --}}
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-pie-chart me-2 text-primary"></i>Booking Status Breakdown</h5>
                </div>
                <div class="admin-card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <canvas id="statusChart" height="160"></canvas>
                        </div>
                        <div class="col-6">
                            @foreach(['completed' => 'badge-active', 'pending' => 'badge-pending', 'cancelled' => 'badge-suspended', 'confirmed' => 'badge-info'] as $status => $badge)
                                @if(isset($bookingStatuses[$status]))
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="{{ $badge }}">{{ ucfirst($status) }}</span>
                                        <span class="fw-bold small" style="color:var(--text-primary);">
                                            {{ $bookingStatuses[$status] }}
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- payment method breakdown --}}
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-credit-card me-2 text-primary"></i>Payment Methods</h5>
                </div>
                <div class="admin-card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <canvas id="paymentChart" height="160"></canvas>
                        </div>
                        <div class="col-6">
                            @foreach($paymentMethods as $method => $count)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge-info">{{ ucfirst($method) }}</span>
                                    <span class="fw-bold small" style="color:var(--text-primary);">{{ $count }}</span>
                                </div>
                            @endforeach
                            @if($paymentMethods->isEmpty())
                                <p class="admin-text-muted small mb-0">No payment data yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- top shops --}}
    <div class="row g-3">

        {{-- top shops by bookings --}}
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-trophy me-2 text-primary"></i>Top Shops by Bookings</h5>
                </div>
                <div class="admin-card-body">
                    @forelse($topShopsByBookings as $index => $shop)
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="icon-box icon-box-blue"
                                     style="width:32px; height:32px; font-size:0.85rem; font-weight:700;">
                                    {{ $index + 1 }}
                                </div>
                                <p class="mb-0 small fw-medium" style="color:var(--text-primary);">
                                    {{ $shop->name }}
                                </p>
                            </div>
                            <span class="cat-count-badge">{{ $shop->bookings_count }} bookings</span>
                        </div>
                        {{-- progress bar --}}
                        @if($topShopsByBookings->first()->bookings_count > 0)
                            <div class="progress mb-3" style="height:6px; background:var(--border);">
                                <div class="progress-bar" style="width:{{ ($shop->bookings_count / $topShopsByBookings->first()->bookings_count) * 100 }}%; background:var(--primary);"></div>
                            </div>
                        @endif
                    @empty
                        <p class="admin-text-muted small mb-0">No booking data yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- top shops by rating --}}
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-star me-2 text-primary"></i>Top Shops by Rating</h5>
                </div>
                <div class="admin-card-body">
                    @forelse($topShopsByRating as $index => $shop)
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="icon-box icon-box-amber"
                                     style="width:32px; height:32px; font-size:0.85rem; font-weight:700;">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="mb-0 small fw-medium" style="color:var(--text-primary);">
                                        {{ $shop->name }}
                                    </p>
                                    <small class="admin-text-muted">{{ $shop->total_reviews }} reviews</small>
                                </div>
                            </div>
                            <span class="cat-count-badge">
                                <i class="bi bi-star-fill text-warning me-1" style="font-size:0.7rem;"></i>
                                {{ number_format($shop->average_rating, 1) }}
                            </span>
                        </div>
                        {{-- rating bar --}}
                        <div class="progress mb-3" style="height:6px; background:var(--border);">
                            <div class="progress-bar" style="width:{{ ($shop->average_rating / 5) * 100 }}%; background:#f59e0b;"></div>
                        </div>
                    @empty
                        <p class="admin-text-muted small mb-0">No rating data yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // bookings per month chart
    var bookingData = @json($bookingsByMonth);
    new Chart(document.getElementById('bookingsChart'), {
        type: 'bar',
        data: {
            labels: bookingData.map(d => d.month),
            datasets: [{
                label: 'Bookings',
                data: bookingData.map(d => d.total),
                backgroundColor: 'rgba(59,130,246,0.15)',
                borderColor: '#3b82f6',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });

    // revenue per month chart
    var revenueData = @json($revenueByMonth);
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: revenueData.map(d => d.month),
            datasets: [{
                label: 'Revenue (Rs)',
                data: revenueData.map(d => d.total),
                backgroundColor: 'rgba(16,185,129,0.1)',
                borderColor: '#10b981',
                borderWidth: 2,
                pointBackgroundColor: '#10b981',
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });

    // booking status pie chart
    var statusData = @json($bookingStatuses);
    var statusLabels = Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1));
    var statusValues = Object.values(statusData);
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusValues,
                backgroundColor: ['#10b981', '#3b82f6', '#ef4444', '#f59e0b'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            cutout: '70%',
        }
    });

    // payment method pie chart
    var paymentData = @json($paymentMethods);
    var paymentLabels = Object.keys(paymentData).map(s => s.charAt(0).toUpperCase() + s.slice(1));
    var paymentValues = Object.values(paymentData);
    new Chart(document.getElementById('paymentChart'), {
        type: 'doughnut',
        data: {
            labels: paymentLabels,
            datasets: [{
                data: paymentValues,
                backgroundColor: ['#3b82f6', '#f59e0b', '#10b981'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            cutout: '70%',
        }
    });
</script>

@endsection
