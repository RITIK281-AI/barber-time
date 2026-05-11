<div class="dashboard-panel">
    <div class="panel-title">Notifications</div>
    <div class="panel-subtitle">View important booking and payment updates</div>

    <div class="text-uppercase text-muted fw-semibold mb-3"
         style="font-size:0.72rem;letter-spacing:0.08em">
        Booking Confirmed
    </div>

    @if($confirmedNotifications->isEmpty())
        <div class="text-muted small border rounded-3 p-3 mb-4">
            No confirmed booking notifications yet.
        </div>
    @else
        <div class="list-group mb-4">
            @foreach($confirmedNotifications as $booking)
                <div class="list-group-item border-0 border-bottom py-3 px-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-semibold">Booking Confirmed - {{ $booking->barberShop?->name ?? 'Shop' }}</div>
                            <div class="text-muted small">
                                {{ $booking->service?->name ?? 'Service' }} | {{ $booking->booking_date?->format('d M Y') }} {{ $booking->booking_time?->format('h:i A') }}
                            </div>
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle">Confirmed</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="text-uppercase text-muted fw-semibold mb-3"
         style="font-size:0.72rem;letter-spacing:0.08em">
        Payment Completed
    </div>

    @if($paymentNotifications->isEmpty())
        <div class="text-muted small border rounded-3 p-3">
            No payment completion notifications yet.
        </div>
    @else
        <div class="list-group">
            @foreach($paymentNotifications as $booking)
                <div class="list-group-item border-0 border-bottom py-3 px-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-semibold">Payment Complete - {{ $booking->barberShop?->name ?? 'Shop' }}</div>
                            <div class="text-muted small">
                                Rs {{ number_format($booking->final_amount ?? $booking->total_price ?? 0, 2) }} paid
                            </div>
                        </div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Paid</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
