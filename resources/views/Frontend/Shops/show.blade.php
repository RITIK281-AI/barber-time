{{-- resources/views/frontend/shops/show.blade.php --}}

@extends('frontend.layouts.app')

@section('title', $shop->name ?? 'Barber Shop Details')
@push('styles')
<style>
    .service-select-card {
        transition: all 0.2s ease;
        border: 1px solid #e9ecef;
    }

    .service-select-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        border-color: #ffd2b3;
    }

    .service-select-card.is-selected {
        border: 2px solid var(--trim-orange, #f97316) !important;
        background: #fff7f1;
        box-shadow: 0 10px 24px rgba(249, 115, 22, 0.12);
    }

    .selected-service-chip {
        background: #fff7f1;
        border: 1px solid #ffd2b3;
        color: #c2410c;
    }

    #shop-map {
        height: 340px;
        border-radius: 1rem;
        overflow: hidden;
    }
</style>
@endpush
@section('content')

{{-- Hero / Shop Photos --}}
<section class="mt-5 pt-4">
    <div class="container">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('frontend.shops.index') }}" class="text-muted text-decoration-none">All Shops</a>
                </li>
                <li class="breadcrumb-item active fw-semibold">{{ $shop->name }}</li>
            </ol>
        </nav>

        {{-- Photo + Info --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                @if($shop->shop_image)
                    <img src="{{ asset('storage/' . $shop->shop_image) }}"
                         class="img-fluid rounded-4 shadow-sm w-100"
                         style="height: 550px; object-fit: cover;"
                         alt="{{ $shop->name }}">
                @else
                    <div class="bg-dark rounded-4 d-flex align-items-center justify-content-center shadow-sm"
                         style="height: 550px;">
                        <i class="bi bi-scissors display-1 text-secondary opacity-50"></i>
                    </div>
                @endif
            </div>

            <div class="col-lg-4 d-flex flex-column gap-3">
                <div class="card border-0 shadow-sm rounded-4 p-4 flex-grow-1">
                    <h2 class="fw-bold mb-1">{{ $shop->name }}</h2>
                    <p class="text-muted mb-3">
                        <i class="bi bi-geo-alt-fill me-1"></i>
                        {{ $shop->address ?? 'Address not provided' }}
                        @if($shop->district), {{ $shop->district }}@endif
                    </p>

                    <div class="mb-3">
                        @if($shop->average_rating)
                            <span class="fs-5 text-warning fw-bold">
                                <i class="bi bi-star-fill"></i>
                                {{ number_format($shop->average_rating, 1) }}
                            </span>
                            <span class="text-muted ms-1">({{ $shop->total_reviews }} reviews)</span>
                        @else
                            <span class="text-muted"><i class="bi bi-star me-1"></i>No reviews yet</span>
                        @endif
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="border rounded-3 p-2 bg-light h-100">
                                <small class="text-muted d-block">Starting Price</small>
                                <span class="fw-semibold">
                                    Rs. {{ $shop->services->isNotEmpty() ? number_format($shop->services->min('price'), 0) : 'N/A' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded-3 p-2 bg-light h-100">
                                <small class="text-muted d-block">Barbers</small>
                                <span class="fw-semibold">{{ $shop->barbers->count() }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded-3 p-2 bg-light h-100">
                                <small class="text-muted d-block">District</small>
                                <span class="fw-semibold">{{ $shop->district ?: 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded-3 p-2 bg-light h-100">
                                <small class="text-muted d-block">Reviews</small>
                                <span class="fw-semibold">{{ $shop->total_reviews ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    @if($shop->opening_time && $shop->closing_time)
                        <p class="mb-2 text-muted small">
                            <i class="bi bi-clock-fill me-2"></i>
                            {{ \Carbon\Carbon::parse($shop->opening_time)->format('g:i A') }}
                            –
                            {{ \Carbon\Carbon::parse($shop->closing_time)->format('g:i A') }}
                        </p>
                    @endif

                    <p class="mb-2 text-muted small">
                        <i class="bi bi-calendar-x-fill me-2"></i>
                        Closed Days:
                        @if($shop->closedDays->isNotEmpty())
                            {{ $shop->closedDays->pluck('day_name')->join(', ') }}
                        @else
                            Not specified
                        @endif
                    </p>

                    @if($shop->holidayDates->isNotEmpty())
                        <div class="mb-2">
                            <p class="text-muted small mb-1"><i class="bi bi-calendar-event me-2"></i>Upcoming Holidays</p>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($shop->holidayDates as $holiday)
                                    <span class="badge rounded-pill bg-light text-dark border">
                                        {{ $holiday->date->format('d M') }}
                                        @if($holiday->reason)
                                            - {{ $holiday->reason }}
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($shop->phone)
                        <p class="mb-2 text-muted small">
                            <i class="bi bi-telephone-fill me-2"></i>{{ $shop->phone }}
                        </p>
                    @endif

                    @if($shop->email)
                        <p class="mb-0 text-muted small">
                            <i class="bi bi-envelope-fill me-2"></i>{{ $shop->email }}
                        </p>
                    @endif
                    @auth
                        <div class="mt-3 pt-3 border-top">
                            <button
                                id="fav-btn"
                                onclick="toggleFavourite({{ $shop->id }})"
                                class="btn btn-sm w-100 rounded-3 {{ auth()->user()->hasFavourited($shop->id) ? 'btn-danger' : 'btn-outline-danger' }}"
                            >
                                <i class="bi {{ auth()->user()->hasFavourited($shop->id) ? 'bi-heart-fill' : 'bi-heart' }} me-1"></i>
                                <span id="fav-label">
                                    {{ auth()->user()->hasFavourited($shop->id) ? 'Saved to Favourites' : 'Save to Favourites' }}
                                </span>
                            </button>
                        </div>

                        @push('scripts')
                        <script>
                            function toggleFavourite(shopId) {
                                const btn   = document.getElementById('fav-btn');
                                const label = document.getElementById('fav-label');
                                const icon  = btn.querySelector('i');

                                fetch('/favourites/' + shopId + '/toggle', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept':       'application/json',
                                        'Content-Type': 'application/json',
                                    },
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.favourited) {
                                        btn.classList.remove('btn-outline-danger');
                                        btn.classList.add('btn-danger');
                                        icon.className = 'bi bi-heart-fill me-1';
                                        label.textContent = 'Saved to Favourites';
                                    } else {
                                        btn.classList.remove('btn-danger');
                                        btn.classList.add('btn-outline-danger');
                                        icon.className = 'bi bi-heart me-1';
                                        label.textContent = 'Save to Favourites';
                                    }
                                })
                                .catch(() => {
                                    alert('Something went wrong. Please try again.');
                                });
                            }
                        </script>
                        @endpush
                    @endauth
                </div>
                

            </div>
        </div>

    </div>
</section>

{{-- Main Content --}}
<section class="pb-5">
    <div class="container">
        <div class="row g-5">

            {{-- Left: Services + Barbers + Reviews --}}
            <div class="col-lg-8">

                {{-- Services grouped by category --}}
                <div class="mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                        <h4 class="fw-bold mb-0">Services Offered</h4>
                        @if($shop->services->isNotEmpty())
                            <span class="text-muted small">
                                Prices from Rs. {{ number_format($shop->services->min('price'), 2) }}
                            </span>
                        @endif
                    </div>

                    @if($shop->services->isNotEmpty())

                        @php
                            $grouped = $shop->services->groupBy(function ($s) {
                                return $s->category?->name ?? 'Other';
                            });
                        @endphp

                        @foreach($grouped as $categoryName => $categoryServices)

                            <div class="d-flex align-items-center gap-2 mt-4 mb-3">
                                <span class="fw-bold small text-uppercase text-muted">{{ $categoryName }}</span>
                                <div class="flex-grow-1 border-bottom"></div>
                            </div>

                            <div class="d-flex flex-column gap-3">
                                @foreach($categoryServices as $service)
                                    <div class="card border-0 shadow-sm rounded-4 p-4 service-select-card"
                                         id="service-card-{{ $service->id }}"
                                         style="cursor: pointer;"
                                         onclick="toggleService({{ $service->id }}, '{{ addslashes($service->name) }}', {{ $service->price }}, {{ $service->duration ?? 0 }}, '{{ addslashes($categoryName) }}')">

                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1 me-3">
                                                <h5 class="fw-bold mb-1">{{ $service->name }}</h5>
                                                <p class="text-muted small mb-2">
                                                    {{ $service->description ?? 'Professional grooming service' }}
                                                </p>
                                                @if($service->duration)
                                                    <span class="badge bg-light text-muted border">
                                                        <i class="bi bi-clock me-1"></i>{{ $service->duration }} min
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-bold fs-5">Rs. {{ number_format($service->price, 2) }}</div>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-trim mt-2 select-btn"
                                                        id="select-btn-{{ $service->id }}">
                                                    Select
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>

                        @endforeach

                    @else
                        <div class="alert alert-info text-center py-4 rounded-4">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            No services listed yet. Check back soon!
                        </div>
                    @endif
                </div>
                {{-- Barbers — cards are clickable and go to barber profile --}}
                <div class="mb-5">
                    <h4 class="fw-bold mb-4 border-bottom pb-2">Meet the Barbers</h4>
                    @if($shop->barbers->isNotEmpty())
                        <div class="row g-3">
                            @foreach($shop->barbers as $barber)
                                <div class="col-md-4 col-6">
                                    <a href="{{ route('frontend.barbers.show', $barber->id) }}"
                                        class="text-decoration-none text-dark">
                                        <div class="card border-0 shadow-sm rounded-4 text-center p-4 h-100"
                                                style="transition: transform 0.15s ease, box-shadow 0.15s ease; cursor: pointer;"
                                                onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.10)'"
                                                onmouseout="this.style.transform='';this.style.boxShadow=''">

                                            @if($barber->profile_image)
                                                <img src="{{ asset('storage/' . $barber->profile_image) }}"
                                                        class="rounded-circle mx-auto mb-3"
                                                        style="width: 90px; height: 90px; object-fit: cover;"
                                                        alt="{{ $barber->name }}">
                                            @else
                                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-3 mx-auto"
                                                        style="width: 90px; height: 90px;">
                                                    <i class="bi bi-person-fill fs-1 text-white"></i>
                                                </div>
                                            @endif

                                            <h6 class="fw-bold mb-1">{{ $barber->name }}</h6>
                                            <p class="text-muted small mb-2">
                                                {{ $barber->bio ? \Str::limit($barber->bio, 40) : 'Barber' }}
                                            </p>
                                            @if($barber->experience_years)
                                                <span class="badge bg-light text-muted border small">
                                                    {{ $barber->experience_years }} yrs exp
                                                </span>
                                            @endif
                                            @if($barber->average_rating)
                                                <div class="mt-2 text-warning small">
                                                    <i class="bi bi-star-fill"></i>
                                                    {{ number_format($barber->average_rating, 1) }}
                                                </div>
                                            @endif

                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No barbers listed yet.</p>
                    @endif
                </div>
                {{-- Customer Reviews --}}
                <div class="mb-5">
                    <h4 class="fw-bold mb-4 border-bottom pb-2">Customer Reviews</h4>

                    @if($shop->reviews->isNotEmpty())
                        <div class="d-flex flex-column gap-3">
                            @foreach($shop->reviews as $review)
                                <div class="card border-0 shadow-sm rounded-4 p-4">

                                    {{-- reviewer name and date --}}
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-semibold">
                                            <i class="bi bi-person-circle me-1 text-muted"></i>
                                            {{ $review->user?->name ?? 'Customer' }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $review->created_at->format('d M Y') }}
                                        </small>
                                    </div>

                                    {{-- star ratings --}}
                                    <div class="d-flex gap-3 mb-2 small">
                                        <div>
                                            <span class="text-muted">Barber:</span>
                                            <span class="text-warning ms-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= $review->barber_rating ? '-fill' : '' }}"></i>
                                                @endfor
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-muted">Shop:</span>
                                            <span class="text-warning ms-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= $review->shop_rating ? '-fill' : '' }}"></i>
                                                @endfor
                                            </span>
                                        </div>
                                    </div>

                                    {{-- written comment if any --}}
                                    @if($review->comment)
                                        <p class="text-muted small mb-0 mt-1">
                                            <i class="bi bi-chat-left-quote me-1"></i>
                                            {{ $review->comment }}
                                        </p>
                                    @endif

                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-chat-square-text fs-1 opacity-50 d-block mb-3"></i>
                            <p class="mb-0">No reviews yet. Be the first to review!</p>
                        </div>
                    @endif
                </div>

                {{-- Map Location --}}
                <div class="mb-5">
                    <h4 class="fw-bold mb-4 border-bottom pb-2">Shop Location</h4>
                    @if($shop->latitude && $shop->longitude)
                        <div class="card border-0 shadow-sm rounded-4 p-3">
                            <div id="shop-map"></div>
                            <p class="text-muted small mt-3 mb-0">
                                <i class="bi bi-geo-alt-fill me-1"></i>
                                {{ $shop->address ?? 'Location pinned on map' }}
                            </p>
                        </div>
                    @else
                        <div class="alert alert-light border rounded-4">
                            <i class="bi bi-geo-alt me-2"></i>
                            Map location is not available for this shop yet.
                        </div>
                    @endif
                </div>

            </div>

            {{-- Right: Booking Panel --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow rounded-4 p-4 sticky-top" style="top: 90px;">
                    <h5 class="fw-bold mb-2">Book This Shop</h5>
                    <p class="text-muted small mb-4">Select your service first, then continue to choose barber, date, and time.</p>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @if($shop->average_rating)
                            <span class="badge rounded-pill bg-light text-dark border">
                                ⭐ {{ number_format($shop->average_rating, 1) }} rating
                            </span>
                        @endif

                        @if($shop->opening_time && $shop->closing_time)
                            <span class="badge rounded-pill bg-light text-dark border">
                                🕒 {{ \Carbon\Carbon::parse($shop->opening_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($shop->closing_time)->format('g:i A') }}
                            </span>
                        @endif

                        <span class="badge rounded-pill bg-light text-dark border">
                            👨‍🔧 {{ $shop->barbers->count() }} barber{{ $shop->barbers->count() > 1 ? 's' : '' }}
                        </span>
                    </div>

                    <div id="no-service-msg" class="alert alert-light border text-center small mb-3">
                        <i class="bi bi-hand-index me-1"></i> Select a service from the list
                    </div>

                    <div id="selected-services-list" class="d-none mb-3">
                        <div class="small text-muted mb-2 fw-semibold text-uppercase">Selected Services</div>
                        <div id="selected-items"></div>
                        <div class="border-top mt-2 pt-2 d-flex justify-content-between">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold text-trim-orange" id="summary-total">Rs. 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small mt-1">
                            <span>Est. Duration</span>
                            <span id="summary-duration">0 min</span>
                        </div>
                    </div>

                    <div id="book-btn-wrapper" class="d-none">
                        @auth
                            <form action="{{ route('frontend.bookings.create', ['shop' => $shop->id]) }}" method="GET">
                                <input type="hidden" name="service_ids" id="service-ids-input">
                                <button type="submit" class="btn btn-trim text-white w-100 py-3 fw-bold rounded-3 shadow-sm">
                                    <i class="bi bi-calendar-check me-2"></i>Continue to Booking
                                </button>
                            </form>
                            <p class="text-center text-muted small mt-2 mb-0">
                                You will confirm date &amp; time on the next step
                            </p>
                        @endauth
                    </div>

                    @guest
                        <div class="alert alert-warning small mt-3 mb-0 rounded-3">
                            <i class="bi bi-lock-fill me-1"></i>
                            <a href="{{ route('login') }}" class="fw-semibold">Log in</a> to book an appointment.
                        </div>
                    @endguest

                </div>
            </div>

        </div>
    </div>
</section>

@push('scripts')
@if($shop->latitude && $shop->longitude)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lat = {{ $shop->latitude }};
        const lng = {{ $shop->longitude }};

        const map = L.map('shop-map').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup(@json($shop->name))
            .openPopup();
    });
</script>
@endif

<script>
    let selected = {};

    function toggleService(id, name, price, duration, category) {
        const card = document.getElementById('service-card-' + id);
        const btn = document.getElementById('select-btn-' + id);

        if (selected[id]) {
            delete selected[id];
            card.classList.remove('is-selected');
            btn.textContent = 'Select';
            btn.classList.remove('btn-trim', 'text-white');
            btn.classList.add('btn-outline-trim');
        } else {
            Object.keys(selected).forEach(function(existingId) {
                if (selected[existingId].category === category) {
                    const oldCard = document.getElementById('service-card-' + existingId);
                    const oldBtn = document.getElementById('select-btn-' + existingId);
                    if (oldCard) oldCard.classList.remove('is-selected');
                    if (oldBtn) {
                        oldBtn.textContent = 'Select';
                        oldBtn.classList.remove('btn-trim', 'text-white');
                        oldBtn.classList.add('btn-outline-trim');
                    }
                    delete selected[existingId];
                }
            });

            selected[id] = { name, price, duration, category };
            card.classList.add('is-selected');
            btn.textContent = 'Selected ✓';
            btn.classList.remove('btn-outline-trim');
            btn.classList.add('btn-trim', 'text-white');
        }

        updatePanel();
    }

    function removeService(id) {
        const card = document.getElementById('service-card-' + id);
        const btn = document.getElementById('select-btn-' + id);
        if (card) card.classList.remove('is-selected');
        if (btn) {
            btn.textContent = 'Select';
            btn.classList.remove('btn-trim', 'text-white');
            btn.classList.add('btn-outline-trim');
        }
        delete selected[id];
        updatePanel();
    }

    function updatePanel() {
        const ids = Object.keys(selected);
        const noMsg = document.getElementById('no-service-msg');
        const list = document.getElementById('selected-services-list');
        const bookWrapper = document.getElementById('book-btn-wrapper');
        const itemsContainer = document.getElementById('selected-items');
        const totalEl = document.getElementById('summary-total');
        const durationEl = document.getElementById('summary-duration');
        const idsInput = document.getElementById('service-ids-input');

        if (ids.length === 0) {
            noMsg.classList.remove('d-none');
            list.classList.add('d-none');
            bookWrapper.classList.add('d-none');
            return;
        }

        noMsg.classList.add('d-none');
        list.classList.remove('d-none');
        bookWrapper.classList.remove('d-none');

        itemsContainer.innerHTML = '';
        let total = 0;
        let totalDur = 0;

        ids.forEach(function(id) {
            const s = selected[id];
            total += s.price;
            totalDur += s.duration;

            const row = document.createElement('div');
            row.className = 'd-flex justify-content-between align-items-center mb-2 small selected-service-chip rounded-3 px-2 py-2';
            row.innerHTML =
                '<div class="d-flex align-items-center gap-2">' +
                    '<button type="button" onclick="removeService(' + id + ')" class="btn btn-link btn-sm p-0 text-muted text-decoration-none">✕</button>' +
                    '<div>' +
                        '<div class="fw-semibold">' + s.name + '</div>' +
                        '<div class="text-muted" style="font-size: 12px;">' + s.duration + ' min</div>' +
                    '</div>' +
                '</div>' +
                '<span class="fw-semibold">Rs. ' + parseFloat(s.price).toFixed(2) + '</span>';
            itemsContainer.appendChild(row);
        });

        totalEl.textContent = 'Rs. ' + total.toFixed(2);
        durationEl.textContent = totalDur + ' min';

        if (idsInput) idsInput.value = ids.join(',');
    }
</script>
@endpush

@endsection
