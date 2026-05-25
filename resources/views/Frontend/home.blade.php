@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')

{{-- ── HERO ──────────────────────────────────────────────────────── --}}
<section class="tt-hero d-flex align-items-center position-relative"
    style="background-image: url('https://images.unsplash.com/photo-1503951914875-452162b0f3f1?fm=jpg&q=60&w=3000&auto=format&fit=crop'); background-size: cover; background-position: center;">

    <div class="tt-hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>

    <div class="container text-center position-relative z-1 py-5">

        <span class="badge tt-hero-badge mb-4">
            <i class="bi bi-geo-alt-fill me-1"></i> Serving Kathmandu, Pokhara & beyond
        </span>

        <h1 class="display-3 fw-bold text-white mb-4 lh-sm">
            Find Your <span class="text-trim-blue">Perfect Barber</span><br>
            <span class="fs-3 fw-normal">Book in 60 seconds</span>
        </h1>

        <p class="lead text-white-50 mb-5 mx-auto" style="max-width: 580px;">
            Discover top-rated barber shops near you. Choose your barber, service,
            and time slot — get instant confirmation.
        </p>

        {{-- search bar --}}
         <div class="row justify-content-center mb-5">
            <div class="col-lg-6 col-md-8">
                <form action="{{ route('frontend.shops.index') }}" method="GET">
                    <div class="tt-search-bar d-flex align-items-center">
                        <i class="bi bi-search text-muted ms-3 me-2"></i>
                        <input
                            type="text"
                            name="search"
                            class="flex-grow-1 border-0 bg-transparent outline-0 text-dark"
                            placeholder="Search barbers, shops, services..."
                            style="outline: none; box-shadow: none;"
                            value="{{ request('search') }}"
                            autocomplete="off"
                        >
                        <button type="submit" class="tt-search-btn border-0">Find Now</button>
                    </div>
                </form>
            </div>
        </div>

        @auth
            <span class="badge bg-trim-blue fs-6 px-4 py-2 rounded-pill text-white">
                Welcome back, {{ Auth::user()->name }} 👋
            </span>
        @else
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('frontend.shops.index') }}" class="btn tt-btn-primary btn-lg px-5">
                    <i class="bi bi-search me-2"></i>Find a Barber
                </a>
                <a href="{{ route('register') }}" class="btn tt-btn-ghost-white btn-lg px-5">
                    Create Free Account
                </a>
            </div>
        @endauth

    </div>
</section>

{{-- ── STATS ─────────────────────────────────────────────────────── --}}
<section class="py-5 bg-white border-bottom">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-6 col-md-3">
                <h2 class="display-5 fw-bold text-trim-blue mb-1">
                    {{ $shopCount > 0 ? $shopCount.'+' : '0' }}
                </h2>
                <p class="text-muted mb-0">Barber Shops</p>
            </div>
            <div class="col-6 col-md-3">
                <h2 class="display-5 fw-bold text-trim-blue mb-1">
                    {{ $clientCount > 0 ? number_format($clientCount).'+' : '0' }}
                </h2>
                <p class="text-muted mb-0">Happy Clients</p>
            </div>
            <div class="col-6 col-md-3">
                <h2 class="display-5 fw-bold text-trim-blue mb-1">
                    {{ $avgRating ? number_format($avgRating, 1) : 'N/A' }}
                    @if($avgRating)
                        <i class="bi bi-star-fill fs-4"></i>
                    @endif
                </h2>
                <p class="text-muted mb-0">Average Rating</p>
            </div>
            <div class="col-6 col-md-3">
                <h2 class="display-5 fw-bold text-trim-blue mb-1">
                    <i class="bi bi-lightning-fill fs-3"></i>
                </h2>
                <p class="text-muted mb-0">Instant Confirmation</p>
            </div>
        </div>
    </div>
</section>

{{-- ── WHY TRIMTIME ──────────────────────────────────────────────── --}}
<section class="py-5 bg-white">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-2">Why Choose <span class="text-trim-blue">BarberTime</span>?</h2>
        <p class="lead text-muted mb-5">Connecting you with trusted local barbers — fast, reliable, and built for Nepal.</p>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="tt-feature-card h-100 p-4">
                    <div class="tt-feature-icon mb-4">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">Top-Rated Barbers</h5>
                    <p class="text-muted mb-0">Verified shops with high ratings and proven track records from real customers.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="tt-feature-card h-100 p-4">
                    <div class="tt-feature-icon mb-4">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">Fast & Easy Booking</h5>
                    <p class="text-muted mb-0">Select barber, service & slot in seconds — real-time availability, no phone calls.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="tt-feature-card h-100 p-4">
                    <div class="tt-feature-icon mb-4">
                        <i class="bi bi-shield-check-fill"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">Secure & Reliable</h5>
                    <p class="text-muted mb-0">Instant confirmations, reminders & Khalti-secured payments you can trust.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── FEATURED SHOPS ────────────────────────────────────────────── --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
            <div>
                <h2 class="display-5 fw-bold mb-1">Featured Barber Shops</h2>
                <p class="text-muted mb-0">Hand-picked top-rated shops near you</p>
            </div>
            <a href="{{ route('frontend.shops.index') }}" class="btn btn-outline-secondary">
                View All Shops <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="featured-shops-wrapper">
            <button class="featured-scroll-btn left" type="button" onclick="scrollFeaturedShops(-1)">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="featured-shops-scroll" id="featuredShopsScroll">
                @forelse($shops ?? [] as $shop)
                    <div class="featured-shop-item">
                        <div class="tt-shop-card h-100">
                            @if($shop->shop_image)
                                <img src="{{ asset('storage/' . $shop->shop_image) }}"
                                    class="tt-shop-img" alt="{{ $shop->display_name }}">
                            @else
                                <div class="tt-shop-img-placeholder">
                                    <i class="bi bi-shop"></i>
                                </div>
                            @endif

                            <div class="p-3 d-flex flex-column">
                                <h6 class="fw-semibold mb-2">{{ $shop->display_name }}</h6>
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $shop->address ?? 'Nepal' }}
                                </p>

                                <div class="mt-auto">
                                    <a href="{{ route('frontend.shops.show', $shop) }}"
                                       class="btn tt-btn-primary btn-sm w-100">
                                        View Shop
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="w-100 text-center py-5">
                        <i class="bi bi-shop display-4 text-muted opacity-50 mb-3 d-block"></i>
                        <p class="text-muted fs-5">No featured shops yet. Check back soon!</p>
                    </div>
                @endforelse
            </div>

            <button class="featured-scroll-btn right" type="button" onclick="scrollFeaturedShops(1)">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

{{-- ── POPULAR SERVICES ──────────────────────────────────────────── --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-2">Popular Services</h2>
            <p class="lead text-muted">From everyday cuts to premium grooming — transparent prices, real durations.</p>
        </div>

        <div class="row g-3 justify-content-center">
            @forelse($services ?? [] as $service)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="tt-service-card text-center p-4 h-100">
                        <div class="tt-service-icon mb-3">
                            <i class="bi bi-scissors"></i>
                        </div>
                        <h6 class="fw-semibold mb-2">{{ $service->name }}</h6>
                        <p class="text-trim-blue fw-bold fs-5 mb-1">रु {{ number_format($service->price) }}</p>
                        <small class="text-muted d-block mb-1">
                            <i class="bi bi-clock me-1"></i>{{ $service->duration }} min
                        </small>
                        <small class="text-muted">
                            {{ optional($service->barberShop?->barberShop)->name ?? 'Various Shops' }}
                        </small>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-5">No popular services listed yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ── CTA BANNER ────────────────────────────────────────────────── --}}
<section class="tt-cta-section py-5 text-center">
    <div class="container py-3">
        @auth
            <h2 class="display-4 fw-bold text-white mb-3">Welcome Back, {{ Auth::user()->name }}!</h2>
            <p class="lead text-white-50 mb-5">Ready for your next trim? Find a barber and book your next appointment.</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('frontend.shops.index') }}" class="btn tt-btn-primary btn-lg px-5">
                    Find a Barber
                </a>
                <a href="{{ route('dashboard') }}" class="btn tt-btn-ghost-white btn-lg px-5">
                    Go to Dashboard
                </a>
            </div>
        @else
            <h2 class="display-4 fw-bold text-white mb-3">Ready for Your Perfect Cut?</h2>
            <p class="lead text-white-50 mb-5">
                Join thousands of happy clients who trust BarberTime for quick, reliable barber bookings.
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('frontend.shops.index') }}" class="btn tt-btn-primary btn-lg px-5">
                    Find a Barber
                </a>
                <a href="{{ route('register') }}" class="btn tt-btn-ghost-white btn-lg px-5">
                    Create Free Account
                </a>
            </div>
        @endauth
    </div>
</section>
<script>
    function scrollFeaturedShops(direction) {
        const container = document.getElementById('featuredShopsScroll');
        const card = container.querySelector('.featured-shop-item');

        if (!card) return;

        const cardWidth = card.offsetWidth + 24;
        container.scrollBy({
            left: direction * cardWidth,
            behavior: 'smooth'
        });
    }
</script>

@endsection
