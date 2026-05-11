@extends('barbershop.layouts.shop')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title fw-bold mb-1">Booking Details</h1>
            <p class="page-subtitle text-muted mb-0">Booking #{{ $booking->id }} summary</p>
        </div>
        <a href="{{ route('shop.bookings.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Bookings
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="stat-card mb-4">
                <h5 class="fw-bold mb-3">Appointment Information</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">Customer</label>
                        <p class="mb-0 fw-medium">{{ $booking->user?->name ?? '—' }}</p>
                        <p class="mb-0 small text-muted">{{ $booking->user?->email ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">Barber</label>
                        <p class="mb-0 fw-medium">{{ $booking->barber?->name ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">Date</label>
                        <p class="mb-0">{{ optional($booking->booking_date)->format('d M Y') ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">Time Slot</label>
                        <p class="mb-0">
                            {{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('h:i A') : '—' }}
                            -
                            {{ $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('h:i A') : '—' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">Primary Service</label>
                        <p class="mb-0">{{ $booking->service?->name ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1">Payment Method</label>
                        <p class="mb-0 text-uppercase">{{ $booking->payment_method ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card mb-4">
                <h5 class="fw-bold mb-3">Booked Services</h5>
                @if($booking->items->count())
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead style="background:var(--bg-secondary, #f8fafc);">
                                <tr>
                                    <th class="small text-muted">Service</th>
                                    <th class="small text-muted text-end">Price</th>
                                    <th class="small text-muted text-end">Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->items as $item)
                                    <tr>
                                        <td>{{ $item->service_name ?? $item->service?->name ?? 'Service' }}</td>
                                        <td class="text-end">Rs {{ number_format($item->service_price ?? $item->service?->price ?? 0, 2) }}</td>
                                        <td class="text-end">{{ $item->service_duration ?? $item->service?->duration ?? 0 }} mins</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead style="background:var(--bg-secondary, #f8fafc);">
                                <tr>
                                    <th class="small text-muted">Service</th>
                                    <th class="small text-muted text-end">Price</th>
                                    <th class="small text-muted text-end">Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $booking->service?->name ?? 'Service' }}</td>
                                    <td class="text-end">Rs {{ number_format($booking->final_amount ?? $booking->service?->price ?? 0, 2) }}</td>
                                    <td class="text-end">{{ $booking->service?->duration ?? 0 }} mins</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>

        <div class="col-lg-4">
            <div class="stat-card mb-4">
                <h5 class="fw-bold mb-3">Status</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="small text-muted">Booking Status</span>
                    <span>
                        @if($booking->status === 'completed')
                            <span class="dash-badge badge-confirmed">Completed</span>
                        @elseif($booking->status === 'confirmed')
                            <span class="dash-badge badge-other">Confirmed</span>
                        @elseif($booking->status === 'pending')
                            <span class="dash-badge badge-pending">Pending</span>
                        @elseif($booking->status === 'cancelled')
                            <span class="dash-badge" style="background:#fee2e2;color:#dc2626;">Cancelled</span>
                        @else
                            <span class="dash-badge" style="background:#e2e8f0;color:#334155;">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
                        @endif
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="small text-muted">Payment Status</span>
                    <span>
                        @if($booking->payment_status === 'paid')
                            <span class="dash-badge badge-confirmed">Paid</span>
                        @elseif($booking->payment_status === 'partially_paid')
                            <span class="dash-badge badge-other">Partial</span>
                        @else
                            <span class="dash-badge badge-pending">Unpaid</span>
                        @endif
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="small text-muted">Cancelled By</span>
                    <span class="small text-capitalize">{{ str_replace('_', ' ', $booking->cancelled_by ?? '—') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="small text-muted">Cancellation Type</span>
                    <span class="small text-capitalize">{{ str_replace('_', ' ', $booking->cancellation_type ?? '—') }}</span>
                </div>
            </div>

            <div class="stat-card mb-4">
                <h5 class="fw-bold mb-3">Amount Breakdown</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="small text-muted">Original Amount</span>
                    <span>Rs {{ number_format($booking->original_amount ?? 0, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="small text-muted">Discount</span>
                    <span>Rs {{ number_format($booking->discount_amount ?? 0, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="small text-muted">Final Amount</span>
                    <span class="fw-semibold">Rs {{ number_format($booking->final_amount ?? $booking->total_price ?? 0, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="small text-muted">Cancellation Fine</span>
                    <span>Rs {{ number_format($booking->cancellation_fine ?? 0, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="small text-muted">Fine Paid</span>
                    <span>{{ $booking->fine_paid ? 'Yes' : 'No' }}</span>
                </div>
            </div>

            @if($booking->review)
                <div class="stat-card">
                    <h5 class="fw-bold mb-3">Customer Review</h5>
                    <p class="mb-1 fw-medium">Rating: {{ number_format($booking->review->barber_rating ?? 0, 1) }}/5</p>
                    <p class="text-muted mb-0">{{ $booking->review->comment ?? 'No written review.' }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
