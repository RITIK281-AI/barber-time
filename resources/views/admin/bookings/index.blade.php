@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="admin-title mb-1">Booking Management</h2>
            <p class="admin-text-muted mb-0">Monitor all booking records across shops</p>
        </div>
        <span class="admin-text-muted small">{{ now()->format('d M Y') }}</span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-blue"><i class="bi bi-calendar2-week"></i></div>
                <div>
                    <p class="admin-text-muted small mb-0">Total Bookings</p>
                    <h4 class="mb-0 fw-bold" style="color:var(--text-primary);">{{ number_format($summary['total']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-green"><i class="bi bi-check2-circle"></i></div>
                <div>
                    <p class="admin-text-muted small mb-0">Completed</p>
                    <h4 class="mb-0 fw-bold" style="color:var(--text-primary);">{{ number_format($summary['completed']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-red"><i class="bi bi-x-circle"></i></div>
                <div>
                    <p class="admin-text-muted small mb-0">Cancelled</p>
                    <h4 class="mb-0 fw-bold" style="color:var(--text-primary);">{{ number_format($summary['cancelled']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-amber"><i class="bi bi-cash-stack"></i></div>
                <div>
                    <p class="admin-text-muted small mb-0">Paid Bookings</p>
                    <h4 class="mb-0 fw-bold" style="color:var(--text-primary);">{{ number_format($summary['paid']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card mb-4">
        <div class="admin-card-header">
            <h5><i class="bi bi-funnel me-2 text-primary"></i>Filters</h5>
            @if(request()->hasAny(['date_from','date_to','shop_id','status','payment_status','payment_method']))
                <a href="{{ route('admin.bookings.index') }}" class="admin-text-muted small">Clear filters</a>
            @endif
        </div>
        <div class="admin-card-body">
            <form method="GET" action="{{ route('admin.bookings.index') }}">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label small admin-text-muted">Date From</label>
                        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small admin-text-muted">Date To</label>
                        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small admin-text-muted">Shop</label>
                        <select name="shop_id" class="form-select form-select-sm">
                            <option value="">All Shops</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}" {{ (string) request('shop_id') === (string) $shop->id ? 'selected' : '' }}>
                                    {{ $shop->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small admin-text-muted">Booking Status</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small admin-text-muted">Payment</label>
                        <select name="payment_status" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small admin-text-muted">Method</label>
                        <select name="payment_method" class="form-select form-select-sm">
                            <option value="">All</option>
                            <option value="khalti" {{ request('payment_method') === 'khalti' ? 'selected' : '' }}>Online</option>
                            <option value="cod" {{ request('payment_method') === 'cod' ? 'selected' : '' }}>Cash</option>
                        </select>
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary px-4"><i class="bi bi-search me-1"></i> Apply</button>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-secondary px-4">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="admin-card mb-4">
        <div class="admin-card-header">
            <h5><i class="bi bi-table me-2 text-primary"></i>Booking Records</h5>
            <span class="admin-text-muted small">{{ $bookings->total() }} records</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="admin-table-head">
                    <tr>
                        <th class="ps-3 py-3">S.N.</th>
                        <th class="py-3">Customer</th>
                        <th class="py-3">Shop</th>
                        <th class="py-3">Service</th>
                        <th class="py-3">Barber</th>
                        <th class="py-3">Date</th>
                        <th class="py-3">Time</th>
                        <th class="py-3">Amount</th>
                        <th class="py-3">Payment</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $index => $booking)
                        <tr>
                            <td class="ps-3 small admin-text-muted">{{ $bookings->firstItem() + $index }}</td>
                            <td>
                                <p class="mb-0 fw-medium small" style="color:var(--text-primary);">{{ $booking->user->name ?? '—' }}</p>
                            </td>
                            <td class="small admin-text-muted">{{ $booking->barberShop->name ?? '—' }}</td>
                            <td class="small admin-text-muted">{{ $booking->service->name ?? '—' }}</td>
                            <td class="small admin-text-muted">{{ $booking->barber->name ?? '—' }}</td>
                            <td class="small admin-text-muted">{{ optional($booking->booking_date)->format('d M Y') ?? '—' }}</td>
                            <td class="small admin-text-muted">
                                {{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('h:i A') : '—' }} -
                                {{ $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('h:i A') : '—' }}
                            </td>
                            <td class="small fw-medium" style="color:var(--text-primary);">
                                Rs {{ number_format($booking->final_amount ?? $booking->total_price ?? 0, 2) }}
                            </td>
                            <td>
                                @if($booking->payment_status === 'paid')
                                    <span class="badge-active">Paid</span>
                                @elseif($booking->payment_status === 'partially_paid')
                                    <span class="badge-info">Partial</span>
                                @elseif($booking->payment_status === 'refunded')
                                    <span class="badge-suspended">Refunded</span>
                                @else
                                    <span class="badge-pending">Unpaid</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->status === 'completed')
                                    <span class="badge-active">Completed</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="badge-info">Confirmed</span>
                                @elseif($booking->status === 'pending')
                                    <span class="badge-pending">Pending</span>
                                @else
                                    <span class="badge-suspended">Cancelled</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <i class="bi bi-inbox fs-2 text-muted opacity-50 d-block mb-2"></i>
                                <p class="admin-text-muted mb-0">No booking records found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="admin-card-body pt-0">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
