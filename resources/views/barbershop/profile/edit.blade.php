@extends('barbershop.layouts.shop')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h2 class="page-title mb-1">Shop Profile</h2>
        <p class="page-subtitle mb-0">Manage your shop details and appearance.</p>
    </div>

    {{-- success message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- approval status badge --}}
    <div class="mb-4">
        @if($shop->status === 'approved')
            <span class="shop-status-badge status-approved">
                <i class="bi bi-check-circle me-1"></i> Approved
            </span>
        @elseif($shop->status === 'pending')
            <span class="shop-status-badge status-pending">
                <i class="bi bi-hourglass-split me-1"></i> Pending Approval
            </span>
        @elseif($shop->status === 'suspended')
            <span class="shop-status-badge status-suspended">
                <i class="bi bi-slash-circle me-1"></i> Suspended
            </span>
        @else
            <span class="shop-status-badge status-rejected">
                <i class="bi bi-x-circle me-1"></i> Rejected
            </span>
        @endif

        @if($shop->admin_remarks)
            <div class="admin-remarks-box mt-3">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Admin Note:</strong> {{ $shop->admin_remarks }}
            </div>
        @endif
    </div>

    <form action="{{ route('shop.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- left column: shop image --}}
            <div class="col-lg-3 col-md-4">
                <div class="shop-card">
                    <div class="shop-card-body text-center">

                        @if($shop->shop_image)
                            <img src="{{ Storage::url($shop->shop_image) }}"
                                 alt="Shop Image"
                                 class="shop-profile-img mx-auto d-block mb-3"
                                 id="shopImagePreview">
                        @else
                            <div class="shop-image-placeholder mx-auto mb-3" id="shopImagePreview">
                                <i class="bi bi-shop"></i>
                            </div>
                        @endif

                        <h6 class="mb-1 fw-bold" style="color:var(--text-primary);">{{ $shop->name }}</h6>
                        <p class="small mb-3" style="color:var(--text-muted);">{{ $shop->district ?? 'Nepal' }}</p>

                        <label for="shopImageInput" class="btn-upload-shop w-100">
                            <i class="bi bi-camera me-1"></i> Change Photo
                        </label>
                        <input type="file" id="shopImageInput" name="shop_image"
                               class="d-none" accept="image/*"
                               onchange="previewShopImage(this)">
                        <p class="small mt-2 mb-0" style="color:var(--text-muted);">JPG, PNG or WebP · Max 2MB</p>

                        @error('shop_image')
                            <p class="text-danger small mt-2 mb-0">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- shop stats (read-only) --}}
                <div class="shop-card mt-4">
                    <div class="shop-card-header">
                        <h6><i class="bi bi-bar-chart me-2 text-primary"></i>Shop Stats</h6>
                    </div>
                    <div class="shop-card-body">
                        <div class="profile-info-row">
                            <span class="profile-info-label">Rating</span>
                            <span class="profile-info-value">
                                <i class="bi bi-star-fill text-warning me-1"></i>
                                {{ number_format($shop->average_rating, 1) }}
                            </span>
                        </div>
                        <div class="profile-info-row">
                            <span class="profile-info-label">Reviews</span>
                            <span class="profile-info-value">{{ $shop->total_reviews }}</span>
                        </div>
                        <div class="profile-info-row">
                            <span class="profile-info-label">Barbers</span>
                            <span class="profile-info-value">{{ $shop->barbers()->count() }}</span>
                        </div>
                        <div class="profile-info-row">
                            <span class="profile-info-label">Services</span>
                            <span class="profile-info-value">{{ $shop->services()->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- right column: forms --}}
            <div class="col-lg-9 col-md-8">

                {{-- basic info --}}
                <div class="shop-card mb-4">
                    <div class="shop-card-header">
                        <h6><i class="bi bi-shop-window me-2 text-primary"></i>Basic Information</h6>
                    </div>
                    <div class="shop-card-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="shop-label">Shop Name <span class="text-danger">*</span></label>
                                <input type="text" name="name"
                                       class="shop-input @error('name') is-invalid @enderror"
                                       value="{{ old('name', $shop->name) }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="shop-label">Phone Number</label>
                                <input type="text" name="phone"
                                       class="shop-input @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $shop->phone) }}"
                                       placeholder="e.g. 9800000000">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="shop-label">Email Address</label>
                                <input type="email" name="email"
                                       class="shop-input @error('email') is-invalid @enderror"
                                       value="{{ old('email', $shop->email) }}"
                                       placeholder="shop@example.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="shop-label">District</label>
                                <input type="text" name="district"
                                       class="shop-input @error('district') is-invalid @enderror"
                                       value="{{ old('district', $shop->district) }}"
                                       placeholder="e.g. Kathmandu">
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="shop-label">Address</label>
                                <input type="text" name="address"
                                       class="shop-input @error('address') is-invalid @enderror"
                                       value="{{ old('address', $shop->address) }}"
                                       placeholder="Full street address">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="shop-label">Description</label>
                                <textarea name="description" rows="3"
                                          class="shop-input @error('description') is-invalid @enderror"
                                          placeholder="Tell customers about your shop...">{{ old('description', $shop->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-2">
                                <h6 class="mb-2" style="color:var(--text-primary);">Change Password (Optional)</h6>
                                <p class="small mb-0" style="color:var(--text-muted);">Leave these fields empty to keep your current password.</p>
                            </div>

                            @if($errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation'))
                                <div class="col-12">
                                    <div class="alert alert-danger py-2 mb-1">
                                        <ul class="mb-0 ps-3">
                                            @error('current_password')<li>{{ $message }}</li>@enderror
                                            @error('new_password')<li>{{ $message }}</li>@enderror
                                            @error('new_password_confirmation')<li>{{ $message }}</li>@enderror
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-4">
                                <label class="shop-label">Current Password</label>
                                <div class="position-relative">
                                    <input type="password"
                                           id="shop_current_password"
                                           name="current_password"
                                         class="shop-input pe-5 @error('current_password') is-invalid @enderror"
                                         autocomplete="off"
                                         data-clear-on-load="true">
                                    <button type="button"
                                            class="password-toggle-btn btn btn-link position-absolute top-50 end-0 translate-middle-y border-0 text-muted"
                                            data-password-target="shop_current_password"
                                            aria-label="Show password"
                                            aria-pressed="false"
                                            style="z-index:3;">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="shop-label">New Password</label>
                                <div class="position-relative">
                                    <input type="password"
                                           id="shop_new_password"
                                           name="new_password"
                                           class="shop-input pe-5 @error('new_password') is-invalid @enderror"
                                         minlength="9"
                                         autocomplete="new-password"
                                         data-clear-on-load="true">
                                    <button type="button"
                                            class="password-toggle-btn btn btn-link position-absolute top-50 end-0 translate-middle-y border-0 text-muted"
                                            data-password-target="shop_new_password"
                                            aria-label="Show password"
                                            aria-pressed="false"
                                            style="z-index:3;">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('new_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="shop-label">Confirm New Password</label>
                                <div class="position-relative">
                                    <input type="password"
                                           id="shop_new_password_confirmation"
                                           name="new_password_confirmation"
                                           class="shop-input pe-5"
                                         minlength="9"
                                         autocomplete="new-password"
                                         data-clear-on-load="true">
                                    <button type="button"
                                            class="password-toggle-btn btn btn-link position-absolute top-50 end-0 translate-middle-y border-0 text-muted"
                                            data-password-target="shop_new_password_confirmation"
                                            aria-label="Show password"
                                            aria-pressed="false"
                                            style="z-index:3;">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-12">
                                <small class="text-muted">Password must be at least 9 characters and include an uppercase letter, number, and symbol.</small>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- working hours --}}
                <div class="shop-card mb-4">
                    <div class="shop-card-header">
                        <h6><i class="bi bi-clock me-2 text-primary"></i>Working Hours</h6>
                    </div>
                    <div class="shop-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="shop-label">Opening Time</label>
                                <input type="time" name="opening_time"
                                       class="shop-input"
                                       value="{{ old('opening_time', $shop->opening_time ?? '09:00') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="shop-label">Closing Time</label>
                                <input type="time" name="closing_time"
                                       class="shop-input"
                                       value="{{ old('closing_time', $shop->closing_time ?? '18:00') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- shop location map picker --}}
                <div class="shop-card mb-4">
                    <div class="shop-card-header">
                        <h6><i class="bi bi-geo-alt me-2 text-primary"></i>Shop Location on Map</h6>
                    </div>
                    <div class="shop-card-body">
                        <p class="small mb-3" style="color:var(--text-muted);">
                            Click anywhere on the map to pin your shop, or drag the marker to adjust. Customers use this to find you nearby.
                        </p>

                        {{-- hidden inputs submitted with the form --}}
                        <input type="hidden" name="latitude"  id="latInput"  value="{{ old('latitude',  $shop->latitude) }}">
                        <input type="hidden" name="longitude" id="lngInput"  value="{{ old('longitude', $shop->longitude) }}">

                        <div class="mb-3 d-flex align-items-center gap-3 flex-wrap">
                            <span class="small text-muted">
                                Pinned at:
                                <span id="coordDisplay">
                                    @if($shop->latitude && $shop->longitude)
                                        {{ $shop->latitude }}, {{ $shop->longitude }}
                                    @else
                                        Not set — click the map to pin
                                    @endif
                                </span>
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="autoDetectLocation()">
                                <i class="bi bi-crosshair me-1"></i> Use My Location
                            </button>
                        </div>

                        @error('latitude')
                            <p class="text-danger small mb-2">{{ $message }}</p>
                        @enderror
                        @error('longitude')
                            <p class="text-danger small mb-2">{{ $message }}</p>
                        @enderror

                        <div id="shopLocationMap" style="height: 350px; border-radius: 10px; overflow: hidden;"></div>
                    </div>
                </div>

                {{-- business details --}}
                <div class="shop-card mb-4">
                    <div class="shop-card-header">
                        <h6><i class="bi bi-briefcase me-2 text-primary"></i>Business Details</h6>
                    </div>
                    <div class="shop-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="shop-label">Business License Number</label>
                                <input type="text" name="business_license_number"
                                       class="shop-input @error('business_license_number') is-invalid @enderror"
                                       value="{{ old('business_license_number', $shop->business_license_number) }}"
                                       placeholder="e.g. BL-12345">
                                @error('business_license_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="shop-label">Business Registration Date</label>
                                <input type="date" name="business_registration_date"
                                       class="shop-input @error('business_registration_date') is-invalid @enderror"
                                       value="{{ old('business_registration_date', $shop->business_registration_date) }}">
                                @error('business_registration_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="shop-label">No. of Barbers</label>
                                <input type="number" name="number_of_barbers" min="1" max="50"
                                       class="shop-input @error('number_of_barbers') is-invalid @enderror"
                                       value="{{ old('number_of_barbers', $shop->number_of_barbers) }}">
                                @error('number_of_barbers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="shop-label">No. of Chairs</label>
                                <input type="number" name="number_of_chairs" min="1" max="50"
                                       class="shop-input @error('number_of_chairs') is-invalid @enderror"
                                       value="{{ old('number_of_chairs', $shop->number_of_chairs) }}">
                                @error('number_of_chairs')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="shop-label">Shop Area (sq ft)</label>
                                <input type="number" name="shop_area_sqft" min="1"
                                       class="shop-input @error('shop_area_sqft') is-invalid @enderror"
                                       value="{{ old('shop_area_sqft', $shop->shop_area_sqft) }}">
                                @error('shop_area_sqft')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="shop-label">Years of Experience</label>
                                <input type="number" name="years_of_experience" min="0" max="100"
                                       class="shop-input @error('years_of_experience') is-invalid @enderror"
                                       value="{{ old('years_of_experience', $shop->years_of_experience) }}">
                                @error('years_of_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="shop-label">City</label>
                                <input type="text" name="city"
                                       class="shop-input @error('city') is-invalid @enderror"
                                       value="{{ old('city', $shop->city) }}"
                                       placeholder="e.g. Thamel">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="shop-label">Emergency Contact Name</label>
                                <input type="text" name="emergency_contact_name"
                                       class="shop-input @error('emergency_contact_name') is-invalid @enderror"
                                       value="{{ old('emergency_contact_name', $shop->emergency_contact_name) }}">
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="shop-label">Emergency Contact Phone</label>
                                <input type="text" name="emergency_contact_phone"
                                       class="shop-input @error('emergency_contact_phone') is-invalid @enderror"
                                       value="{{ old('emergency_contact_phone', $shop->emergency_contact_phone) }}"
                                       placeholder="98XXXXXXXX">
                                @error('emergency_contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="shop-label">Services Offered</label>
                                <textarea name="services_offered" rows="3"
                                          class="shop-input @error('services_offered') is-invalid @enderror"
                                          placeholder="e.g. Haircut, Beard Trim, Hair Coloring...">{{ old('services_offered', $shop->services_offered) }}</textarea>
                                @error('services_offered')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- read-only owner info --}}
                <div class="shop-card mb-4">
                    <div class="shop-card-header">
                        <h6><i class="bi bi-person me-2 text-primary"></i>Owner Information (Read-only)</h6>
                    </div>
                    <div class="shop-card-body">
                        <p class="small mb-3" style="color:var(--text-muted);">
                            These details were submitted during registration and can only be changed by the admin.
                        </p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="shop-label">Owner Name</label>
                                <input type="text" class="shop-input" value="{{ $shop->owner_name ?? '—' }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="shop-label">PAN Number</label>
                                <input type="text" class="shop-input" value="{{ $shop->pan_number ?? '—' }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- save button --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-shop-primary px-4">
                        <i class="bi bi-check2 me-1"></i> Save Changes
                    </button>
                    <a href="{{ route('shop.dashboard') }}" class="btn-shop-secondary px-4">
                        Cancel
                    </a>
                </div>

            </div>
        </div>
    </form>

</div>

{{-- leaflet CSS and JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // preview image before upload
    function previewShopImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('shopImagePreview');
                if (preview.tagName === 'DIV') {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'shop-profile-img mx-auto d-block mb-3';
                    img.id = 'shopImagePreview';
                    preview.replaceWith(img);
                } else {
                    preview.src = e.target.result;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // default center is Kathmandu if no saved location
    const defaultLat = {{ $shop->latitude  ?? 27.7172 }};
    const defaultLng = {{ $shop->longitude ?? 85.3240 }};

    const locationMap = L.map('shopLocationMap').setView([defaultLat, defaultLng], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(locationMap);

    let shopMarker = null;

    // place existing saved pin on the map
    @if($shop->latitude && $shop->longitude)
        shopMarker = L.marker([{{ $shop->latitude }}, {{ $shop->longitude }}], { draggable: true }).addTo(locationMap);
        shopMarker.on('dragend', function(e) {
            updatePin(e.target.getLatLng().lat, e.target.getLatLng().lng);
        });
    @endif

    // click map to place or move the pin
    locationMap.on('click', function(e) {
        if (shopMarker) {
            shopMarker.setLatLng(e.latlng);
        } else {
            shopMarker = L.marker(e.latlng, { draggable: true }).addTo(locationMap);
            shopMarker.on('dragend', function(ev) {
                updatePin(ev.target.getLatLng().lat, ev.target.getLatLng().lng);
            });
        }
        updatePin(e.latlng.lat, e.latlng.lng);
    });

    // write lat/lng into hidden inputs and update the display text
    function updatePin(lat, lng) {
        document.getElementById('latInput').value       = lat.toFixed(7);
        document.getElementById('lngInput').value       = lng.toFixed(7);
        document.getElementById('coordDisplay').textContent = lat.toFixed(5) + ', ' + lng.toFixed(5);
    }

    // auto detect location using browser GPS
    function autoDetectLocation() {
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser.');
            return;
        }
        navigator.geolocation.getCurrentPosition(function(pos) {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            locationMap.setView([lat, lng], 16);
            if (shopMarker) {
                shopMarker.setLatLng([lat, lng]);
            } else {
                shopMarker = L.marker([lat, lng], { draggable: true }).addTo(locationMap);
                shopMarker.on('dragend', function(e) {
                    updatePin(e.target.getLatLng().lat, e.target.getLatLng().lng);
                });
            }
            updatePin(lat, lng);
        }, function() {
            alert('Could not detect location. Please allow location access and try again.');
        });
    }

    document.querySelectorAll('.password-toggle-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            const targetId = button.getAttribute('data-password-target');
            const input = document.getElementById(targetId);

            if (!input) {
                return;
            }

            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';

            const icon = button.querySelector('i');
            if (icon) {
                icon.classList.toggle('bi-eye', !isHidden);
                icon.classList.toggle('bi-eye-slash', isHidden);
            }

            button.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
            button.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        });
    });

    document.querySelectorAll('input[type="password"][data-clear-on-load="true"]').forEach(function (input) {
        input.value = '';
    });
</script>
@endsection
