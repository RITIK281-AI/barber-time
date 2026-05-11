@extends('barbershop.layouts.shop')

@section('content')
<div class="container-fluid px-0">

    {{-- back button --}}
    <div class="mb-4">
        <a href="{{ route('shop.barbers.index') }}" class="btn-shop-secondary px-3">
            <i class="bi bi-arrow-left me-1"></i> Back to Barbers
        </a>
    </div>

    <div class="row g-4">

        {{-- left column: photo + quick stats --}}
        <div class="col-lg-3 col-md-4">

            {{-- photo card --}}
            <div class="stat-card text-center mb-4">

                @if($barber->profile_image)
                    <img src="{{ asset('storage/' . $barber->profile_image) }}"
                         class="rounded-circle mx-auto d-block mb-3"
                         style="width:110px; height:110px; object-fit:cover; border:3px solid var(--border);"
                         alt="{{ $barber->name }}">
                @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                         style="width:110px; height:110px; background:var(--main-bg); border:2px dashed var(--border);">
                        <i class="bi bi-person-fill fs-1" style="color:var(--text-muted);"></i>
                    </div>
                @endif

                <h5 class="fw-bold mb-1" style="color:var(--text-primary);">{{ $barber->name }}</h5>
                <p class="small mb-3" style="color:var(--text-muted);">{{ $barber->bio ?? 'No bio provided.' }}</p>

                {{-- status --}}
                <span class="badge rounded-pill px-3 py-2 mb-3
                    @if($barber->status === 'available') bg-success
                    @elseif($barber->status === 'busy') bg-warning text-dark
                    @else bg-secondary @endif">
                    {{ ucfirst($barber->status) }}
                </span>

                {{-- star rating --}}
                @if($barber->average_rating > 0)
                    <div class="fs-5 mb-1" style="color:#f59e0b;">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $barber->average_rating >= $i ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                    <small style="color:var(--text-muted);">
                        {{ number_format($barber->average_rating, 1) }} ({{ $barber->total_reviews }} reviews)
                    </small>
                @else
                    <small style="color:var(--text-muted);">No ratings yet</small>
                @endif
            </div>

            {{-- quick info rows --}}
            <div class="stat-card">
                <div class="profile-info-row">
                    <span class="profile-info-label">Phone</span>
                    <span class="profile-info-value">{{ $barber->phone ?? '—' }}</span>
                </div>
                <div class="profile-info-row">
                    <span class="profile-info-label">Email</span>
                    <span class="profile-info-value small">{{ $barber->email ?? '—' }}</span>
                </div>
                <div class="profile-info-row">
                    <span class="profile-info-label">Experience</span>
                    <span class="profile-info-value">{{ $barber->experience_years ? $barber->experience_years . ' yrs' : '—' }}</span>
                </div>
                <div class="profile-info-row">
                    <span class="profile-info-label">Joined</span>
                    <span class="profile-info-value">{{ $barber->created_at->format('d M Y') }}</span>
                </div>
                @if($barber->unavailable_reason)
                    <div class="profile-info-row">
                        <span class="profile-info-label">Reason</span>
                        <span class="profile-info-value small">{{ $barber->unavailable_reason }}</span>
                    </div>
                @endif
            </div>

        </div>

        {{-- right column: full details + reviews --}}
        <div class="col-lg-9 col-md-8">

            {{-- full details card --}}
            <div class="stat-card mb-4">
                <h5 class="fw-bold mb-4" style="color:var(--text-primary); border-bottom:1px solid var(--border); padding-bottom:0.75rem;">
                    <i class="bi bi-person me-2 text-primary"></i>Barber Information
                </h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="shop-label mb-1">Full Name</p>
                        <p class="mb-0 fw-medium" style="color:var(--text-primary);">{{ $barber->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="shop-label mb-1">Email Address</p>
                        <p class="mb-0" style="color:var(--text-primary);">{{ $barber->email ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="shop-label mb-1">Phone Number</p>
                        <p class="mb-0" style="color:var(--text-primary);">{{ $barber->phone ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="shop-label mb-1">Experience</p>
                        <p class="mb-0" style="color:var(--text-primary);">
                            {{ $barber->experience_years ? $barber->experience_years . ' years' : '—' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="shop-label mb-1">Status</p>
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
                        <p class="shop-label mb-1">Joined</p>
                        <p class="mb-0" style="color:var(--text-primary);">{{ $barber->created_at->format('d M Y') }}</p>
                    </div>
                    @if($barber->bio)
                        <div class="col-12">
                            <p class="shop-label mb-1">Bio</p>
                            <p class="mb-0" style="color:var(--text-primary);">{{ $barber->bio }}</p>
                        </div>
                    @endif
                    @if($barber->unavailable_reason)
                        <div class="col-12">
                            <p class="shop-label mb-1">Unavailable Reason</p>
                            <p class="mb-0" style="color:var(--text-primary);">{{ $barber->unavailable_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- reviews card --}}
            <div class="stat-card">
                <h5 class="fw-bold mb-4" style="color:var(--text-primary); border-bottom:1px solid var(--border); padding-bottom:0.75rem;">
                    <i class="bi bi-star me-2" style="color:#f59e0b;"></i>Customer Reviews
                </h5>

                @if($barber->reviews->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" style="color:var(--text-primary);">
                            <thead>
                                <tr style="border-bottom:2px solid var(--border); color:var(--text-muted); font-size:0.82rem; text-transform:uppercase; letter-spacing:0.04em;">
                                    <th class="pb-3 fw-semibold">Customer</th>
                                    <th class="pb-3 fw-semibold">Barber Rating</th>
                                    <th class="pb-3 fw-semibold">Shop Rating</th>
                                    <th class="pb-3 fw-semibold">Comment</th>
                                    <th class="pb-3 fw-semibold">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barber->reviews as $review)
                                    <tr style="border-bottom:1px solid var(--border);">
                                        <td class="py-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="upcoming-avatar" style="width:32px; height:32px; font-size:0.8rem;">
                                                    {{ strtoupper(substr($review->user?->name ?? 'C', 0, 1)) }}
                                                </div>
                                                <span class="fw-semibold">{{ $review->user?->name ?? 'Customer' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div style="color:#f59e0b;">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $review->barber_rating >= $i ? '-fill' : '' }}"></i>
                                                @endfor
                                            </div>
                                            <small style="color:var(--text-muted);">{{ $review->barber_rating }}/5</small>
                                        </td>
                                        <td class="py-3">
                                            <div style="color:#f59e0b;">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $review->shop_rating >= $i ? '-fill' : '' }}"></i>
                                                @endfor
                                            </div>
                                            <small style="color:var(--text-muted);">{{ $review->shop_rating }}/5</small>
                                        </td>
                                        <td class="py-3 small" style="color:var(--text-muted);">
                                            {{ $review->comment ?? '—' }}
                                        </td>
                                        <td class="py-3 small" style="color:var(--text-muted);">
                                            {{ $review->created_at->format('d M Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5" style="color:var(--text-muted);">
                        <i class="bi bi-chat-square-text fs-1 opacity-50 d-block mb-3"></i>
                        No reviews yet for this barber.
                    </div>
                @endif
            </div>

        </div>
    </div>

</div>
@endsection
