@extends('frontend.layouts.app')

@section('title', 'How It Works')

@section('content')

{{-- ── PAGE HERO ── --}}
<section class="py-5 text-center"
    style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);">
    <div class="container py-4">
        <span class="badge bg-trim-orange text-white px-3 py-2 mt-5 mb-3 fs-6">Simple Process</span>
        <h1 class="display-4 fw-bold text-white mb-3">
            How <span class="text-trim-orange">TrimTime</span> Works
        </h1>
        <p class="lead text-white-50 mx-auto" style="max-width: 560px;">
            From finding your barber to showing up fresh — it takes less than 60 seconds to book.
        </p>
    </div>
</section>

{{-- ── 3 STEPS ── --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-5 align-items-center">

            {{-- Step 1 --}}
            <div class="col-md-4 text-center">
                <div class="tt-step-circle mx-auto mb-4">1</div>
                <h4 class="fw-bold mb-2">Find a Barber Shop</h4>
                <p class="text-muted">
                    Search by location, name, or service type. Browse real customer ratings,
                    photos, and available barbers near you.
                </p>
                <a href="{{ route('frontend.shops.index') }}" class="btn tt-btn-primary mt-2">
                    Browse Shops <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            {{-- Step 2 --}}
            <div class="col-md-4 text-center">
                <div class="tt-step-circle mx-auto mb-4">2</div>
                <h4 class="fw-bold mb-2">Pick Your Time Slot</h4>
                <p class="text-muted">
                    Choose your preferred barber, select a service, and pick an available
                    time slot that fits your schedule. No phone calls needed.
                </p>
            </div>

            {{-- Step 3 --}}
            <div class="col-md-4 text-center">
                <div class="tt-step-circle mx-auto mb-4">3</div>
                <h4 class="fw-bold mb-2">Pay & Show Up</h4>
                <p class="text-muted">
                    Pay securely via Khalti or cash on delivery. Get instant confirmation
                    and a reminder before your appointment. Just show up!
                </p>
            </div>

        </div>
    </div>
</section>

{{-- ── FOR CUSTOMERS ── --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">For <span class="text-trim-orange">Customers</span></h2>
            <p class="text-muted">Everything you get as a TrimTime customer</p>
        </div>
        <div class="row g-4">
            @foreach([
                ['bi-search',         'Discover Barbers',       'Find verified barber shops by location, rating, or service. Read real reviews from real customers.'],
                ['bi-calendar-check', 'Real-Time Booking',      'See live availability and book your slot instantly — no waiting, no back-and-forth calls.'],
                ['bi-bell-fill',      'Reminders',              'Get email reminders before your appointment so you never miss your slot.'],
                ['bi-star-fill',      'Rate & Review',          'After your visit, leave honest feedback that helps others find great barbers.'],
                ['bi-clock-history',  'Booking History',        'View all your past and upcoming bookings from your personal dashboard.'],
                ['bi-shield-check',   'Secure Payments',        'Pay via Khalti with full security, or choose cash on delivery.'],
            ] as [$icon, $title, $desc])
            <div class="col-md-4">
                <div class="tt-feature-card h-100 p-4">
                    <div class="tt-feature-icon mb-3">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <h6 class="fw-semibold mb-2">{{ $title }}</h6>
                    <p class="text-muted mb-0 small">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── FOR BARBER SHOPS ── --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">For <span class="text-trim-orange">Barber Shops</span></h2>
            <p class="text-muted">Grow your business with TrimTime's management tools</p>
        </div>
        <div class="row g-4">
            @foreach([
                ['bi-shop',            'Shop Dashboard',         'Manage your entire shop from one dashboard — bookings, barbers, services, and analytics.'],
                ['bi-people-fill',     'Barber Management',      'Add multiple barbers, set their availability, and track their individual performance.'],
                ['bi-calendar2-week',  'Schedule Control',       'Set time slots, working hours, and prevent double bookings automatically.'],
                ['bi-graph-up-arrow',  'Analytics & Reports',    'See booking trends, revenue reports, and customer feedback at a glance.'],
                ['bi-chat-dots-fill',  'Customer Insights',      'Read reviews and track ratings to understand what your customers love.'],
                ['bi-check-circle',    'Approval Workflow',      'Get listed on TrimTime after a quick admin review. Fast and simple onboarding.'],
            ] as [$icon, $title, $desc])
            <div class="col-md-4">
                <div class="tt-feature-card h-100 p-4">
                    <div class="tt-feature-icon mb-3">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <h6 class="fw-semibold mb-2">{{ $title }}</h6>
                    <p class="text-muted mb-0 small">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── FAQ ── --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">Frequently Asked <span class="text-trim-orange">Questions</span></h2>
            <p class="text-muted">Everything you need to know about TrimTime</p>
        </div>

        {{-- Booking Section --}}
        <div class="mb-5">
            <h4 class="fw-bold mb-4 text-trim-orange">
                <i class="bi bi-calendar-check me-2"></i> Booking
            </h4>
            <div class="accordion accordion-flush" id="bookingAccordion">
                <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#booking1">
                            How do I book an appointment?
                        </button>
                    </h2>
                    <div id="booking1" class="accordion-collapse collapse show" data-bs-parent="#bookingAccordion">
                        <div class="accordion-body text-muted">
                            <ol class="mb-0">
                                <li>Search and find a barber shop near you</li>
                                <li>Select your preferred barber and service</li>
                                <li>Choose an available time slot</li>
                                <li>Confirm your booking and pay</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#booking2">
                            Can I change my appointment time?
                        </button>
                    </h2>
                    <div id="booking2" class="accordion-collapse collapse" data-bs-parent="#bookingAccordion">
                        <div class="accordion-body text-muted">
                            Yes, you can reschedule your appointment up to <strong>2 hours before</strong> your booking time.
                            Go to "My Bookings" and click "Reschedule". Select a new available time slot and confirm.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#booking3">
                            How far in advance can I book?
                        </button>
                    </h2>
                    <div id="booking3" class="accordion-collapse collapse" data-bs-parent="#bookingAccordion">
                        <div class="accordion-body text-muted">
                            You can book appointments <strong>up to 30 days in advance</strong>. This gives you plenty of time
                            to plan your grooming schedule.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cancellation & No-Show Section --}}
        <div class="mb-5">
            <h4 class="fw-bold mb-4 text-trim-orange">
                <i class="bi bi-x-circle me-2"></i> Cancellation & No-Show Policy
            </h4>
            <div class="accordion accordion-flush" id="cancellationAccordion">
                <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#cancel1">
                            📋 What is the cancellation policy?
                        </button>
                    </h2>
                    <div id="cancel1" class="accordion-collapse collapse show" data-bs-parent="#cancellationAccordion">
                        <div class="accordion-body text-muted">
                            <ul class="mb-0">
                                <li><strong>Free Cancellation:</strong> Cancel <strong>more than 1 hour</strong> before your appointment with <strong>no charges</strong></li>
                                <li><strong>Late Cancellation Fine:</strong> Cancel <strong>within 1 hour</strong> = <span class="text-danger fw-semibold">10% of service price (min Rs. 30, max Rs. 80)</span> + lose <strong>3 loyalty points</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cancel2">
                            ⚠️ What happens if I don't show up?
                        </button>
                    </h2>
                    <div id="cancel2" class="accordion-collapse collapse" data-bs-parent="#cancellationAccordion">
                        <div class="accordion-body text-muted">
                            <strong>No-Show Fine:</strong> If you miss your appointment without canceling, you will be charged
                            <span class="text-danger fw-semibold">15% of service price (min Rs. 50, max Rs. 150)</span> + lose <strong>5 loyalty points</strong>.
                            <br><br>
                            This fine must be paid before you can make another booking.
                            <br><br>
                            <strong>How to avoid this:</strong> Always cancel/reschedule if you can't make it. Set reminders!
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cancel3">
                            How do I pay a no-show fine?
                        </button>
                    </h2>
                    <div id="cancel3" class="accordion-collapse collapse" data-bs-parent="#cancellationAccordion">
                        <div class="accordion-body text-muted">
                            If you have a no-show fine, you'll see it in your "My Bookings" section with a
                            <strong>"Pay Fine"</strong> button. Click it to pay via Khalti or Cash on Delivery.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cancel4">
                            Can the barber shop cancel my booking?
                        </button>
                    </h2>
                    <div id="cancel4" class="accordion-collapse collapse" data-bs-parent="#cancellationAccordion">
                        <div class="accordion-body text-muted">
                            Yes, shops can cancel bookings in emergencies (e.g., barber illness). If a shop cancels,
                            <strong>you'll get a full refund</strong> and receive a notification immediately.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Section --}}
        <div class="mb-5">
            <h4 class="fw-bold mb-4 text-trim-orange">
                <i class="bi bi-credit-card me-2"></i> Payment Methods & Pricing
            </h4>
            <div class="accordion accordion-flush" id="paymentAccordion">
                <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#payment1">
                            What payment methods do you accept?
                        </button>
                    </h2>
                    <div id="payment1" class="accordion-collapse collapse show" data-bs-parent="#paymentAccordion">
                        <div class="accordion-body text-muted">
                            <strong>We accept two payment methods:</strong>
                            <ul class="mt-2 mb-0">
                                <li><strong>Khalti:</strong> Digital wallet for instant secure payments</li>
                                <li><strong>Cash on Delivery (COD):</strong> Pay the barber when you arrive</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#payment2">
                            What is Khalti?
                        </button>
                    </h2>
                    <div id="payment2" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                        <div class="accordion-body text-muted">
                            <strong>Khalti</strong> is a popular digital payment platform in Nepal. It's safe, fast, and widely accepted.
                            You can link your bank account or card to make quick payments without carrying cash.
                            <br><br>
                            <strong>Benefits:</strong> Instant payment confirmation, digital receipt, and easy refunds.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#payment3">
                            Is it safe to pay with Khalti?
                        </button>
                    </h2>
                    <div id="payment3" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                        <div class="accordion-body text-muted">
                            Yes, Khalti uses <strong>bank-level encryption</strong> to protect your information.
                            Your card or bank details are never shared with us — only Khalti processes the payment.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#payment4">
                            What's included in the booking price?
                        </button>
                    </h2>
                    <div id="payment4" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                        <div class="accordion-body text-muted">
                            The booking price includes:
                            <ul class="mt-2 mb-0">
                                <li>Service cost (haircut, shave, etc.)</li>
                                <li>Barber's skill and time</li>
                                <li>Booking guarantee (reserved time slot)</li>
                            </ul>
                            <br>
                            <strong>Note:</strong> Tips are optional and can be added when paying.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Loyalty Section --}}
        <div class="mb-5">
            <h4 class="fw-bold mb-4 text-trim-orange">
                <i class="bi bi-gift me-2"></i> Loyalty Points
            </h4>
            <div class="accordion accordion-flush" id="loyaltyAccordion">
                <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#loyalty1">
                            How do loyalty points work?
                        </button>
                    </h2>
                    <div id="loyalty1" class="accordion-collapse collapse show" data-bs-parent="#loyaltyAccordion">
                        <div class="accordion-body text-muted">
                            Every completed booking earns you <strong>1 point per Rs. 100 spent</strong>.
                            Accumulate points and redeem them for discounts on future bookings!
                            <br><br>
                            <strong>Example:</strong> Spend Rs. 500 = 5 points | Spend Rs. 1,000 = 10 points
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#loyalty2">
                            How do I redeem my points?
                        </button>
                    </h2>
                    <div id="loyalty2" class="accordion-collapse collapse" data-bs-parent="#loyaltyAccordion">
                        <div class="accordion-body text-muted">
                            When booking, you'll see a "Redeem Points" option before payment. Available tiers:
                            <ul class="mt-2 mb-0">
                                <li><strong>10 points</strong> = Rs. 50 discount</li>
                                <li><strong>20 points</strong> = Rs. 100 discount</li>
                                <li><strong>30 points</strong> = Rs. 150 discount</li>
                                <li><strong>50 points</strong> = <span class="text-success fw-semibold">Free service (up to Rs. 300 value)</span></li>
                            </ul>
                            Your booking price will automatically reduce!
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#loyalty3">
                            Do I earn points if cancelled?
                        </button>
                    </h2>
                    <div id="loyalty3" class="accordion-collapse collapse" data-bs-parent="#loyaltyAccordion">
                        <div class="accordion-body text-muted">
                            <strong>No.</strong> Points are only earned for <strong>completed bookings</strong>.
                            Cancelled or no-show bookings don't earn points.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Other Questions --}}
        <div class="mb-5">
            <h4 class="fw-bold mb-4 text-trim-orange">
                <i class="bi bi-chat-dots me-2"></i> Other Questions
            </h4>
            <div class="accordion accordion-flush" id="otherAccordion">
                <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#other1">
                            How do I contact customer support?
                        </button>
                    </h2>
                    <div id="other1" class="accordion-collapse collapse show" data-bs-parent="#otherAccordion">
                        <div class="accordion-body text-muted">
                            <strong>Reach us via:</strong>
                            <ul class="mt-2 mb-0">
                                <li>📧 Email: trimtime66@gmail.com</li>
                                <li>📱 WhatsApp: <a href="https://wa.me/977?text=Hello%20TrimTime" target="_blank" class="text-trim-orange">Message us</a></li>
                                <li>📞 Phone: +977 61 123456</li>
                                <li>🕒 Hours: Sun – Fri: 9 AM – 6 PM (Saturday Closed)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#other2">
                            How do barber shops register?
                        </button>
                    </h2>
                    <div id="other2" class="accordion-collapse collapse" data-bs-parent="#otherAccordion">
                        <div class="accordion-body text-muted">
                            Shops can register as partners on our platform.
                            <a href="{{ route('frontend.shops.partner.create') }}" class="text-trim-orange fw-semibold">
                                Become a Partner <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 shadow-sm rounded-3 mb-3">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#other3">
                            Is my personal information safe?
                        </button>
                    </h2>
                    <div id="other3" class="accordion-collapse collapse" data-bs-parent="#otherAccordion">
                        <div class="accordion-body text-muted">
                            Yes! We use industry-standard encryption and follow data protection laws.
                            Your information is never shared with third parties without your consent.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── CTA ── --}}
<section class="tt-cta-section py-5 text-center">
    <div class="container py-3">
        <h2 class="display-5 fw-bold text-white mb-3">Ready to Book Your Next Cut?</h2>
        <p class="lead text-white-50 mb-5">Join TrimTime and find your perfect barber in seconds.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('frontend.shops.index') }}" class="btn tt-btn-primary btn-lg px-5">
                Find a Barber
            </a>
            @guest
            <a href="{{ route('register') }}" class="btn tt-btn-ghost-white btn-lg px-5">
                Create Free Account
            </a>
            @endguest
        </div>
    </div>
</section>

@endsection
