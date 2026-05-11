@extends('frontend.layouts.app')

@section('title', 'Find Barber Shops')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .tt-pagination .pagination { gap: 4px; }
    .tt-pagination .page-link {
        border-radius: 8px !important;
        border: 1px solid #e0e0e0;
        color: #333;
        padding: 8px 14px;
        font-size: 0.88rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .tt-pagination .page-link:hover {
        background-color: var(--trim-orange-light);
        border-color: var(--trim-orange);
        color: var(--trim-orange);
    }
    .tt-pagination .page-item.active .page-link {
        background-color: var(--trim-orange);
        border-color: var(--trim-orange);
        color: #fff;
    }
    .tt-pagination .page-item.disabled .page-link {
        color: #ccc;
        border-color: #eee;
        background: #fafafa;
    }
</style>
@endpush

@section('content')
    <section class="py-5 mt-5">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold">Discover Top Barber Shops</h1>
                <p class="lead text-muted">Book your next fresh cut — browse highly-rated shops near you with real-time availability.</p>
            </div>

            {{-- search + filter bar --}}
            <div class="row justify-content-center mb-2">
                <div class="col-lg-10">
                    <form method="GET" action="{{ route('frontend.shops.index') }}">
                        <div class="row g-2">

                            {{-- search input --}}
                            <div class="col-lg-5 col-md-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0"
                                        placeholder="Search by shop name or location..."
                                        value="{{ request('search') }}">
                                </div>
                            </div>

                            {{-- district filter --}}
                            <div class="col-lg-3 col-md-4">
                                <select name="district" class="form-select">
                                    <option value="">All Districts</option>
                                    @foreach($districts as $d)
                                        <option value="{{ $d }}" {{ request('district') == $d ? 'selected' : '' }}>
                                            {{ $d }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- rating filter --}}
                            <div class="col-lg-2 col-md-4">
                                <select name="rating" class="form-select">
                                    <option value="">Any Rating</option>
                                    <option value="4.5" {{ request('rating') == '4.5' ? 'selected' : '' }}>⭐ 4.5 & above</option>
                                    <option value="4"   {{ request('rating') == '4'   ? 'selected' : '' }}>⭐ 4.0 & above</option>
                                    <option value="3"   {{ request('rating') == '3'   ? 'selected' : '' }}>⭐ 3.0 & above</option>
                                </select>
                            </div>

                            {{-- search + clear buttons --}}
                            <div class="col-lg-2 col-md-4">
                                <div class="d-flex gap-2">
                                    <button class="btn tt-btn-primary flex-grow-1" type="submit">
                                        Find Shops
                                    </button>
                                    @if(request('search') || request('district') || request('rating'))
                                        <a href="{{ route('frontend.shops.index') }}"
                                            class="btn tt-btn-ghost" title="Clear filters">
                                            <i class="bi bi-x-lg"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </form>

                    {{-- active filter tags + result count --}}
                    @if(request('search') || request('district') || request('rating'))
                        <div class="d-flex gap-2 flex-wrap mt-3 align-items-center">
                            <small class="text-muted">Filters:</small>

                            @if(request('search'))
                                <span class="badge rounded-pill border text-dark bg-white px-3 py-2">
                                    <i class="bi bi-search me-1"></i>{{ request('search') }}
                                </span>
                            @endif

                            @if(request('district'))
                                <span class="badge rounded-pill border text-dark bg-white px-3 py-2">
                                    <i class="bi bi-geo-alt me-1"></i>{{ request('district') }}
                                </span>
                            @endif

                            @if(request('rating'))
                                <span class="badge rounded-pill border text-dark bg-white px-3 py-2">
                                    <i class="bi bi-star me-1"></i>{{ request('rating') }}+ stars
                                </span>
                            @endif

                            <small class="text-muted ms-auto">{{ $shops->total() }} shop(s) found</small>
                        </div>
                    @endif

                </div>
            </div>

            {{-- near me button --}}
            <div class="row justify-content-center mb-4">
                <div class="col-lg-10">
                    <button class="btn btn-outline-secondary btn-sm" type="button"
                            id="nearMeBtn" onclick="findNearMe()">
                        <i class="bi bi-geo-alt-fill me-1"></i> Find Shops Near Me
                    </button>
                </div>
            </div>

            {{-- location permission error alert (hidden until permission denied) --}}
            <div id="locationErrorAlert" class="alert alert-warning alert-dismissible fade d-none mb-4" role="alert">
                <div class="d-flex align-items-start gap-3">
                    <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0 mt-1"></i>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading mb-2">Location Access Required</h5>
                        <p class="mb-2">Please allow location access in your browser to find shops near you.</p>
                        <small class="text-muted d-block">
                            <strong>How to enable location:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Click the location icon in your browser address bar</li>
                                <li>Select "Allow" to share your location with this site</li>
                                <li>Try the "Find Shops Near Me" button again</li>
                            </ul>
                        </small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            {{-- near me map section (hidden until button clicked) --}}
            <div id="nearMeSection" class="mb-5 d-none">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3 px-4">
                        <div>
                            <span class="fw-semibold">
                                <i class="bi bi-geo-alt-fill text-danger me-2"></i>Shops Near You
                            </span>
                            <span id="nearMeCount" class="text-muted small ms-2"></span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center gap-2">
                                <label class="small text-muted mb-0">Radius:</label>
                                <select id="radiusSelect" class="form-select form-select-sm"
                                        style="width: auto;" onchange="loadNearbyShops()">
                                    <option value="5">5 km</option>
                                    <option value="10" selected>10 km</option>
                                    <option value="20">20 km</option>
                                    <option value="50">50 km</option>
                                </select>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary rounded-3" onclick="closeNearMe()">
                                <i class="bi bi-x"></i> Close
                            </button>
                        </div>
                    </div>
                    <div id="nearMeMap" style="height: 400px;"></div>
                    <div id="nearMeList" class="p-4"></div>
                </div>
            </div>

            {{-- shops grid --}}
            <div class="row g-4">
                @forelse($shops ?? [] as $shop)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm overflow-hidden shop-card rounded-4">
                            <div class="position-relative">
                                @if($shop->shop_image)
                                    <img src="{{ asset('storage/' . $shop->shop_image) }}"
                                        class="card-img-top" alt="{{ $shop->name }}"
                                        style="height: 220px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-dark d-flex align-items-center justify-content-center"
                                        style="height: 220px;">
                                        <i class="bi bi-scissors display-1 text-secondary opacity-75"></i>
                                    </div>
                                @endif

                                @if($shop->average_rating && $shop->average_rating >= 4.5)
                                    <span class="position-absolute top-0 end-0 m-3 badge bg-warning text-dark px-3 py-2 rounded-pill">
                                        ⭐ Top Rated
                                    </span>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title fw-bold mb-0">{{ $shop->name }}</h5>
                                </div>

                                <p class="text-muted small mb-3">
                                    <i class="bi bi-geo-alt-fill me-1"></i>
                                    {{ $shop->address ?? 'Location not specified' }}
                                </p>

                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="border rounded-3 p-2 h-100 bg-light-subtle">
                                            <small class="text-muted d-block">Starting Price</small>
                                            <span class="fw-semibold">
                                                {{ $shop->starting_price ? 'रु ' . number_format($shop->starting_price) : 'Not set' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="border rounded-3 p-2 h-100 bg-light-subtle">
                                            <small class="text-muted d-block">Barbers</small>
                                            <span class="fw-semibold">{{ $shop->barbers_count ?? $shop->number_of_barbers ?? 0 }}</span>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="border rounded-3 p-2 h-100 bg-light-subtle">
                                            <small class="text-muted d-block">District</small>
                                            <span class="fw-semibold">{{ $shop->district ?: 'N/A' }}</span>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="border rounded-3 p-2 h-100 bg-light-subtle">
                                            <small class="text-muted d-block">Reviews</small>
                                            <span class="fw-semibold">{{ $shop->total_reviews ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    @if($shop->average_rating)
                                        <span class="text-warning fw-bold">
                                            <i class="bi bi-star-fill"></i>
                                            {{ number_format($shop->average_rating, 1) }}
                                        </span>
                                        <span class="text-muted ms-1 small">({{ $shop->total_reviews }} reviews)</span>
                                    @else
                                        <span class="text-muted small">
                                            <i class="bi bi-star me-1"></i>No reviews yet
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-auto d-grid gap-2">
                                    <a href="{{ route('frontend.shops.show', $shop) }}"
                                    class="btn btn-trim text-white py-3 fw-semibold rounded-3">
                                        Book Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 my-5">
                        <i class="bi bi-shop-window display-1 text-muted mb-4 d-block"></i>
                        @if(request('search') || request('district') || request('rating'))
                            <h4 class="text-muted">No shops match your filters.</h4>
                            <p class="text-muted">Try different search terms or clear the filters.</p>
                            <a href="{{ route('frontend.shops.index') }}" class="btn tt-btn-primary mt-2">
                                Clear Filters
                            </a>
                        @else
                            <h4 class="text-muted">No barber shops found at the moment.</h4>
                            <p class="text-muted">Check back soon!</p>
                        @endif
                    </div>
                @endforelse
            </div>

            {{-- pagination --}}
            @if($shops->hasPages())
                <div class="d-flex justify-content-center mt-4 mb-0 tt-pagination">
                    {{ $shops->links() }}
                </div>
            @endif

        </div>
    </section>

    {{-- partner CTA --}}
    <section class="tt-cta-section py-5 text-white text-center">
        <div class="container py-3">
            <h3 class="fw-bold mb-3">Become a Partner Barber Shop</h3>
            <p class="lead text-white-50 mb-4">
                Grow your business with online bookings, customer insights, and real-time scheduling.
            </p>
            <a href="{{ route('frontend.shops.partner.create') }}" class="btn tt-btn-primary btn-lg px-5">
                Register Your Shop &rarr;
            </a>
        </div>
    </section>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map         = null;
    let userLat     = null;
    let userLng     = null;
    let shopMarkers = [];

    function findNearMe() {
        const btn = document.getElementById('nearMeBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Locating...';

        if (!navigator.geolocation) {
            showLocationError('Geolocation is not supported by your browser.');
            resetBtn();
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(pos) {
                userLat = pos.coords.latitude;
                userLng = pos.coords.longitude;
                hideLocationError();
                showMap();
                loadNearbyShops();
                resetBtn();
            },
            function(error) {
                let errorMsg = 'Could not get your location. ';
                if (error.code === error.PERMISSION_DENIED) {
                    errorMsg = 'Please allow location access to find shops near you.';
                } else if (error.code === error.POSITION_UNAVAILABLE) {
                    errorMsg = 'Location information is unavailable.';
                } else if (error.code === error.TIMEOUT) {
                    errorMsg = 'The request to get user location timed out.';
                }
                showLocationError(errorMsg);
                resetBtn();
            }
        );
    }

    function resetBtn() {
        const btn = document.getElementById('nearMeBtn');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-geo-alt-fill me-1"></i> Find Shops Near Me';
    }

    function showLocationError(message) {
        const errorAlert = document.getElementById('locationErrorAlert');
        if (message && message.includes('Please allow location')) {
            // Show full alert for permission denied
            errorAlert.classList.remove('d-none');
            errorAlert.classList.add('show');
        } else {
            // For other errors, just show a quick message
            errorAlert.classList.remove('d-none');
            errorAlert.classList.add('show');
        }
        // Scroll to alert
        errorAlert.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function hideLocationError() {
        const errorAlert = document.getElementById('locationErrorAlert');
        errorAlert.classList.add('d-none');
        errorAlert.classList.remove('show');
    }

    function showMap() {
        const section = document.getElementById('nearMeSection');
        section.classList.remove('d-none');
        section.scrollIntoView({ behavior: 'smooth', block: 'start' });

        if (!map) {
            map = L.map('nearMeMap').setView([userLat, userLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
        } else {
            map.setView([userLat, userLng], 13);
        }

        L.marker([userLat, userLng], {
            icon: L.divIcon({
                className: '',
                html: '<div style="background:#ff6b00;width:16px;height:16px;border-radius:50%;border:3px solid white;box-shadow:0 0 6px rgba(0,0,0,0.4);"></div>',
                iconSize: [16, 16],
                iconAnchor: [8, 8],
            })
        }).addTo(map).bindPopup('<strong>You are here</strong>').openPopup();
    }

    function loadNearbyShops() {
        const radius = document.getElementById('radiusSelect').value;
        const list   = document.getElementById('nearMeList');
        const count  = document.getElementById('nearMeCount');

        list.innerHTML = '<div class="text-center text-muted py-3"><div class="spinner-border spinner-border-sm me-2"></div>Finding shops...</div>';

        shopMarkers.forEach(m => map.removeLayer(m));
        shopMarkers = [];

        fetch('/shops/nearby?lat=' + userLat + '&lng=' + userLng + '&radius=' + radius)
            .then(res => res.json())
            .then(function(shops) {
                count.textContent = '(' + shops.length + ' found)';

                if (shops.length === 0) {
                    list.innerHTML = '<div class="text-center text-muted py-4">' +
                        '<i class="bi bi-shop-window fs-2 d-block mb-2"></i>' +
                        'No shops found within ' + radius + ' km. Try a larger radius.</div>';
                    return;
                }

                shops.forEach(function(shop) {
                    if (!shop.latitude || !shop.longitude) return;

                    const marker = L.marker([shop.latitude, shop.longitude])
                        .addTo(map)
                        .bindPopup(
                            '<strong>' + shop.name + '</strong><br>' +
                            (shop.address || '') + '<br>' +
                            '<span style="color:#ff6b00;">' + parseFloat(shop.distance).toFixed(1) + ' km away</span><br>' +
                            '<a href="/shops/' + shop.id + '" style="color:#ff6b00;">View Shop →</a>'
                        );
                    shopMarkers.push(marker);
                });

                if (shopMarkers.length > 0) {
                    const group = L.featureGroup(shopMarkers);
                    map.fitBounds(group.getBounds().pad(0.2));
                }

                let html = '<div class="row g-3">';
                shops.forEach(function(shop) {
                    const img = shop.shop_image ? '/storage/' + shop.shop_image : null;

                    html += '<div class="col-md-4 col-sm-6">';
                    html += '<div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">';

                    if (img) {
                        html += '<img src="' + img + '" class="card-img-top" style="height:120px;object-fit:cover;" alt="' + shop.name + '">';
                    } else {
                        html += '<div class="bg-secondary d-flex align-items-center justify-content-center" style="height:120px;"><i class="bi bi-scissors fs-1 text-white opacity-50"></i></div>';
                    }

                    html += '<div class="card-body p-3">';
                    html += '<h6 class="fw-bold mb-1">' + shop.name + '</h6>';
                    html += '<p class="text-muted small mb-1"><i class="bi bi-geo-alt me-1"></i>' + (shop.address || '') + '</p>';
                    html += '<p class="small mb-2" style="color:#ff6b00;"><i class="bi bi-pin-map me-1"></i>' + parseFloat(shop.distance).toFixed(1) + ' km away</p>';

                    if (shop.average_rating) {
                        html += '<div class="text-warning small mb-2"><i class="bi bi-star-fill me-1"></i>' +
                            parseFloat(shop.average_rating).toFixed(1) +
                            ' <span class="text-muted">(' + shop.total_reviews + ')</span></div>';
                    }

                    html += '<a href="/shops/' + shop.id + '" class="btn btn-sm btn-trim text-white w-100 rounded-3">View Shop</a>';
                    html += '</div></div></div>';
                });
                html += '</div>';
                list.innerHTML = html;
            })
            .catch(function() {
                list.innerHTML = '<div class="alert alert-danger m-3">Could not load nearby shops. Please try again.</div>';
            });
    }

    function closeNearMe() {
        document.getElementById('nearMeSection').classList.add('d-none');
        shopMarkers.forEach(m => map.removeLayer(m));
        shopMarkers = [];
        document.getElementById('nearMeCount').textContent = '';
        document.getElementById('nearMeList').innerHTML = '';
    }
</script>
@endpush
