@extends('frontend.layouts.app')

@section('title', 'Select Payment Method')

@section('content')

<section class="py-5 mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">

                <h1 class="display-6 fw-bold mb-1">Complete Your Booking</h1>
                <p class="lead text-muted mb-4">
                    Pay a 25% advance to confirm Booking #{{ $booking->id }}.
                    The remaining amount is paid after your service.
                </p>

                {{-- Booking Summary Card --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-receipt me-2 text-trim-orange"></i>Booking Summary
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted">Shop</td>
                                <td class="fw-semibold">{{ $booking->barberShop?->display_name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Service</td>
                                <td class="fw-semibold">{{ $booking->service?->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Barber</td>
                                <td class="fw-semibold">{{ $booking->barber?->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Date & Time</td>
                                <td class="fw-semibold">
                                    {{ optional($booking->booking_date)->format('Y-m-d') }}
                                    {{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('h:i A') : '' }}
                                </td>
                            </tr>
                            <tr class="border-top">
                                <td class="text-muted">Total Service Price</td>
                                <td class="fw-semibold">Rs. {{ number_format($servicePrice, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted text-success fw-semibold">
                                    <i class="bi bi-arrow-up-circle me-1"></i>Advance to Pay Now (25%)
                                </td>
                                <td class="fw-bold text-success fs-5">
                                    Rs. {{ number_format($advanceAmount, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Remaining After Service</td>
                                <td class="fw-semibold text-muted">
                                    Rs. {{ number_format($remainingAmount, 2) }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Warning about non-refundable advance --}}
                <div class="alert alert-warning d-flex align-items-start gap-2 mb-4">
                    <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                    <div>
                        <strong>Non-refundable advance.</strong>
                        If you cancel after paying, the advance of
                        Rs. {{ number_format($advanceAmount, 2) }} will not be refunded.
                    </div>
                </div>

                {{-- Payment Method Selection --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-credit-card me-2 text-trim-orange"></i>Choose Payment Method
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            Both methods require a 25% advance via Khalti.
                            The remaining 75% is paid after your service is done.
                        </p>

                        <form action="{{ route('payment.advance', $booking->id) }}" method="POST">
                            @csrf

                            {{-- Khalti Option --}}
                            <div class="form-check border rounded p-3 mb-3 cursor-pointer"
                                onclick="selectMethod('khalti')" id="card-khalti"
                                style="cursor:pointer;">
                                <input class="form-check-input mt-1" type="radio"
                                    name="payment_method" id="method_khalti"
                                    value="khalti" required>
                                <label class="form-check-label ms-2 w-100" for="method_khalti"
                                    style="cursor:pointer;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <span class="fw-semibold">Pay via Khalti</span>
                                            <div class="text-muted small">
                                                Advance (Rs. {{ number_format($advanceAmount, 2) }})
                                                + remaining (Rs. {{ number_format($remainingAmount, 2) }})
                                                both paid online via Khalti.
                                            </div>
                                        </div>
                                        <img src="https://khalti.com/static/khalti-logo.svg"
                                            alt="Khalti" height="28"
                                            onerror="this.style.display='none'">
                                    </div>
                                </label>
                            </div>

                            {{-- COD Option --}}
                            <div class="form-check border rounded p-3 mb-4 cursor-pointer"
                                onclick="selectMethod('cod')" id="card-cod"
                                style="cursor:pointer;">
                                <input class="form-check-input mt-1" type="radio"
                                    name="payment_method" id="method_cod"
                                    value="cod" required>
                                <label class="form-check-label ms-2 w-100" for="method_cod"
                                    style="cursor:pointer;">
                                    <div>
                                        <span class="fw-semibold">Cash on Delivery (COD)</span>
                                        <div class="text-muted small">
                                            Advance (Rs. {{ number_format($advanceAmount, 2) }})
                                            paid via Khalti now. Remaining
                                            (Rs. {{ number_format($remainingAmount, 2) }})
                                            paid cash at shop after service.
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-trim text-white btn-lg"
                                    onclick="return confirm('You will be redirected to Khalti to pay the advance of Rs. {{ number_format($advanceAmount, 2) }}. Continue?')">
                                    <i class="bi bi-lock-fill me-2"></i>
                                    Pay Advance Rs. {{ number_format($advanceAmount, 2) }} via Khalti
                                </button>
                                <a href="{{ route('frontend.bookings.index') }}"
                                    class="btn btn-outline-secondary">
                                    Back to My Bookings
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    function selectMethod(method) {
        // Highlight selected card
        document.getElementById('card-khalti').classList.remove('border-success', 'bg-success-subtle');
        document.getElementById('card-cod').classList.remove('border-success', 'bg-success-subtle');
        document.getElementById('card-' + method).classList.add('border-success', 'bg-success-subtle');

        // Check the radio button
        document.getElementById('method_' + method).checked = true;
    }
</script>
@endpush
