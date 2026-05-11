@extends('barbershop.layouts.shop')

@section('content')
<div class="container-fluid px-0">

    <div class="mb-5">
        <h1 class="page-title fw-bold mb-1">Bookings</h1>
        <p class="page-subtitle text-muted">View and manage your shop bookings.</p>
    </div>

    <div class="stat-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-funnel me-2"></i>Filters
            </h5>
            @if(request()->hasAny(['date_from','date_to','service_id','barber_id','payment_status','status']))
                <a href="{{ route('shop.bookings.index') }}" class="small text-muted">Clear filters</a>
            @endif
        </div>

        <form method="GET" action="{{ route('shop.bookings.index') }}">
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label small text-muted">Date From</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small text-muted">Date To</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small text-muted">Service</label>
                    <select name="service_id" class="form-select form-select-sm">
                        <option value="">All Services</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ (string) request('service_id') === (string) $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small text-muted">Barber</label>
                    <select name="barber_id" class="form-select form-select-sm">
                        <option value="">All Barbers</option>
                        @foreach($barbers as $barber)
                            <option value="{{ $barber->id }}" {{ (string) request('barber_id') === (string) $barber->id ? 'selected' : '' }}>
                                {{ $barber->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small text-muted">Payment</label>
                    <select name="payment_status" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="partially_paid" {{ request('payment_status') === 'partially_paid' ? 'selected' : '' }}>Advance Paid</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small text-muted">Booking Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary px-4">
                        <i class="bi bi-search me-1"></i> Apply
                    </button>
                    <a href="{{ route('shop.bookings.index') }}" class="btn btn-sm btn-outline-secondary px-4">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="stat-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-0">Booking Records</h5>
                <p class="text-muted small mb-0">{{ $bookings->total() }} records found</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:var(--bg-secondary, #f8fafc);">
                    <tr>
                        <th class="py-3 ps-3 small text-muted fw-semibold">S.N.</th>
                        <th class="py-3 small text-muted fw-semibold">Customer</th>
                        <th class="py-3 small text-muted fw-semibold">Service</th>
                        <th class="py-3 small text-muted fw-semibold">Barber</th>
                        <th class="py-3 small text-muted fw-semibold">Date</th>
                        <th class="py-3 small text-muted fw-semibold">Time</th>
                        <th class="py-3 small text-muted fw-semibold">Price</th>
                        <th class="py-3 small text-muted fw-semibold">Payment</th>
                        <th class="py-3 small text-muted fw-semibold">Status</th>
                        <th class="py-3 small text-muted fw-semibold">Cancellation Fine</th>
                        <th class="py-3 text-center small text-muted fw-semibold">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $index => $booking)
                        <tr>
                            <td class="ps-3 small text-muted">{{ $bookings->firstItem() + $index }}</td>
                            <td>
                                <p class="mb-0 fw-medium small" style="color:var(--text-primary);">{{ $booking->user?->name ?? '—' }}</p>
                            </td>
                            <td class="small text-muted">{{ $booking->service?->name ?? '—' }}</td>
                            <td class="small text-muted">{{ $booking->barber?->name ?? '—' }}</td>
                            <td class="small text-muted">{{ optional($booking->booking_date)->format('Y-m-d') ?? '—' }}</td>
                            <td class="small text-muted">
                                {{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('h:i A') : '—' }}
                                -
                                {{ $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('h:i A') : '—' }}
                            </td>
                            <td class="small fw-medium" style="color:var(--text-primary);">
                                Rs {{ number_format($booking->final_amount ?? $booking->service?->price ?? 0, 2) }}
                            </td>

                            <td>
                                @if($booking->payment_status === 'paid')
                                    <span class="dash-badge badge-confirmed">Paid</span>
                                @elseif($booking->payment_status === 'partially_paid')
                                    <span class="dash-badge badge-other">Advance Paid</span>
                                @else
                                    <span class="dash-badge badge-pending">Unpaid</span>
                                @endif
                            </td>

                            <td>
                                @if($booking->status === 'completed')
                                    <span class="dash-badge badge-confirmed">Completed</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="dash-badge badge-other">Confirmed</span>
                                @elseif($booking->status === 'pending')
                                    <span class="dash-badge badge-pending">Pending</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="dash-badge" style="background:#fee2e2;color:#dc2626;">Cancelled</span>
                                @endif
                            </td>

                            <td class="small">
                                @if($booking->cancellation_fine > 0 && !$booking->fine_paid)
                                    <span style="color:#dc2626;" class="fw-medium">Rs {{ number_format($booking->cancellation_fine, 2) }} (Unpaid)</span>
                                @elseif($booking->cancellation_fine > 0 && $booking->fine_paid)
                                    <span style="color:#16a34a;" class="fw-medium">Rs {{ number_format($booking->cancellation_fine, 2) }} (Paid)</span>
                                @else
                                    <span class="small text-muted">—</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <a href="{{ route('shop.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary mb-1">
                                    <i class="bi bi-eye me-1"></i> View
                                </a>

                                @if($booking->status === 'pending')
                                    <form action="{{ route('shop.bookings.update', $booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="btn btn-sm btn-success">Confirm</button>
                                    </form>

                                    <form action="{{ route('shop.bookings.update', $booking->id) }}" method="POST" class="d-inline ms-1">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="btn btn-sm btn-secondary">Cancel</button>
                                    </form>

                                @elseif($booking->status === 'confirmed')
                                    <form method="POST" action="{{ route('shop.bookings.complete', $booking->id) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Mark this booking as completed?')">
                                            <i class="bi bi-check-circle me-1"></i> Mark Complete
                                        </button>
                                    </form>

                                    <form action="{{ route('shop.bookings.update', $booking->id) }}" method="POST" class="d-inline ms-1">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="btn btn-sm btn-secondary">Cancel</button>
                                    </form>

                                @elseif($booking->isCodPending())
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#cashModal{{ $booking->id }}">
                                        <i class="bi bi-cash me-1"></i> Record Cash
                                    </button>

                                    <div class="modal fade" id="cashModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Record Cash - Booking #{{ $booking->id }}</h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="{{ route('shop.bookings.record-cash', $booking->id) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p class="small text-muted mb-2">
                                                            Amount: <strong>Rs {{ number_format($booking->final_amount) }}</strong>
                                                        </p>
                                                        <label class="form-label small">Note (optional)</label>
                                                        <input type="text" name="notes" class="form-control form-control-sm" placeholder="e.g. Cash received at counter">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-sm btn-success">Confirm</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                @else
                                    <span class="small text-muted">No actions</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <i class="bi bi-inbox fs-2 text-muted opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-0">No bookings found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="pt-3 px-3">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
