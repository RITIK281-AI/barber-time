@extends('frontend.layouts.app')

@section('title', 'Confirm Payment')

@section('content')

<section class="py-5 mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">

                <h1 class="display-6 fw-bold mb-1">Payment Details</h1>
                <p class="lead text-muted mb-4">Review your booking and choose how to pay.</p>

                {{-- Alerts --}}
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Booking Summary Card --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-receipt me-2 text-trim-orange"></i>Booking Summary
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted">Shop</td>
                                <td class="fw-semibold">{{ $booking->barberShop?->name ?? '—' }}</td>
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
                                <td class="text-muted">Date</td>
                                <td class="fw-semibold">
                                    {{ optional($booking->booking_date)->format('D, d M Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Time</td>
                                <td class="fw-semibold">
                                    {{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('h:i A') : '—' }}
                                    –
                                    {{ $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('h:i A') : '—' }}
                                </td>
                            </tr>

                            {{-- Price breakdown --}}
                            <tr class="border-top">
                                <td class="text-muted">Service Price</td>
                                <td class="fw-semibold">
                                    Rs. {{ number_format($booking->original_amount ?? $booking->service?->price ?? 0, 2) }}
                                </td>
                            </tr>

                            {{-- Only show discount row if loyalty points were redeemed --}}
                            @if($booking->discount_amount > 0)
                                <tr>
                                    <td class="text-muted text-success">
                                        <i class="bi bi-star-fill me-1"></i>
                                        Loyalty Discount ({{ $booking->redeemed_points }} pts)
                                    </td>
                                    <td class="fw-semibold text-success">
                                        − Rs. {{ number_format($booking->discount_amount, 2) }}
                                    </td>
                                </tr>
                            @endif

                            <tr class="border-top">
                                <td class="fw-bold fs-5">Total Payable</td>
                                <td class="fw-bold fs-5 text-trim-orange">
                                    Rs. {{ number_format($booking->final_amount ?? $booking->service?->price ?? 0, 2) }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Payment Method Selection --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-credit-card me-2 text-trim-orange"></i>Choose Payment Method
                    </div>
                    <div class="card-body">

                        {{-- Khalti Option --}}
                        <div class="border rounded p-3 mb-3"
                             id="card-khalti"
                             style="cursor:pointer;"
                             onclick="selectMethod('khalti')">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <input class="form-check-input mt-0"
                                           type="radio"
                                           name="payment_method"
                                           id="method_khalti"
                                           value="khalti">
                                    <label for="method_khalti" style="cursor:pointer;">
                                        <span class="fw-semibold d-block">Pay via Khalti</span>
                                        <span class="text-muted small">
                                            Pay Rs. {{ number_format($booking->final_amount ?? $booking->service?->price ?? 0, 2) }} securely online via Khalti wallet.
                                        </span>
                                    </label>
                                </div>
                                <img src="https://khalti.com/static/khalti-logo.svg"
                                     alt="Khalti" height="28"
                                     onerror="this.style.display='none'">
                            </div>
                        </div>

                        {{-- COD Option --}}
                        <div class="border rounded p-3 mb-4"
                             id="card-cod"
                             style="cursor:pointer;"
                             onclick="selectMethod('cod')">
                            <div class="d-flex align-items-center gap-3">
                                <input class="form-check-input mt-0"
                                       type="radio"
                                       name="payment_method"
                                       id="method_cod"
                                       value="cod">
                                <label for="method_cod" style="cursor:pointer;">
                                    <span class="fw-semibold d-block">Cash at Shop (COD)</span>
                                    <span class="text-muted small">
                                        Pay Rs. {{ number_format($booking->final_amount ?? $booking->service?->price ?? 0, 2) }} in cash directly at the shop after your service.
                                    </span>
                                </label>
                            </div>
                        </div>

                        {{-- Payment error if no method selected --}}
                        <div id="method-error" class="alert alert-danger d-none mb-3">
                            Please select a payment method before continuing.
                        </div>

                        {{-- Khalti form --}}
                        <form id="form-khalti"
                              action="{{ route('user.payment.initiate', $booking->id) }}"
                              method="POST"
                              class="d-none">
                            @csrf
                        </form>

                        {{-- COD form --}}
                        <form id="form-cod"
                              action="{{ route('user.payment.cod', $booking->id) }}"
                              method="POST"
                              class="d-none">
                            @csrf
                        </form>

                        <div class="d-grid gap-2">
                            <button type="button"
                                    class="btn btn-trim text-white btn-lg"
                                    onclick="submitPayment()">
                                <i class="bi bi-lock-fill me-2"></i>Confirm & Pay
                            </button>
                            <a href="{{ route('frontend.bookings.index') }}"
                               class="btn btn-outline-secondary">
                                Back to My Bookings
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function selectMethod(method) {
        // Reset card highlights
        document.getElementById('card-khalti').classList.remove('border-success', 'bg-success-subtle');
        document.getElementById('card-cod').classList.remove('border-success', 'bg-success-subtle');

        // Highlight selected card
        document.getElementById('card-' + method).classList.add('border-success', 'bg-success-subtle');

        // Check the radio
        document.getElementById('method_' + method).checked = true;

        // Hide error if visible
        document.getElementById('method-error').classList.add('d-none');
    }

    function submitPayment() {
        const khalti = document.getElementById('method_khalti').checked;
        const cod    = document.getElementById('method_cod').checked;

        if (!khalti && !cod) {
            document.getElementById('method-error').classList.remove('d-none');
            return;
        }

        if (khalti) {
            // Confirm before redirecting to Khalti
            const amount = "Rs. {{ number_format($booking->final_amount ?? $booking->service?->price ?? 0, 2) }}";
            if (confirm('You will be redirected to Khalti to pay ' + amount + '. Continue?')) {
                document.getElementById('form-khalti').submit();
            }
        } else {
            // COD confirmation
            const amount = "Rs. {{ number_format($booking->final_amount ?? $booking->service?->price ?? 0, 2) }}";
            if (confirm('You will pay ' + amount + ' in cash at the shop after your service. Continue?')) {
                document.getElementById('form-cod').submit();
            }
        }
    }
</script>
@endpush

@endsection
