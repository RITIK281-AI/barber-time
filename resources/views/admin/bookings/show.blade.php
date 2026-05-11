@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="admin-title mb-1">Booking Details</h2>
            <p class="admin-text-muted mb-0">Booking #{{ $booking->id }} details and payment history</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-card p-4 mb-4">
                <h5 class="mb-3">Appointment Information</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="admin-text-muted small mb-1">Booking Date</p>
                        <p class="mb-0 fw-medium">{{ optional($booking->booking_date)->format('d M Y') ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="admin-text-muted small mb-1">Time Slot</p>
                        <p class="mb-0">
                            {{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('h:i A') : '—' }}
                            -
                            {{ $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('h:i A') : '—' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="admin-text-muted small mb-1">Customer</p>
                        <p class="mb-0 fw-medium">{{ $booking->user?->name ?? '—' }}</p>
                        <p class="mb-0 small admin-text-muted">{{ $booking->user?->email ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="admin-text-muted small mb-1">Barber Shop</p>
                        <p class="mb-0 fw-medium">{{ $booking->barberShop?->name ?? '—' }}</p>
                        <p class="mb-0 small admin-text-muted">{{ $booking->barberShop?->phone ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="admin-text-muted small mb-1">Barber</p>
                        <p class="mb-0">{{ $booking->barber?->name ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="admin-text-muted small mb-1">Primary Service</p>
                        <p class="mb-0">{{ $booking->service?->name ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <div class="admin-card p-4 mb-4">
                <h5 class="mb-3">Booked Services</h5>
                @if($booking->items->count())
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="admin-table-head">
                                <tr>
                                    <th>Service</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Duration</th>
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
                            <thead class="admin-table-head">
                                <tr>
                                    <th>Service</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Duration</th>
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
            <div class="admin-card p-4 mb-4">
                <h5 class="mb-3">Status Summary</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="admin-text-muted">Booking Status</span>
                    <span>
                        @if($booking->status === 'completed')
                            <span class="badge-active">Completed</span>
                        @elseif($booking->status === 'confirmed')
                            <span class="badge-info">Confirmed</span>
                        @elseif($booking->status === 'pending')
                            <span class="badge-pending">Pending</span>
                        @elseif($booking->status === 'no_show')
                            <span class="badge-suspended">No Show</span>
                        @else
                            <span class="badge-suspended">Cancelled</span>
                        @endif
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="admin-text-muted">Payment Status</span>
                    <span>
                        @if($booking->payment_status === 'paid')
                            <span class="badge-active">Paid</span>
                        @elseif($booking->payment_status === 'partially_paid')
                            <span class="badge-info">Partial</span>
                        @else
                            <span class="badge-pending">Unpaid</span>
                        @endif
                    </span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="admin-text-muted">Payment Method</span>
                    <span class="fw-medium">{{ strtoupper($booking->payment_method ?? '—') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="admin-text-muted">Cancelled By</span>
                    <span class="fw-medium text-capitalize">{{ str_replace('_', ' ', $booking->cancelled_by ?? '—') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="admin-text-muted">Cancellation Type</span>
                    <span class="fw-medium text-capitalize">{{ str_replace('_', ' ', $booking->cancellation_type ?? '—') }}</span>
                </div>
            </div>

            <div class="admin-card p-4 mb-4">
                <h5 class="mb-3">Amount Breakdown</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span class="admin-text-muted">Original Amount</span>
                    <span>Rs {{ number_format($booking->original_amount ?? 0, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="admin-text-muted">Discount</span>
                    <span>Rs {{ number_format($booking->discount_amount ?? 0, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="admin-text-muted">Final Amount</span>
                    <span class="fw-semibold">Rs {{ number_format($booking->final_amount ?? $booking->total_price ?? 0, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="admin-text-muted">Cancellation Fine</span>
                    <span>Rs {{ number_format($booking->cancellation_fine ?? 0, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="admin-text-muted">Fine Paid</span>
                    <span class="fw-medium">{{ $booking->fine_paid ? 'Yes' : 'No' }}</span>
                </div>
            </div>

            @if($booking->review)
                <div class="admin-card p-4">
                    <h5 class="mb-3">Customer Review</h5>
                    <p class="mb-1 fw-medium">Rating: {{ number_format($booking->review->barber_rating ?? 0, 1) }}/5</p>
                    <p class="mb-0 admin-text-muted">{{ $booking->review->comment ?? 'No written review.' }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
