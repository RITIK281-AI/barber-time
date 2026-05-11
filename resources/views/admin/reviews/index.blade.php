@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="admin-title mb-1">Reviews</h2>
            <p class="admin-text-muted mb-0">Monitor and moderate customer reviews.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- search and filter --}}
    <div class="admin-card admin-card-body mb-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="admin-label">Search</label>
                <input type="text" name="search" class="admin-input"
                       placeholder="Customer, shop, barber or comment..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="admin-label">Barber Shop</label>
                <select name="shop_id" class="admin-input" onchange="this.form.submit()">
                    <option value="">All Shops</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}" {{ (string) request('shop_id') === (string) $shop->id ? 'selected' : '' }}>
                            {{ $shop->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <label class="admin-label">Rating</label>
                <select name="rating" class="admin-input" onchange="this.form.submit()">
                    <option value="">All</option>
                    @for($r = 5; $r >= 1; $r--)
                        <option value="{{ $r }}" {{ (string) request('rating') === (string) $r ? 'selected' : '' }}>
                            {{ $r }}★
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn-admin-primary">
                    <i class="bi bi-search me-1"></i> Search
                </button>
                @if(request('search') || request('shop_id') || request('rating'))
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- reviews table --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h5><i class="bi bi-star me-2 text-primary"></i>All Reviews</h5>
            <span class="cat-count-badge">{{ $reviews->total() }} reviews</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="admin-table-head">
                    <tr>
                        <th class="ps-4 py-3">S.N.</th>
                        <th class="py-3">Reviewer</th>
                        <th class="py-3">Shop</th>
                        <th class="py-3">Barber</th>
                        <th class="py-3">Ratings</th>
                        <th class="py-3">Comment</th>
                        <th class="py-3">Booking Ref</th>
                        <th class="py-3">Date</th>
                        <th class="py-3 text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $index => $review)
                        <tr>
                            <td class="ps-4 text-muted fw-medium">
                                {{ $reviews->firstItem() + $index }}
                            </td>

                            {{-- reviewer --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="icon-box icon-box-blue"
                                         style="width:34px; height:34px; border-radius:50%; font-size:0.85rem; font-weight:700;">
                                        {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <p class="mb-0 small fw-medium" style="color:var(--text-primary);">
                                        {{ $review->user->name ?? '—' }}
                                    </p>
                                </div>
                            </td>

                            {{-- shop --}}
                            <td class="small admin-text-muted">
                                {{ $review->barberShop->name ?? '—' }}
                            </td>

                            {{-- barber --}}
                            <td class="small admin-text-muted">
                                {{ $review->barber->name ?? '—' }}
                            </td>

                            {{-- ratings --}}
                            <td>
                                <div class="d-flex flex-column gap-1">
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
                                    @if(!$review->shop_rating && !$review->barber_rating)
                                        <span class="admin-text-muted small">—</span>
                                    @endif
                                </div>
                            </td>

                            {{-- comment --}}
                            <td class="small" style="max-width:200px; color:var(--text-secondary);">
                                @if($review->comment)
                                    <span title="{{ $review->comment }}">
                                        {{ Str::limit($review->comment, 60) }}
                                    </span>
                                @else
                                    <span class="admin-text-muted">No comment</span>
                                @endif
                            </td>

                            {{-- booking reference --}}
                            <td class="small admin-text-muted">
                                #{{ $review->booking_id ?? '—' }}
                            </td>

                            {{-- date --}}
                            <td class="small admin-text-muted">
                                {{ $review->created_at->format('d M Y') }}
                            </td>

                            {{-- delete action --}}
                            <td class="text-end pe-4">
                                <form action="{{ route('admin.reviews.destroy', $review) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this review? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="icon-box icon-box-blue mx-auto mb-3"
                                     style="width:56px; height:56px; font-size:1.75rem;">
                                    <i class="bi bi-star"></i>
                                </div>
                                <p class="fw-semibold mb-1" style="color:var(--text-primary);">No reviews found</p>
                                <p class="admin-text-muted small mb-0">Try adjusting your search or filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- pagination --}}
        @if($reviews->hasPages())
            <div class="admin-card-body pt-0">
                {{ $reviews->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection
