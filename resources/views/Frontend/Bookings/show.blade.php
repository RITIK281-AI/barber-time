@extends('frontend.layouts.app')

@section('title', 'Booking Details')

@section('content')
<section class="py-4" style="margin-top: 100px;">
    <div class="container" style="max-width: 900px;">

        @php
            $statusLabel = match($booking->status) {
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
                'pending'   => 'Pending',
                'confirmed' => 'Confirmed',
                'no_show'   => 'No Show',
                default     => ucfirst(str_replace('_', ' ', (string) $booking->status)),
            };

            $statusClass = match($booking->status) {
                'completed' => 'bg-primary',
                'cancelled' => 'bg-secondary',
                'pending'   => 'bg-warning text-dark',
                'confirmed' => 'bg-success',
                'no_show'   => 'bg-danger',
                default     => 'bg-dark',
            };

            $serviceItems = $booking->items->isNotEmpty()
                ? $booking->items
                : collect();
        @endphp

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
            <div>
                <h1 class="fw-bold mb-1">Booking Details</h1>
                <p class="text-muted mb-0">Booking #{{ $booking->id }} overview and history</p>
            </div>
            <span class="badge {{ $statusClass }} rounded-pill px-3 py-2 fs-6">{{ $statusLabel }}</span>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-info-circle me-2 text-trim-blue"></i>Appointment Information
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Shop</div>
                        <div class="fw-semibold">{{ $booking->barberShop?->name ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Barber</div>
                        <div class="fw-semibold">{{ $booking->barber?->name ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Date</div>
                        <div class="fw-semibold">{{ optional($booking->booking_date)->format('D, d M Y') ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Time</div>
                        <div class="fw-semibold">
                            {{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('h:i A') : '—' }}
                            -
                            {{ $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('h:i A') : '—' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Payment Method</div>
                        <div class="fw-semibold text-capitalize">{{ $booking->payment_method ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Payment Status</div>
                        <div class="fw-semibold text-capitalize">{{ $booking->payment_status ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-scissors me-2 text-trim-blue"></i>Service Details
            </div>
            <div class="card-body">
                @if($serviceItems->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Duration</th>
                                    <th class="text-end">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($serviceItems as $item)
                                    <tr>
                                        <td>{{ $item->service_name ?? $item->service?->name ?? '—' }}</td>
                                        <td>{{ $item->service_duration ?? $item->service?->duration ?? 0 }} min</td>
                                        <td class="text-end">Rs. {{ number_format($item->service_price ?? $item->service?->price ?? 0, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="mb-2 fw-semibold">{{ $booking->service?->name ?? '—' }}</p>
                    <p class="text-muted mb-0">Single-service booking.</p>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-receipt me-2 text-trim-blue"></i>Amount Summary
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Original Amount</span>
                    <span>Rs. {{ number_format($booking->original_amount ?? $booking->total_price ?? 0, 2) }}</span>
                </div>

                @if(($booking->discount_amount ?? 0) > 0)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Loyalty Discount ({{ $booking->redeemed_points ?? 0 }} pts)</span>
                        <span>- Rs. {{ number_format($booking->discount_amount, 2) }}</span>
                    </div>
                @endif

                @if(($booking->cancellation_fine ?? 0) > 0)
                    <div class="d-flex justify-content-between mb-2 text-danger">
                        <span>Cancellation Fine</span>
                        <span>Rs. {{ number_format($booking->cancellation_fine, 2) }}</span>
                    </div>
                @endif

                <hr>

                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Final Amount</span>
                    <span class="text-trim-blue">Rs. {{ number_format($booking->final_amount ?? $booking->total_price ?? 0, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('dashboard', ['tab' => 'bookings']) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Booking History
            </a>

            @if($booking->barberShop)
                <a href="{{ route('frontend.shops.show', $booking->barberShop->id) }}" class="btn btn-trim text-white">
                    <i class="bi bi-arrow-repeat me-1"></i>Book Again
                </a>
            @endif
        </div>

    </div>
</section>
@endsection
