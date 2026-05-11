@extends('barbershop.layouts.shop')

@section('content')
<div class="container-fluid px-0">

    {{-- page header --}}
    <div class="mb-5">
        <h1 class="page-title fw-bold mb-1">Payment & Revenue</h1>
        <p class="page-subtitle text-muted">Track your shop earnings and payment records</p>
    </div>

    <div class="row g-4 mb-4">

        <div class="col-xl-4 col-md-6 col-sm-6">
            <div class="stat-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-header mb-1">Today Earnings</div>
                        <div class="stat-value">Rs {{ number_format($todayRevenue) }}</div>
                    </div>
                    <div class="stat-icon-wrap stat-icon-green">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
                <div class="stat-note mt-2">After commission deduction</div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 col-sm-6">
            <div class="stat-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-header mb-1">Monthly Earnings</div>
                        <div class="stat-value">Rs {{ number_format($monthlyRevenue) }}</div>
                    </div>
                    <div class="stat-icon-wrap stat-icon-blue">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                </div>
                <div class="stat-note mt-2">After commission deduction</div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 col-sm-6">
            <div class="stat-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-header mb-1">Total Earnings</div>
                        <div class="stat-value">Rs {{ number_format($totalEarnings) }}</div>
                    </div>
                    <div class="stat-icon-wrap stat-icon-blue">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                </div>
                <div class="stat-note mt-2">All-time after commission deduction</div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 col-sm-6">
            <div class="stat-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-header mb-1">Cash Earnings</div>
                        <div class="stat-value">Rs {{ number_format($cashCollected) }}</div>
                    </div>
                    <div class="stat-icon-wrap stat-icon-orange">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
                <div class="stat-note mt-2">COD after commission</div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 col-sm-6">
            <div class="stat-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-header mb-1">Online Earnings</div>
                        <div class="stat-value">Rs {{ number_format($onlineCollected) }}</div>
                    </div>
                    <div class="stat-icon-wrap stat-icon-purple">
                        <i class="bi bi-phone"></i>
                    </div>
                </div>
                <div class="stat-note mt-2">Khalti after commission</div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 col-sm-6">
            <div class="stat-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-header mb-1">Fine Compensation</div>
                        <div class="stat-value">Rs {{ number_format($fineCompensation, 2) }}</div>
                    </div>
                    <div class="stat-icon-wrap stat-icon-red">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="stat-note mt-2">Paid fines credited to your shop</div>
            </div>
        </div>
    </div>

    {{-- filters --}}
    <div class="stat-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-funnel me-2"></i>Filters
            </h5>
            @if(request()->hasAny(['date_from','date_to','service_id','barber_id','payment_method','payment_for']))
                <a href="{{ route('shop.payments.index') }}" class="small text-muted">Clear filters</a>
            @endif
        </div>

        <form method="GET" action="{{ route('shop.payments.index') }}">
            <div class="row g-3">

                <div class="col-md-2">
                    <label class="form-label small text-muted">Date From</label>
                    <input type="date" name="date_from" class="form-control form-control-sm"
                        value="{{ request('date_from') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small text-muted">Date To</label>
                    <input type="date" name="date_to" class="form-control form-control-sm"
                        value="{{ request('date_to') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small text-muted">Service</label>
                    <select name="service_id" class="form-select form-select-sm">
                        <option value="">All Services</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}"
                                {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small text-muted">Barber</label>
                    <select name="barber_id" class="form-select form-select-sm">
                        <option value="">All Barbers</option>
                        @foreach($barbers as $barber)
                            <option value="{{ $barber->id }}"
                                {{ request('barber_id') == $barber->id ? 'selected' : '' }}>
                                {{ $barber->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small text-muted">Payment Method</label>
                    <select name="payment_method" class="form-select form-select-sm">
                        <option value="">All Methods</option>
                        <option value="khalti" {{ request('payment_method') === 'khalti' ? 'selected' : '' }}>
                            Online (Khalti)
                        </option>
                        <option value="cod" {{ request('payment_method') === 'cod' ? 'selected' : '' }}>
                            Cash (COD)
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small text-muted">Payment For</label>
                    <select name="payment_for" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="booking" {{ request('payment_for') === 'booking' ? 'selected' : '' }}>Booking</option>
                        <option value="fine" {{ request('payment_for') === 'fine' ? 'selected' : '' }}>Fine</option>
                    </select>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary px-4">
                        <i class="bi bi-search me-1"></i> Apply
                    </button>
                    <a href="{{ route('shop.payments.index') }}" class="btn btn-sm btn-outline-secondary px-4">
                        Reset
                    </a>
                </div>

            </div>
        </form>
    </div>

    {{-- payment records table --}}
    <div class="stat-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-0">Payment Records</h5>
                <p class="text-muted small mb-0">{{ $paymentRecords->total() }} records found</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:var(--bg-secondary, #f8fafc);">
                    <tr>
                        <th class="py-3 ps-3 small text-muted fw-semibold">S.N.</th>
                        <th class="py-3 small text-muted fw-semibold">Customer</th>
                        <th class="py-3 small text-muted fw-semibold">Service</th>
                        <th class="py-3 small text-muted fw-semibold">Barber</th>
                        <th class="py-3 small text-muted fw-semibold">Amount</th>
                        <th class="py-3 small text-muted fw-semibold">Method</th>
                        <th class="py-3 small text-muted fw-semibold">Payment</th>
                        <th class="py-3 small text-muted fw-semibold">Booking</th>
                        <th class="py-3 small text-muted fw-semibold">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentRecords as $index => $booking)
                        @php
                            $isFinePaid = $booking->status === 'cancelled'
                                && (float) ($booking->cancellation_fine ?? 0) > 0
                                && (bool) $booking->fine_paid;
                        @endphp
                        <tr>
                            <td class="ps-3 small text-muted">{{ $paymentRecords->firstItem() + $index }}</td>

                            <td>
                                <p class="mb-0 fw-medium small" style="color:var(--text-primary);">
                                    {{ $booking->user->name ?? '—' }}
                                </p>
                            </td>

                            <td class="small text-muted">
                                {{ $booking->service->name ?? '—' }}
                            </td>

                            <td class="small text-muted">
                                {{ $booking->barber->name ?? '—' }}
                            </td>

                            <td class="small fw-medium" style="color:var(--text-primary);">
                                Rs {{ number_format($isFinePaid ? ($booking->cancellation_fine ?? 0) : ($booking->final_amount ?? 0), 2) }}
                            </td>

                            <td>
                                @if($isFinePaid)
                                    <span class="dash-badge badge-confirmed">Online (Fine)</span>
                                @elseif($booking->payment_method === 'khalti')
                                    <span class="dash-badge badge-confirmed">Online</span>
                                @elseif($booking->payment_method === 'cod')
                                    <span class="dash-badge badge-other">Cash</span>
                                @else
                                    <span class="small text-muted">—</span>
                                @endif
                            </td>

                            <td>
                                @if($isFinePaid || $booking->payment_status === 'paid')
                                    <span class="dash-badge badge-confirmed">Paid</span>
                                @else
                                    <span class="dash-badge badge-pending">Unpaid</span>
                                @endif
                            </td>

                            <td>
                                @if($booking->status === 'completed')
                                    <span class="dash-badge badge-confirmed">Completed</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="dash-badge badge-other">Confirmed</span>
                                @elseif($booking->status === 'pending')
                                    <span class="dash-badge badge-pending">Pending</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="dash-badge" style="background:#fee2e2;color:#dc2626;">Cancelled</span>
                                @endif
                            </td>

                            <td class="small text-muted">
                                {{ $booking->booking_date->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="bi bi-inbox fs-2 text-muted opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-0">No payment records found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- pagination --}}
        @if($paymentRecords->hasPages())
            <div class="pt-3 px-3">
                {{ $paymentRecords->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
