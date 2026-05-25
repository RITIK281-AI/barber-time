@extends('frontend.layouts.app')

@section('title', $barber->name . ' — BarberTime')

@section('content')

<section class="py-5 mt-5">
    <div class="container">

        {{-- back button --}}
        <div class="mb-4">
            <a href="{{ url()->previous() }}" class="text-muted text-decoration-none small">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="row g-4">

            {{-- left column: photo + quick info --}}
            <div class="col-lg-3 col-md-4">

                {{-- photo card --}}
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center mb-4">

                    @if($barber->profile_image)
                        <img src="{{ asset('storage/' . $barber->profile_image) }}"
                             class="rounded-circle mx-auto d-block mb-3"
                             style="width:110px; height:110px; object-fit:cover;"
                             alt="{{ $barber->name }}">
                    @else
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3"
                             style="width:110px; height:110px;">
                            <i class="bi bi-person-fill display-5 text-muted"></i>
                        </div>
                    @endif

                    <h5 class="fw-bold mb-1">{{ $barber->name }}</h5>
                    <p class="text-muted small mb-3">{{ $barber->bio ?? 'Professional barber.' }}</p>

                    {{-- status badge --}}
                    <span class="badge rounded-pill px-3 py-2 mb-3
                        @if($barber->status === 'available') bg-success
                        @elseif($barber->status === 'busy') bg-warning text-dark
                        @else bg-secondary @endif">
                        {{ ucfirst($barber->status) }}
                    </span>

                    {{-- star rating --}}
                    @if($barber->average_rating > 0)
                        <div class="fs-5 mb-1 text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $barber->average_rating >= $i ? '-fill' : '' }}"></i>
                            @endfor
                        </div>
                        <small class="text-muted">
                            {{ number_format($barber->average_rating, 1) }} ({{ $barber->total_reviews }} reviews)
                        </small>
                    @else
                        <small class="text-muted"><i class="bi bi-star me-1"></i>No ratings yet</small>
                    @endif
                </div>

                {{-- quick info list --}}
                <div class="card border-0 shadow-sm rounded-4 p-3">
                    <div class="d-flex justify-content-between py-2 border-bottom small">
                        <span class="text-muted">Phone</span>
                        <span class="fw-medium">{{ $barber->phone ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom small">
                        <span class="text-muted">Email</span>
                        <span class="fw-medium">{{ $barber->email ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom small">
                        <span class="text-muted">Experience</span>
                        <span class="fw-medium">{{ $barber->experience_years ? $barber->experience_years . ' yrs' : '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 small">
                        <span class="text-muted">Joined</span>
                        <span class="fw-medium">{{ $barber->created_at->format('d M Y') }}</span>
                    </div>
                </div>

            </div>

            {{-- right column: full details + reviews --}}
            <div class="col-lg-9 col-md-8">

                {{-- full details card --}}
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold mb-4 pb-2 border-bottom">
                        <i class="bi bi-person me-2 text-primary"></i>Barber Information
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Full Name</p>
                            <p class="mb-0 fw-medium">{{ $barber->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Email Address</p>
                            <p class="mb-0">{{ $barber->email ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Phone Number</p>
                            <p class="mb-0">{{ $barber->phone ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Experience</p>
                            <p class="mb-0">{{ $barber->experience_years ? $barber->experience_years . ' years' : '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Status</p>
                            <p class="mb-0">
                                <span class="badge
                                    @if($barber->status === 'available') bg-success
                                    @elseif($barber->status === 'busy') bg-warning text-dark
                                    @else bg-secondary @endif">
                                    {{ ucfirst($barber->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Joined</p>
                            <p class="mb-0">{{ $barber->created_at->format('d M Y') }}</p>
                        </div>
                        @if($barber->bio)
                            <div class="col-12">
                                <p class="text-muted small mb-1">Bio</p>
                                <p class="mb-0">{{ $barber->bio }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- reviews card --}}
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-4 pb-2 border-bottom">
                        <i class="bi bi-star me-2 text-warning"></i>Customer Reviews
                    </h5>

                    @if($barber->reviews->isNotEmpty())
                        <div class="d-flex flex-column gap-3">
                            @foreach($barber->reviews as $review)
                                <div class="p-3 rounded-3" style="background:#f8fafc; border:1px solid #e2e8f0;">

                                    {{-- reviewer + date --}}
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center fw-bold text-primary"
                                                 style="width:32px; height:32px; font-size:0.8rem;">
                                                {{ strtoupper(substr($review->user?->name ?? 'C', 0, 1)) }}
                                            </div>
                                            <span class="fw-semibold small">{{ $review->user?->name ?? 'Customer' }}</span>
                                        </div>
                                        <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                                    </div>

                                    {{-- ratings row --}}
                                    <div class="d-flex gap-4 mb-2 small">
                                        <div>
                                            <span class="text-muted me-1">Barber:</span>
                                            <span class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $review->barber_rating >= $i ? '-fill' : '' }}"></i>
                                                @endfor
                                            </span>
                                            <span class="text-muted ms-1">{{ $review->barber_rating }}/5</span>
                                        </div>
                                        <div>
                                            <span class="text-muted me-1">Shop:</span>
                                            <span class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $review->shop_rating >= $i ? '-fill' : '' }}"></i>
                                                @endfor
                                            </span>
                                            <span class="text-muted ms-1">{{ $review->shop_rating }}/5</span>
                                        </div>
                                    </div>

                                    {{-- comment --}}
                                    @if($review->comment)
                                        <p class="text-muted small mb-0">
                                            <i class="bi bi-chat-left-quote me-1"></i>{{ $review->comment }}
                                        </p>
                                    @endif

                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-chat-square-text fs-1 opacity-50 d-block mb-3"></i>
                            No reviews yet for this barber.
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
