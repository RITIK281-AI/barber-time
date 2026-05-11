@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="admin-title mb-1">Add New Barber Shop</h2>
            <p class="admin-text-muted mb-0">Enter details for a new barber shop.</p>
        </div>
        <a href="{{ route('admin.barbershops.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h5><i class="bi bi-shop me-2 text-primary"></i>Shop Details</h5>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('admin.barbershops.store') }}" method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="admin-label">Shop Name <span class="text-danger">*</span></label>
                        <input type="text" name="name"
                               class="admin-input @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="admin-label">Phone Number</label>
                        <input type="text" name="phone"
                               class="admin-input @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="admin-label">Opening Time <span class="text-danger">*</span></label>
                        <input type="time" name="opening_time"
                               class="admin-input @error('opening_time') is-invalid @enderror"
                               value="{{ old('opening_time', '09:00') }}">
                        @error('opening_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="admin-label">Closing Time <span class="text-danger">*</span></label>
                        <input type="time" name="closing_time"
                               class="admin-input @error('closing_time') is-invalid @enderror"
                               value="{{ old('closing_time', '18:00') }}">
                        <p class="password-hint">Must be after opening time.</p>
                        @error('closing_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="admin-label">Full Address <span class="text-danger">*</span></label>
                        <input type="text" name="address"
                               class="admin-input @error('address') is-invalid @enderror"
                               value="{{ old('address') }}">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="admin-label">Latitude <span class="text-danger">*</span></label>
                        <input type="number" step="any" name="latitude"
                               class="admin-input @error('latitude') is-invalid @enderror"
                               value="{{ old('latitude') }}">
                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="admin-label">Longitude <span class="text-danger">*</span></label>
                        <input type="number" step="any" name="longitude"
                               class="admin-input @error('longitude') is-invalid @enderror"
                               value="{{ old('longitude') }}">
                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="admin-label">Status <span class="text-danger">*</span></label>
                        <select name="status"
                                class="admin-input @error('status') is-invalid @enderror">
                            <option value="" disabled {{ old('status') ? '' : 'selected' }}>Select status</option>
                            <option value="pending"  {{ old('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ old('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="admin-label">Shop Image</label>
                        <input type="file" name="shop_image"
                               class="admin-input @error('shop_image') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/webp">
                        <p class="password-hint">Max 2MB. JPG, PNG or WEBP.</p>
                        @error('shop_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="mt-4 pt-3" style="border-top:1px solid var(--border);">
                    <button type="submit" class="btn-admin-primary px-4">
                        <i class="bi bi-save me-1"></i> Save Barber Shop
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
