@extends('admin.layouts.app')

@section('content')

<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Edit Barber Shop</h2>
            <p class="text-muted mb-0">Update details for <strong>{{ $barberShop->name }}</strong></p>
        </div>
        <a href="{{ route('admin.shops.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-md-5">

            <form action="{{ route('admin.shops.update', $barberShop) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    <div class="col-md-6">
                        <label class="form-label fw-medium">Shop Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $barberShop->name) }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium">Phone Number</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $barberShop->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Opening & Closing Time --}}
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Opening Time <span class="text-danger">*</span></label>
                        <input type="time" name="opening_time"
                               class="form-control @error('opening_time') is-invalid @enderror"
                               value="{{ old('opening_time', \Carbon\Carbon::parse($barberShop->opening_time)->format('H:i')) }}"
                               required>
                        @error('opening_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium">Closing Time <span class="text-danger">*</span></label>
                        <input type="time" name="closing_time"
                               class="form-control @error('closing_time') is-invalid @enderror"
                               value="{{ old('closing_time', \Carbon\Carbon::parse($barberShop->closing_time)->format('H:i')) }}"
                               required>
                        @error('closing_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted">Must be after opening time.</div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-medium">Full Address <span class="text-danger">*</span></label>
                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                               value="{{ old('address', $barberShop->address) }}" required>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium">Latitude <span class="text-danger">*</span></label>
                        <input type="number" step="any" name="latitude" class="form-control @error('latitude') is-invalid @enderror"
                               value="{{ old('latitude', $barberShop->latitude) }}" required>
                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium">Longitude <span class="text-danger">*</span></label>
                        <input type="number" step="any" name="longitude" class="form-control @error('longitude') is-invalid @enderror"
                               value="{{ old('longitude', $barberShop->longitude) }}" required>
                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="pending"  {{ old('status', $barberShop->status) === 'pending'  ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ old('status', $barberShop->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ old('status', $barberShop->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium">Current Shop Image</label>
                        @if($barberShop->shop_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $barberShop->shop_image) }}"
                                     alt="{{ $barberShop->name }}"
                                     class="img-thumbnail shadow-sm"
                                     style="max-width: 180px; height: auto; object-fit: cover;">
                            </div>
                        @else
                            <p class="text-muted mt-2">No image uploaded yet</p>
                        @endif
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-medium">Change / Upload New Image (optional)</label>
                        <input type="file" name="shop_image" class="form-control @error('shop_image') is-invalid @enderror"
                               accept="image/jpeg,image/png,image/webp">
                        @error('shop_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Leave empty to keep current image</small>
                    </div>

                </div>

                <div class="d-flex justify-content-end mt-5">
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                        <i class="bi bi-save me-2"></i> Update Barber Shop
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
