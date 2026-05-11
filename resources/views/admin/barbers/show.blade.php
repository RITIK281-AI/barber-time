@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-0">

    {{-- header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="admin-title mb-1">Barber Details</h2>
            <p class="admin-text-muted mb-0">Viewing profile of {{ $barber->name }}</p>
        </div>
        <a href="{{ route('admin.barbers.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>

    <div class="row g-4">

        {{-- left: photo card --}}
        <div class="col-md-4">
            <div class="admin-card p-4 text-center">

                @if($barber->profile_image)
                    <img src="{{ Storage::url($barber->profile_image) }}"
                         alt="{{ $barber->name }}"
                         class="rounded-circle mx-auto d-block mb-3"
                         style="width:110px; height:110px; object-fit:cover; border:3px solid var(--color-accent);">
                @else
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mx-auto mb-3"
                         style="width:110px; height:110px; background:#2a2a2a; border:3px solid var(--color-accent); font-size:2.5rem; color:var(--color-accent);">
                        {{ strtoupper(substr($barber->name, 0, 1)) }}
                    </div>
                @endif

                <h5 class="mb-2">{{ $barber->name }}</h5>

                {{-- status badge --}}
                <span class="badge rounded-pill px-3 py-2
                    @if($barber->status === 'available') bg-success
                    @elseif($barber->status === 'busy') bg-warning text-dark
                    @else bg-secondary @endif">
                    {{ ucfirst($barber->status) }}
                </span>

                @if($barber->shop)
                    <p class="admin-text-muted small mt-2 mb-0">
                        <i class="bi bi-shop me-1"></i>{{ $barber->shop->name }}
                    </p>
                @endif

                {{-- star rating using bi icons so they're always visible --}}
                <div class="mt-3">
                    @if($barber->average_rating > 0)
                        <div class="fs-5" style="color: #f59e0b;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $barber->average_rating >= $i ? '-fill' : ($barber->average_rating >= $i - 0.5 ? '-half' : '') }}"></i>
                            @endfor
                        </div>
                        <small class="admin-text-muted">
                            {{ number_format($barber->average_rating, 1) }} ({{ $barber->total_reviews }} reviews)
                        </small>
                    @else
                        <small class="admin-text-muted">No ratings yet</small>
                    @endif
                </div>
            </div>
        </div>

        {{-- right: details + reviews --}}
        <div class="col-md-8">

            {{-- info card --}}
            <div class="admin-card p-4 mb-4">
                <h5 class="mb-4">Barber Information</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="admin-text-muted small mb-1">Full Name</p>
                        <p class="mb-0 fw-medium">{{ $barber->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="admin-text-muted small mb-1">Email</p>
                        <p class="mb-0">{{ $barber->email ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="admin-text-muted small mb-1">Phone</p>
                        <p class="mb-0">{{ $barber->phone ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="admin-text-muted small mb-1">Experience</p>
                        <p class="mb-0">{{ $barber->experience_years ? $barber->experience_years . ' years' : '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="admin-text-muted small mb-1">Assigned Shop</p>
                        <p class="mb-0">{{ $barber->shop->name ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="admin-text-muted small mb-1">Joined</p>
                        <p class="mb-0">{{ $barber->created_at->format('d M Y') }}</p>
                    </div>
                    @if($barber->bio)
                        <div class="col-12">
                            <p class="admin-text-muted small mb-1">Bio</p>
                            <p class="mb-0">{{ $barber->bio }}</p>
                        </div>
                    @endif
                    @if($barber->unavailable_reason)
                        <div class="col-12">
                            <p class="admin-text-muted small mb-1">Unavailable Reason</p>
                            <p class="mb-0">{{ $barber->unavailable_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- recent reviews --}}
            <div class="admin-card p-4">
                <h5 class="mb-4">Recent Reviews</h5>

                @forelse($barber->reviews->take(5) as $review)
                    <div class="mb-3 pb-3" style="border-bottom:1px solid rgba(255,255,255,0.07);">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-medium small">{{ $review->user->name ?? 'Customer' }}</span>
                            {{-- bi icons instead of text chars so stars show on dark bg --}}
                            <span style="color:#f59e0b;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $review->barber_rating >= $i ? '-fill' : '' }}"></i>
                                @endfor
                                <small class="ms-1 admin-text-muted">{{ $review->barber_rating }}/5</small>
                            </span>
                        </div>
                        <p class="admin-text-muted small mb-0">{{ $review->comment ?? 'No comment.' }}</p>
                        <p class="admin-text-muted small mb-0 mt-1">{{ $review->created_at->format('d M Y') }}</p>
                    </div>
                @empty
                    <p class="admin-text-muted small">No reviews yet.</p>
                @endforelse
            </div>

        </div>
    </div>
</div>
@endsection
