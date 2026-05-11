{{-- resources/views/barbershop/services/create.blade.php --}}

@extends('barbershop.layouts.shop')

@section('content')

<div class="container-fluid px-0">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold text-dark mb-1">Add New Service</h2>
            <p class="text-muted mb-0">Fill in the details for your new service</p>
        </div>
        <a href="{{ route('shop.services.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i> Back
        </a>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('shop.services.store') }}" method="POST">
                @csrf

                {{-- Category --}}
                <div class="mb-3">
                    <label for="category_id" class="form-label fw-medium">Category</label>
                    <select class="form-select @error('category_id') is-invalid @enderror"
                            id="category_id" name="category_id" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Service Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label fw-medium">Service Name</label>
                    <input type="text"
                           class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name"
                           value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label fw-medium">
                        Description <span class="text-muted fw-normal">(Optional)</span>
                    </label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Price --}}
                <div class="mb-3">
                    <label for="price" class="form-label fw-medium">Price (Rs.)</label>
                    <input type="number" step="0.01" min="0"
                           class="form-control @error('price') is-invalid @enderror"
                           id="price" name="price"
                           value="{{ old('price') }}" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Duration --}}
                <div class="mb-3">
                    <label for="duration" class="form-label fw-medium">Duration (minutes)</label>
                    <input type="number" min="1"
                           class="form-control @error('duration') is-invalid @enderror"
                           id="duration" name="duration"
                           value="{{ old('duration') }}" required>
                    @error('duration')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-4">
                    <label for="status" class="form-label fw-medium">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror"
                            id="status" name="status" required>
                        <option value="active"   {{ old('status') === 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">Save Service</button>
                    <a href="{{ route('shop.services.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                </div>

            </form>
        </div>
    </div>

</div>

@endsection
