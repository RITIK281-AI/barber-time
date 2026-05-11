@extends('frontend.layouts.app')

@section('title', 'Edit Review')

@section('content')

<section class="py-5 mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">

                <h1 class="display-6 fw-bold mb-1">Edit Your Review</h1>
                <p class="text-muted mb-4">
                    {{ $booking->barberShop?->name ?? 'Shop' }} &mdash;
                    {{ optional($booking->booking_date)->format('d M Y') }}
                </p>

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $err)
                            <div>{{ $err }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('frontend.reviews.update', $review->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- barber star rating prefilled --}}
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-1">
                                <i class="bi bi-scissors me-1"></i>
                                Barber Rating — {{ $booking->barber?->name ?? 'Barber' }}
                            </h6>
                            <p class="text-muted small mb-3">How was the barber's skill and service?</p>
                            @include('frontend.reviews.partials.star-input', [
                                'fieldName'    => 'barber_rating',
                                'currentValue' => $review->barber_rating,
                            ])
                        </div>
                    </div>

                    {{-- shop star rating prefilled --}}
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-1">
                                <i class="bi bi-shop me-1"></i>
                                Shop Rating — {{ $booking->barberShop?->name ?? 'Shop' }}
                            </h6>
                            <p class="text-muted small mb-3">How was the overall shop experience?</p>
                            @include('frontend.reviews.partials.star-input', [
                                'fieldName'    => 'shop_rating',
                                'currentValue' => $review->shop_rating,
                            ])
                        </div>
                    </div>

                    {{-- optional comment prefilled --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-1">
                                <i class="bi bi-chat-left-text me-1"></i>
                                Comment <span class="text-muted fw-normal">(optional)</span>
                            </h6>
                            <textarea name="comment"
                                      class="form-control mt-2 @error('comment') is-invalid @enderror"
                                      rows="4"
                                      maxlength="1000"
                                      placeholder="Share your experience...">{{ old('comment', $review->comment) }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Update Review
                        </button>
                        <a href="{{ route('frontend.bookings.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

@endsection
