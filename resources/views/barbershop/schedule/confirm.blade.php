@extends('barbershop.layouts.shop')

@section('content')
<div class="container-fluid py-4">

    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Confirm Holiday Date
                    </h5>
                </div>

                <div class="card-body p-4">

                    {{-- date and reason summary --}}
                    <div class="p-3 bg-light rounded border mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Date</span>
                            <span class="fw-bold">
                                {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Reason</span>
                            <span class="fw-medium">{{ $reason ?: '—' }}</span>
                        </div>
                    </div>

                    {{-- affected bookings warning --}}
                    @if($affectedCount > 0)
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <strong>{{ $affectedCount }} booking(s)</strong> will be cancelled and each customer will receive a cancellation email.
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            No active bookings on this date. Safe to mark as holiday.
                        </div>
                    @endif

                    {{-- confirm form --}}
                    <form action="{{ route('shop.schedule.holiday.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="date" value="{{ $date }}">
                        <input type="hidden" name="reason" value="{{ $reason }}">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="bi bi-calendar-x me-2"></i>
                                Yes, Mark as Holiday
                                @if($affectedCount > 0)
                                    & Cancel {{ $affectedCount }} Booking(s)
                                @endif
                            </button>
                            <a href="{{ route('shop.schedule.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Go Back
                            </a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
