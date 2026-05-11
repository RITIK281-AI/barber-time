@extends('barbershop.layouts.shop')

@section('content')

<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Edit Barber: {{ $barber->name }}</h2>
            <p class="text-muted mb-0">Update profile, availability, photo, and performance details</p>
        </div>
        <a href="{{ route('shop.barbers.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-4 p-md-5">
            <form method="POST" action="{{ route('shop.barbers.update', $barber) }}" enctype="multipart/form-data" class="row g-4">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="col-md-6">
                    <label class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $barber->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="col-md-6">
                    <label class="form-label fw-medium">Phone Number</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone', $barber->phone) }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <label class="form-label fw-medium">Email (optional)</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $barber->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Experience -->
                <div class="col-md-6">
                    <label class="form-label fw-medium">Experience (years)</label>
                    <input type="number" name="experience_years" min="0" max="60" class="form-control @error('experience_years') is-invalid @enderror"
                           value="{{ old('experience_years', $barber->experience_years) }}">
                    @error('experience_years')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Bio -->
                <div class="col-12">
                    <label class="form-label fw-medium">Short Bio / Specialization</label>
                    <textarea name="bio" rows="3" class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $barber->bio) }}</textarea>
                    @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Profile Image -->
                <div class="col-md-6">
                    <label class="form-label fw-medium">Profile Photo</label>
                    <input type="file" name="profile_image" class="form-control @error('profile_image') is-invalid @enderror"
                           accept="image/*">
                    @error('profile_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @if($barber->profile_image)
                        <div class="mt-3">
                            <small class="text-muted">Current photo:</small><br>
                            <img src="{{ asset('storage/' . $barber->profile_image) }}" alt="{{ $barber->name }}"
                                 class="rounded-circle mt-2" style="width:120px; height:120px; object-fit:cover; border:2px solid #e9ecef;">
                        </div>
                    @endif
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label class="form-label fw-medium">Current Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="active"   {{ old('status', $barber->status) === 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $barber->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Unavailable Reason -->
                <div class="col-md-6">
                    <label class="form-label fw-medium">Unavailable Reason (optional)</label>
                    <input type="text" name="unavailable_reason" class="form-control @error('unavailable_reason') is-invalid @enderror"
                           value="{{ old('unavailable_reason', $barber->unavailable_reason) }}"
                           placeholder="e.g. On vacation, training">
                    @error('unavailable_reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Read-only Stats (optional enhancement) -->
                <div class="col-12 mt-3">
                    <hr class="my-4">
                    <div class="row g-3 text-center text-md-start">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Average Rating</small>
                            <h5 class="mb-0">{{ $barber->average_rating ?? '0.0' }} <span class="text-warning">★</span></h5>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Total Reviews</small>
                            <h5 class="mb-0">{{ $barber->total_reviews ?? 0 }}</h5>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="bi bi-save me-2"></i> Update Barber
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
