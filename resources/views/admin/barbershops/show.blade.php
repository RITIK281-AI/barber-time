@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="admin-title mb-1">Barber Shop Details</h2>
            <p class="admin-text-muted mb-0">Viewing details of {{ $barberShop->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.barbershops.edit', $barberShop) }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ request('from') === 'barbers' ? route('admin.barbers.index') : route('admin.barbershops.index') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- left column --}}
        <div class="col-lg-4">

            {{-- shop image + basic info --}}
            <div class="admin-card mb-4">
                <div class="admin-card-body text-center">

                    @if($barberShop->shop_image)
                        <img src="{{ asset('storage/' . $barberShop->shop_image) }}"
                             alt="{{ $barberShop->name }}"
                             class="rounded mb-3"
                             style="width:100%; height:180px; object-fit:cover; border-radius:var(--radius) !important;">
                    @else
                        <div class="d-flex align-items-center justify-content-center mb-3"
                             style="width:100%; height:180px; background:var(--color-info-bg); border-radius:var(--radius);">
                            <i class="bi bi-shop" style="font-size:3rem; color:var(--primary);"></i>
                        </div>
                    @endif

                    <h5 class="fw-bold mb-1" style="color:var(--text-primary);">{{ $barberShop->name }}</h5>

                    {{-- status badge --}}
                    @if($barberShop->status === 'approved')
                        <span class="badge-active">Approved</span>
                    @elseif($barberShop->status === 'pending')
                        <span class="badge-pending">Pending</span>
                    @elseif($barberShop->status === 'suspended')
                        <span class="badge-suspended">Suspended</span>
                    @else
                        <span class="badge-suspended">Rejected</span>
                    @endif

                    {{-- rating --}}
                    @if($barberShop->average_rating > 0)
                        <p class="mt-2 mb-0">
                            <i class="bi bi-star-fill text-warning me-1"></i>
                            <span class="fw-medium">{{ number_format($barberShop->average_rating, 1) }}</span>
                            <span class="admin-text-muted small">({{ $barberShop->total_reviews }} reviews)</span>
                        </p>
                    @endif

                    {{-- suspend or restore button --}}
                    <div class="mt-3">
                        @if($barberShop->status === 'suspended')
                            <form action="{{ route('admin.barbershops.restore', $barberShop) }}"
                                  method="POST"
                                  onsubmit="return confirm('Restore this barber shop?');">
                                @csrf
                                <button type="submit" class="btn btn-outline-success w-100">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Restore Shop
                                </button>
                            </form>
                        @elseif($barberShop->status === 'approved')
                            <form action="{{ route('admin.barbershops.suspend', $barberShop) }}"
                                  method="POST"
                                  onsubmit="return confirm('Suspend this barber shop? All bookings will be affected.');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-slash-circle me-1"></i> Suspend Shop
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- shop info --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-info-circle me-2 text-primary"></i>Shop Info</h5>
                </div>
                <div class="admin-card-body">
                    <div class="profile-info-row">
                        <span class="profile-info-label">Phone</span>
                        <span class="profile-info-value">{{ $barberShop->phone ?? '—' }}</span>
                    </div>
                    <div class="profile-info-row">
                        <span class="profile-info-label">Email</span>
                        <span class="profile-info-value">{{ $barberShop->email ?? '—' }}</span>
                    </div>
                    <div class="profile-info-row">
                        <span class="profile-info-label">Address</span>
                        <span class="profile-info-value">{{ $barberShop->address }}</span>
                    </div>
                    <div class="profile-info-row">
                        <span class="profile-info-label">District</span>
                        <span class="profile-info-value">{{ $barberShop->district ?? '—' }}</span>
                    </div>
                    <div class="profile-info-row">
                        <span class="profile-info-label">Opening Hours</span>
                        <span class="profile-info-value">
                            {{ $barberShop->opening_time ? \Carbon\Carbon::parse($barberShop->opening_time)->format('h:i A') : '—' }}
                            —
                            {{ $barberShop->closing_time ? \Carbon\Carbon::parse($barberShop->closing_time)->format('h:i A') : '—' }}
                        </span>
                    </div>
                    <div class="profile-info-row">
                        <span class="profile-info-label">Owner Name</span>
                        <span class="profile-info-value">{{ $barberShop->owner_name ?? '—' }}</span>
                    </div>
                    <div class="profile-info-row">
                        <span class="profile-info-label">PAN Number</span>
                        <span class="profile-info-value">{{ $barberShop->pan_number ?? '—' }}</span>
                    </div>
                    <div class="profile-info-row">
                        <span class="profile-info-label">Registered</span>
                        <span class="profile-info-value">{{ $barberShop->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- right column --}}
        <div class="col-lg-8">

            {{-- barbers --}}
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h5><i class="bi bi-scissors me-2 text-primary"></i>Barbers</h5>
                    <span class="cat-count-badge">{{ $barberShop->barbers->count() }} barbers</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="admin-table-head">
                            <tr>
                                <th class="ps-4 py-3">Name</th>
                                <th class="py-3">Phone</th>
                                <th class="py-3">Experience</th>
                                <th class="py-3">Rating</th>
                                <th class="py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barberShop->barbers as $barber)
                                <tr>
                                    <td class="ps-4 fw-medium small" style="color:var(--text-primary);">
                                        {{ $barber->name }}
                                    </td>
                                    <td class="small admin-text-muted">{{ $barber->phone ?? '—' }}</td>
                                    <td class="small admin-text-muted">
                                        {{ $barber->experience_years ? $barber->experience_years . ' yrs' : '—' }}
                                    </td>
                                    <td>
                                        @if($barber->average_rating > 0)
                                            <span class="cat-count-badge">
                                                <i class="bi bi-star-fill text-warning me-1" style="font-size:0.7rem;"></i>
                                                {{ number_format($barber->average_rating, 1) }}
                                            </span>
                                        @else
                                            <span class="admin-text-muted small">No ratings</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($barber->status === 'active')
                                            <span class="badge-active">Available</span>
                                        @elseif($barber->status === 'busy')
                                            <span class="badge-pending">Busy</span>
                                        @else
                                            <span class="badge-suspended">Unavailable</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <p class="admin-text-muted small mb-0">No barbers added yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- services --}}
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h5><i class="bi bi-list-ul me-2 text-primary"></i>Services</h5>
                    <span class="cat-count-badge">{{ $barberShop->services->count() }} services</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="admin-table-head">
                            <tr>
                                <th class="ps-4 py-3">Service</th>
                                <th class="py-3">Price</th>
                                <th class="py-3">Duration</th>
                                <th class="py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barberShop->services as $service)
                                <tr>
                                    <td class="ps-4 fw-medium small" style="color:var(--text-primary);">
                                        {{ $service->name }}
                                    </td>
                                    <td class="small admin-text-muted">
                                        Rs {{ number_format($service->price, 0) }}
                                    </td>
                                    <td class="small admin-text-muted">
                                        {{ $service->duration }} mins
                                    </td>
                                    <td>
                                        @if($service->status === 'active' || $service->status === 'available')
                                            <span class="badge-active">Available</span>
                                        @else
                                            <span class="badge-suspended">Unavailable</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <p class="admin-text-muted small mb-0">No services added yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- recent reviews --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-star me-2 text-primary"></i>Recent Reviews</h5>
                    <span class="cat-count-badge">{{ $barberShop->reviews->count() }} reviews</span>
                </div>
                <div class="admin-card-body">
                    @forelse($barberShop->reviews->take(5) as $review)
                        <div class="mb-3 pb-3" style="border-bottom:1px solid var(--border);">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <p class="mb-0 fw-medium small" style="color:var(--text-primary);">
                                    {{ $review->user->name ?? 'Customer' }}
                                </p>
                                <div class="d-flex gap-2">
                                    @if($review->shop_rating)
                                        <span class="cat-count-badge" style="font-size:0.75rem;">
                                            Shop: {{ $review->shop_rating }}/5
                                        </span>
                                    @endif
                                    @if($review->barber_rating)
                                        <span class="badge-info" style="font-size:0.75rem;">
                                            Barber: {{ $review->barber_rating }}/5
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="small mb-1" style="color:var(--text-secondary);">
                                    "{{ Str::limit($review->comment, 100) }}"
                                </p>
                            @endif
                            <small class="admin-text-muted">
                                {{ $review->created_at->format('d M Y') }}
                            </small>
                        </div>
                    @empty
                        <p class="admin-text-muted small mb-0">No reviews yet.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
