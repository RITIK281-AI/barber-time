{{-- resources/views/frontend/dashboard/favourites.blade.php --}}

<div class="card border-0 shadow-sm rounded-4 p-4">

    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-heart-fill text-danger me-2"></i>My Favourite Shops
        </h5>
        <span class="badge bg-light text-muted border">
            {{ $favourites->count() }} saved
        </span>
    </div>

    @if($favourites->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-heart fs-1 opacity-50 d-block mb-3"></i>
            <p class="mb-1 fw-semibold">No favourites yet</p>
            <p class="small mb-4">Browse shops and tap the heart to save them here.</p>
            <a href="{{ route('frontend.shops.index') }}" class="btn btn-trim text-white px-4 rounded-3">
                <i class="bi bi-scissors me-2"></i>Browse Shops
            </a>
        </div>
    @else
        <div class="row g-3">
            @foreach($favourites as $shop)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">

                        {{-- shop image --}}
                        @if($shop->shop_image)
                            <img src="{{ asset('storage/' . $shop->shop_image) }}"
                                 class="card-img-top"
                                 style="height: 140px; object-fit: cover;"
                                 alt="{{ $shop->name }}">
                        @else
                            <div class="bg-secondary d-flex align-items-center justify-content-center"
                                 style="height: 140px;">
                                <i class="bi bi-scissors fs-1 text-white opacity-50"></i>
                            </div>
                        @endif

                        <div class="card-body p-3">
                            <h6 class="fw-bold mb-1">{{ $shop->name }}</h6>

                            <p class="text-muted small mb-1">
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ $shop->address ?? 'Address not listed' }}
                                @if($shop->district), {{ $shop->district }}@endif
                            </p>

                            @if($shop->average_rating)
                                <div class="text-warning small mb-2">
                                    <i class="bi bi-star-fill me-1"></i>
                                    {{ number_format($shop->average_rating, 1) }}
                                    <span class="text-muted">({{ $shop->total_reviews }})</span>
                                </div>
                            @else
                                <div class="text-muted small mb-2">
                                    <i class="bi bi-star me-1"></i>No reviews yet
                                </div>
                            @endif

                            <div class="d-flex gap-2 mt-2">
                                <a href="{{ route('frontend.shops.show', $shop->id) }}"
                                   class="btn btn-sm btn-trim text-white rounded-3 flex-grow-1">
                                    View Shop
                                </a>
                                <button
                                    onclick="removeFavourite({{ $shop->id }}, this)"
                                    class="btn btn-sm btn-outline-danger rounded-3"
                                    title="Remove from favourites">
                                    <i class="bi bi-heart-fill"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

@push('scripts')
<script>
    function removeFavourite(shopId, btn) {
        // find the parent card and remove it from the UI instantly
        const card = btn.closest('.col-md-6');

        fetch('/favourites/' + shopId + '/toggle', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept':       'application/json',
                'Content-Type': 'application/json',
            },
        })
        .then(res => res.json())
        .then(data => {
            if (!data.favourited) {
                // fade out and remove the card
                card.style.transition = 'opacity 0.3s';
                card.style.opacity = '0';
                setTimeout(() => card.remove(), 300);

                // update the saved count badge
                const badge = document.querySelector('.badge.bg-light');
                if (badge) {
                    const current = parseInt(badge.textContent);
                    badge.textContent = (current - 1) + ' saved';
                }
            }
        })
        .catch(() => alert('Could not remove. Please try again.'));
    }
</script>
@endpush
