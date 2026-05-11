@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    {{-- page header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="admin-title mb-1">Payment & Revenue</h2>
            <p class="admin-text-muted mb-0">
                Track all payments and earnings across shops
                <span class="ms-2 badge bg-primary bg-opacity-10 text-primary"
                    style="font-size:0.75rem; font-weight:500;">
                    Commission Rate: {{ $commissionRate }}%
                </span>
            </p>
        </div>
        <span class="admin-text-muted small">{{ now()->format('d M Y') }}</span>
    </div>

    {{-- summary cards row 1 — revenue breakdown --}}
    <div class="row g-3 mb-3">

        <div class="col-md-4">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-blue">
                    <i class="bi bi-receipt"></i>
                </div>
                <div>
                    <p class="admin-text-muted small mb-0">Total Revenue</p>
                    <h4 class="mb-0 fw-bold" style="color:var(--text-primary);">
                        Rs {{ number_format($totalRevenue) }}
                    </h4>
                    <small class="admin-text-muted">Completed + paid bookings</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-green">
                    <i class="bi bi-building"></i>
                </div>
                <div>
                    <p class="admin-text-muted small mb-0">Platform Revenue</p>
                    <h4 class="mb-0 fw-bold" style="color:var(--text-primary);">
                        Rs {{ number_format($platformRevenue) }}
                    </h4>
                    <small class="admin-text-muted">{{ $commissionRate }}% commission cut</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-amber">
                    <i class="bi bi-shop"></i>
                </div>
                <div>
                    <p class="admin-text-muted small mb-0">Shop Earnings</p>
                    <h4 class="mb-0 fw-bold" style="color:var(--text-primary);">
                        Rs {{ number_format($totalShopEarnings) }}
                    </h4>
                    <small class="admin-text-muted">After commission deduction</small>
                </div>
            </div>
        </div>

    </div>

    {{-- summary cards row 2 — payment method breakdown --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-amber">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div>
                    <p class="admin-text-muted small mb-0">Cash Payments</p>
                    <h5 class="mb-0 fw-bold" style="color:var(--text-primary);">
                        Rs {{ number_format($cashTotal) }}
                    </h5>
                    <small class="admin-text-muted">COD collected</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-blue">
                    <i class="bi bi-phone"></i>
                </div>
                <div>
                    <p class="admin-text-muted small mb-0">Online Payments</p>
                    <h5 class="mb-0 fw-bold" style="color:var(--text-primary);">
                        Rs {{ number_format($onlineTotal) }}
                    </h5>
                    <small class="admin-text-muted">Khalti collected</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-red">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div>
                    <p class="admin-text-muted small mb-0">Fine Compensation</p>
                    <h5 class="mb-0 fw-bold" style="color:var(--text-primary);">
                        Rs {{ number_format($fineCompensation, 2) }}
                    </h5>
                    <small class="admin-text-muted">Paid fines credited to shops</small>
                </div>
            </div>
        </div>

    </div>

    {{-- filters --}}
    <div class="admin-card mb-4">
        <div class="admin-card-header">
            <h5><i class="bi bi-funnel me-2 text-primary"></i>Filters</h5>
            @if(request()->hasAny(['date_from','date_to','shop_id','payment_method','payment_for','booking_status']))
                <a href="{{ route('admin.payments.index') }}" class="admin-text-muted small">Clear filters</a>
            @endif
        </div>
        <div class="admin-card-body">
            <form method="GET" action="{{ route('admin.payments.index') }}">
                <div class="row g-3">

                    <div class="col-md-2">
                        <label class="form-label small admin-text-muted">Date From</label>
                        <input type="date" name="date_from" class="form-control form-control-sm"
                            value="{{ request('date_from') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small admin-text-muted">Date To</label>
                        <input type="date" name="date_to" class="form-control form-control-sm"
                            value="{{ request('date_to') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small admin-text-muted">Shop</label>
                        <select name="shop_id" class="form-select form-select-sm">
                            <option value="">All Shops</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}"
                                    {{ request('shop_id') == $shop->id ? 'selected' : '' }}>
                                    {{ $shop->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small admin-text-muted">Payment Method</label>
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
                        <label class="form-label small admin-text-muted">Payment For</label>
                        <select name="payment_for" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="booking" {{ request('payment_for') === 'booking' ? 'selected' : '' }}>Booking</option>
                            <option value="fine" {{ request('payment_for') === 'fine' ? 'selected' : '' }}>Fine</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small admin-text-muted">Booking Status</label>
                        <select name="booking_status" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="pending" {{ request('booking_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('booking_status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ request('booking_status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('booking_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary px-4">
                            <i class="bi bi-search me-1"></i> Apply
                        </button>
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-secondary px-4">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- payment records table --}}
    <div class="admin-card mb-4">
        <div class="admin-card-header">
            <h5><i class="bi bi-table me-2 text-primary"></i>Payment Records</h5>
            <span class="admin-text-muted small">{{ $paymentRecords->total() }} records</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="admin-table-head">
                    <tr>
                        <th class="ps-3 py-3">SN</th>
                        <th class="py-3">Customer</th>
                        <th class="py-3">Shop</th>
                        <th class="py-3">Service</th>
                        <th class="py-3">Amount</th>
                        <th class="py-3">Commission</th>
                        <th class="py-3">Shop Earnings</th>
                        <th class="py-3">Method</th>
                        <th class="py-3">Payment</th>
                        <th class="py-3">Booking</th>
                        <th class="py-3">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentRecords as $booking)
                        @php
                            $isFinePaid = $booking->status === 'cancelled'
                                && (float) ($booking->cancellation_fine ?? 0) > 0
                                && (bool) $booking->fine_paid;

                            $effectiveRate = ($booking->commission_rate ?? 0) > 0
                                ? (float) $booking->commission_rate
                                : (float) $commissionRate;

                            $effectiveCommission = ($booking->commission_amount ?? 0) > 0
                                ? (float) $booking->commission_amount
                                : round(((float) ($booking->final_amount ?? 0)) * ($effectiveRate / 100), 2);

                            $effectiveShopEarnings = ($booking->shop_earnings ?? 0) > 0
                                ? (float) $booking->shop_earnings
                                : round(((float) ($booking->final_amount ?? 0)) - $effectiveCommission, 2);
                        @endphp
                        <tr>
                            <td class="ps-3 small admin-text-muted">
                                {{ ($paymentRecords->currentPage() - 1) * $paymentRecords->perPage() + $loop->iteration }}
                            </td>

                            <td>
                                <p class="mb-0 fw-medium small" style="color:var(--text-primary);">
                                    {{ $booking->user->name ?? '—' }}
                                </p>
                            </td>

                            <td class="small admin-text-muted">
                                {{ $booking->barberShop->name ?? '—' }}
                            </td>

                            <td class="small admin-text-muted">
                                {{ $booking->service->name ?? '—' }}
                            </td>

                            <td class="small fw-medium" style="color:var(--text-primary);">
                                Rs {{ number_format($isFinePaid ? ($booking->cancellation_fine ?? 0) : ($booking->final_amount ?? 0), 2) }}
                            </td>

                            {{-- only show commission for completed paid bookings --}}
                            <td class="small" style="color:#16a34a;">
                                @if($isFinePaid)
                                    Rs 0.00
                                    <span class="admin-text-muted">(0.00%)</span>
                                @elseif($booking->status === 'completed' && $booking->payment_status === 'paid')
                                    Rs {{ number_format($effectiveCommission, 2) }}
                                    <span class="admin-text-muted">({{ number_format($effectiveRate, 2) }}%)</span>
                                @else
                                    <span class="admin-text-muted">—</span>
                                @endif
                            </td>

                            <td class="small admin-text-muted">
                                @if($isFinePaid)
                                    Rs {{ number_format((float) ($booking->cancellation_fine ?? 0), 2) }}
                                @elseif($booking->status === 'completed' && $booking->payment_status === 'paid')
                                    Rs {{ number_format($effectiveShopEarnings, 2) }}
                                @else
                                    —
                                @endif
                            </td>

                            <td>
                                @if($isFinePaid)
                                    <span class="badge-info">Online (Fine)</span>
                                @elseif($booking->payment_method === 'khalti')
                                    <span class="badge-info">Online</span>
                                @elseif($booking->payment_method === 'cod')
                                    <span class="cat-count-badge">Cash</span>
                                @else
                                    <span class="admin-text-muted small">—</span>
                                @endif
                            </td>

                            <td>
                                @if($isFinePaid || $booking->payment_status === 'paid')
                                    <span class="badge-active">Paid</span>
                                @elseif($booking->payment_status === 'unpaid')
                                    <span class="badge-pending">Unpaid</span>
                                @elseif($booking->payment_status === 'partially_paid')
                                    <span class="badge-info">Partial</span>
                                @elseif($booking->payment_status === 'refunded')
                                    <span class="badge-suspended">Refunded</span>
                                @endif
                            </td>

                            <td>
                                @if($booking->status === 'completed')
                                    <span class="badge-active">Completed</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="badge-info">Confirmed</span>
                                @elseif($booking->status === 'pending')
                                    <span class="badge-pending">Pending</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="badge-suspended">Cancelled</span>
                                @endif
                            </td>

                            <td class="small admin-text-muted">
                                {{ $booking->booking_date->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <p class="admin-text-muted mb-0">No payment records found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- pagination --}}
        @if($paymentRecords->hasPages())
            <div class="admin-card-body pt-0">
                {{ $paymentRecords->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
