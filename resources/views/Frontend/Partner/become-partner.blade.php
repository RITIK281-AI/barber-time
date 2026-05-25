@extends('frontend.layouts.app')

@section('title', 'Become a Partner — BarberTime')

@section('content')
<div class="container py-5" style="margin-top: 70px;">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Header --}}
            <div class="text-center mb-5">
                <h1 class="fw-bold">Become a BarberTime Partner</h1>
                <p class="text-muted fs-5">
                    Join Nepal's growing network of professional barber shops.
                    Register your shop and start receiving online bookings.
                </p>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form --}}
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('frontend.shops.partner.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                        {{-- Section 1: Owner Info --}}
                        <h5 class="fw-semibold mb-3 pb-2 border-bottom">Owner Information</h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="owner_name" class="form-label">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="owner_name" id="owner_name"
                                       value="{{ old('owner_name') }}"
                                       class="form-control @error('owner_name') is-invalid @enderror"
                                       required>
                                @error('owner_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" id="email"
                                       value="{{ old('email') }}"
                                       class="form-control @error('email') is-invalid @enderror"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">
                                    Phone Number <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="phone" id="phone"
                                       value="{{ old('phone') }}"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       placeholder="98XXXXXXXX" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Section 2: Shop Details --}}
                        <h5 class="fw-semibold mb-3 pb-2 border-bottom">Barber Shop Details</h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    Shop Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" id="name"
                                       value="{{ old('name') }}"
                                       class="form-control @error('name') is-invalid @enderror"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="district" class="form-label">
                                    District <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="district" id="district"
                                       value="{{ old('district') }}"
                                       class="form-control @error('district') is-invalid @enderror"
                                       placeholder="e.g. Kathmandu" required>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label">
                                    Shop Address <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="address" id="address"
                                       value="{{ old('address') }}"
                                       class="form-control @error('address') is-invalid @enderror"
                                       placeholder="e.g. Thamel, Near Garden of Dreams" required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Section 3: Business Info --}}
                        <h5 class="fw-semibold mb-3 pb-2 border-bottom">Business Information</h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="business_license_number" class="form-label">
                                    Business License Number <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="business_license_number" id="business_license_number"
                                    value="{{ old('business_license_number') }}"
                                    class="form-control @error('business_license_number') is-invalid @enderror"
                                    required>
                                @error('business_license_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="pan_number" class="form-label">PAN Number (Optional)</label>
                                <input type="text" name="pan_number" id="pan_number"
                                    value="{{ old('pan_number') }}"
                                    class="form-control @error('pan_number') is-invalid @enderror">
                                @error('pan_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="business_registration_date" class="form-label">
                                    Business Registration Date (Optional)
                                </label>
                                <input type="date" name="business_registration_date" id="business_registration_date"
                                    value="{{ old('business_registration_date') }}"
                                    class="form-control @error('business_registration_date') is-invalid @enderror">
                                @error('business_registration_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="city" class="form-label">City (Optional)</label>
                                <input type="text" name="city" id="city"
                                    value="{{ old('city') }}"
                                    class="form-control @error('city') is-invalid @enderror"
                                    placeholder="e.g. Thamel">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="number_of_barbers" class="form-label">
                                    No. of Barbers <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="number_of_barbers" id="number_of_barbers"
                                    value="{{ old('number_of_barbers', 1) }}" min="1" max="50"
                                    class="form-control @error('number_of_barbers') is-invalid @enderror"
                                    required>
                                @error('number_of_barbers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="number_of_chairs" class="form-label">
                                    No. of Chairs <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="number_of_chairs" id="number_of_chairs"
                                    value="{{ old('number_of_chairs', 1) }}" min="1" max="50"
                                    class="form-control @error('number_of_chairs') is-invalid @enderror"
                                    required>
                                @error('number_of_chairs')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="shop_area_sqft" class="form-label">Shop Area (sq ft) (Optional)</label>
                                <input type="number" name="shop_area_sqft" id="shop_area_sqft"
                                    value="{{ old('shop_area_sqft') }}" min="1"
                                    class="form-control @error('shop_area_sqft') is-invalid @enderror">
                                @error('shop_area_sqft')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="years_of_experience" class="form-label">Years of Experience (Optional)</label>
                                <input type="number" name="years_of_experience" id="years_of_experience"
                                    value="{{ old('years_of_experience') }}" min="0" max="100"
                                    class="form-control @error('years_of_experience') is-invalid @enderror">
                                @error('years_of_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="emergency_contact_name" class="form-label">Emergency Contact Name (Optional)</label>
                                <input type="text" name="emergency_contact_name" id="emergency_contact_name"
                                    value="{{ old('emergency_contact_name') }}"
                                    class="form-control @error('emergency_contact_name') is-invalid @enderror">
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone (Optional)</label>
                                <input type="text" name="emergency_contact_phone" id="emergency_contact_phone"
                                    value="{{ old('emergency_contact_phone') }}"
                                    placeholder="98XXXXXXXX"
                                    class="form-control @error('emergency_contact_phone') is-invalid @enderror">
                                @error('emergency_contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="services_offered" class="form-label">
                                    Services You Offer <span class="text-danger">*</span>
                                </label>
                                <textarea name="services_offered" id="services_offered" rows="3"
                                        class="form-control @error('services_offered') is-invalid @enderror"
                                        placeholder="e.g. Haircut, Beard Trim, Hair Coloring, Facial..."
                                        required>{{ old('services_offered') }}</textarea>
                                @error('services_offered')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label">Why do you want to join BarberTime? (Optional)</label>
                                <textarea name="description" id="description" rows="3"
                                        class="form-control @error('description') is-invalid @enderror"
                                        placeholder="Tell us about your shop...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="shop_image" class="form-label">Shop Photo / Logo (Optional)</label>
                                <input type="file" name="shop_image" id="shop_image"
                                    accept="image/jpeg,image/png,image/webp"
                                    class="form-control @error('shop_image') is-invalid @enderror">
                                <div class="form-text">JPG, PNG or WebP. Max 2MB.</div>
                                @error('shop_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="shop_license" class="form-label">
                                    Barber Shop License <span class="text-danger">*</span>
                                </label>
                                <input type="file" name="shop_license" id="shop_license"
                                    accept=".jpg,.jpeg,.png,.pdf"
                                    class="form-control @error('shop_license') is-invalid @enderror"
                                    required>
                                <div class="form-text">
                                    <i class="bi bi-shield-lock me-1 text-success"></i>
                                    JPG, PNG or PDF — max 5MB. Only reviewed by BarberTime admins.
                                </div>
                                @error('shop_license')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-3">
                                <label for="registration_document" class="form-label">
                                    Shop Registration Document (Optional)
                                </label>
                                <input type="file" name="registration_document" id="registration_document"
                                    accept=".jpg,.jpeg,.png,.pdf"
                                    class="form-control @error('registration_document') is-invalid @enderror">
                                <div class="form-text">
                                    <i class="bi bi-file-earmark-check me-1 text-primary"></i>
                                    JPG, PNG or PDF — max 5MB.
                                </div>
                                @error('registration_document')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mt-3">
                                <label for="tax_clearance_document" class="form-label">
                                    Tax Clearance Document (Optional)
                                </label>
                                <input type="file" name="tax_clearance_document" id="tax_clearance_document"
                                    accept=".jpg,.jpeg,.png,.pdf"
                                    class="form-control @error('tax_clearance_document') is-invalid @enderror">
                                <div class="form-text">
                                    <i class="bi bi-file-earmark-ruled me-1 text-danger"></i>
                                    JPG, PNG or PDF — max 5MB.
                                </div>
                                @error('tax_clearance_document')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="d-grid d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-trim text-white px-4 py-2">
                                Submit Partnership Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
