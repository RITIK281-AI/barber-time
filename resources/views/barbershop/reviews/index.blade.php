@extends('barbershop.layouts.shop')

@section('content')

<div class="container-fluid px-0">

    {{-- header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Customer Reviews</h2>
            <p class="text-muted mb-0">All reviews left for your shop and barbers</p>
        </div>

        {{-- overall shop rating summary --}}
        <div class="card border-0 shadow-sm rounded-4 px-4 py-3 text-center">
            @if($shop->average_rating > 0)
                <div class="text-warning fs-4 mb-1">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $shop->average_rating >= $i ? '-fill' : '' }}"></i>
                    @endfor
                </div>
                <div class="fw-bold fs-5">{{ number_format($shop->average_rating, 1) }} / 5</div>
                <small class="text-muted">{{ $shop->total_reviews }} total reviews</small>
            @else
                <small class="text-muted">No reviews yet</small>
            @endif
        </div>
    </div>

    {{-- filter by barber --}}
    <form method="GET" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-4 col-lg-3">
                <label class="form-label fw-medium mb-1">Filter by Barber</label>
                <select name="barber_id" class="form-select" onchange="this.form.submit()">
                    <option value="">All Barbers</option>
                    @foreach($barbers as $barber)
                        <option value="{{ $barber->id }}"
                            {{ request('barber_id') == $barber->id ? 'selected' : '' }}>
                            {{ $barber->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <a href="{{ route('shop.reviews.index') }}" class="btn btn-outline-secondary">
                    Reset
                </a>
            </div>
        </div>
    </form>

    {{-- active filter badge --}}
    @if(request('barber_id') && ($filteredBarber = $barbers->firstWhere('id', request('barber_id'))))
        <div class="mb-4">
            <span class="badge bg-light text-dark border px-3 py-2">
                Barber: <strong>{{ $filteredBarber->name }}</strong>
            </span>
        </div>
    @endif

    {{-- reviews table --}}
    <div class="stat-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-0">Review Records</h5>
                <p class="text-muted small mb-0">{{ $reviews->total() }} records found</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:var(--bg-secondary, #f8fafc);">
                    <tr>
                        <th class="py-3 ps-3 small text-muted fw-semibold">S.N.</th>
                        <th class="py-3 small text-muted fw-semibold">Customer</th>
                        <th class="py-3 small text-muted fw-semibold">Barber</th>
                        <th class="py-3 small text-muted fw-semibold">Barber Rating</th>
                        <th class="py-3 small text-muted fw-semibold">Shop Rating</th>
                        <th class="py-3 small text-muted fw-semibold">Comment</th>
                        <th class="py-3 small text-muted fw-semibold">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $index => $review)
                        <tr>
                            <td class="ps-3 small text-muted">{{ $reviews->firstItem() + $index }}</td>
                            <td>
                                <p class="mb-0 fw-medium small" style="color:var(--text-primary);">
                                    {{ $review->user?->name ?? 'Customer' }}
                                </p>
                            </td>
                            <td class="small text-muted">{{ $review->barber?->name ?? '—' }}</td>
                            <td>
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $review->barber_rating >= $i ? '-fill' : '' }}"></i>
                                    @endfor
                                </span>
                                <small class="text-muted ms-1">{{ $review->barber_rating }}/5</small>
                            </td>
                            <td>
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $review->shop_rating >= $i ? '-fill' : '' }}"></i>
                                    @endfor
                                </span>
                                <small class="text-muted ms-1">{{ $review->shop_rating }}/5</small>
                            </td>
                            <td class="small text-muted" style="max-width: 240px;">
                                {{ $review->comment ?? '—' }}
                            </td>
                            <td class="small text-muted">
                                {{ $review->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox fs-2 text-muted opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-0">No reviews found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
            <div class="pt-3 px-3">
                {{ $reviews->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

@endsection
