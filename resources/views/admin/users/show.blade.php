@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="admin-title mb-1">Customer Details</h2>
            <p class="admin-text-muted mb-0">Admin overview for {{ $user->name }}</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Customers
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card h-100">
                <div class="stat-header">Total Bookings</div>
                <div class="stat-value">{{ $user->bookings_count }}</div>
                <div class="stat-note">All-time booking records</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card h-100">
                <div class="stat-header">Completed</div>
                <div class="stat-value">{{ $user->completed_bookings_count }}</div>
                <div class="stat-note">Successfully completed visits</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card h-100">
                <div class="stat-header">Unpaid Fine Amount</div>
                <div class="stat-value">Rs {{ number_format((float) ($user->unpaid_fine_amount ?? 0), 0) }}</div>
                <div class="stat-note">{{ $user->unpaid_fines_count }} unpaid fine case(s)</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card h-100">
                <div class="stat-header">Review Activity</div>
                <div class="stat-value">{{ $reviews->total() }}</div>
                <div class="stat-note">Shop/barber reviews submitted</div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-xl-4 col-lg-5">
            <div class="admin-card mb-4">
                <div class="admin-card-body text-center">
                    <div class="profile-initial mx-auto mb-3">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <h5 class="mb-1 fw-bold" style="color:var(--text-primary);">{{ $user->name }}</h5>
                    <p class="admin-text-muted mb-2">{{ $user->email }}</p>

                    @if($user->unpaid_fines_count > 0)
                        <span class="badge-suspended">Needs Attention</span>
                    @elseif($user->completed_bookings_count >= 5)
                        <span class="badge-active">Loyal Customer</span>
                    @elseif($user->bookings_count > 0)
                        <span class="badge-info">Active Customer</span>
                    @else
                        <span class="badge-pending">New Customer</span>
                    @endif
                </div>
            </div>

            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h5><i class="bi bi-person-vcard me-2 text-primary"></i>Account & Activity</h5>
                </div>
                <div class="admin-card-body">
                    <div class="profile-info-row">
                        <span class="profile-info-label">Phone</span>
                        <span class="profile-info-value">{{ $user->phone ?? '—' }}</span>
                    </div>
                    <div class="profile-info-row">
                        <span class="profile-info-label">Address</span>
                        <span class="profile-info-value">{{ $user->address ?? '—' }}</span>
                    </div>
                    <div class="profile-info-row">
                        <span class="profile-info-label">Email Verification</span>
                        <span class="profile-info-value">
                            @if($user->email_verified_at)
                                <span class="badge-active">Verified</span>
                            @else
                                <span class="badge-pending">Not Verified</span>
                            @endif
                        </span>
                    </div>
                    <div class="profile-info-row">
                        <span class="profile-info-label">Member Since</span>
                        <span class="profile-info-value">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="profile-info-row">
                        <span class="profile-info-label">Last Booking Date</span>
                        <span class="profile-info-value">{{ $recentBooking?->booking_date?->format('d M Y') ?? 'No bookings yet' }}</span>
                    </div>
                    <div class="profile-info-row">
                        <span class="profile-info-label">Profile Completion</span>
                        <span class="profile-info-value">{{ $profileCompletion }}%</span>
                    </div>
                    <div class="profile-info-row">
                        <span class="profile-info-label">Loyalty Balance</span>
                        <span class="profile-info-value"><i class="bi bi-gift text-warning me-1"></i>{{ $user->loyalty_points ?? 0 }} pts</span>
                    </div>
                    <div class="profile-info-row">
                        <span class="profile-info-label">No-Show Count</span>
                        <span class="profile-info-value">{{ $user->no_show_bookings_count }}</span>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-clock-history me-2 text-primary"></i>Loyalty Transactions</h5>
                </div>
                <div class="admin-card-body py-2">
                    @forelse($loyaltyTransactions as $transaction)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <div class="small fw-medium" style="color:var(--text-primary);">{{ ucwords(str_replace('_', ' ', $transaction->type)) }}</div>
                                <div class="admin-text-muted small">{{ $transaction->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                            <div>
                                @if(($transaction->points ?? 0) >= 0)
                                    <span class="badge-active">+{{ $transaction->points }} pts</span>
                                @else
                                    <span class="badge-suspended">{{ $transaction->points }} pts</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="admin-text-muted small my-3">No loyalty transactions found.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="admin-card mb-4" id="booking-history">
                <div class="admin-card-header">
                    <h5><i class="bi bi-calendar-check me-2 text-primary"></i>Booking History</h5>
                    <span class="cat-count-badge">{{ $bookings->total() }} records</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="admin-table-head">
                            <tr>
                                <th class="ps-4 py-3">#</th>
                                <th class="py-3">Date</th>
                                <th class="py-3">Shop / Barber</th>
                                <th class="py-3">Service</th>
                                <th class="py-3">Amount</th>
                                <th class="py-3">Fine</th>
                                <th class="py-3">Payment</th>
                                <th class="py-3">Booking Status</th>
                                <th class="py-3">Review</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $index => $booking)
                                <tr>
                                    <td class="ps-4 text-muted fw-medium">{{ $bookings->firstItem() + $index }}</td>
                                    <td class="small admin-text-muted">
                                        <div>{{ $booking->booking_date?->format('d M Y') ?? '—' }}</div>
                                        <div>{{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('h:i A') : '—' }}</div>
                                    </td>
                                    <td class="small">
                                        <div class="fw-medium" style="color:var(--text-primary);">{{ $booking->barberShop->name ?? '—' }}</div>
                                        <div class="admin-text-muted">{{ $booking->barber->name ?? '—' }}</div>
                                    </td>
                                    <td class="small admin-text-muted">{{ $booking->service->name ?? '—' }}</td>
                                    <td class="small fw-medium" style="color:var(--text-primary);">
                                        Rs {{ number_format((float) ($booking->final_amount ?? $booking->final_price ?? $booking->total_price ?? 0), 0) }}
                                    </td>
                                    <td>
                                        @if((float) $booking->cancellation_fine > 0)
                                            @if($booking->fine_paid)
                                                <span class="badge-active">Paid Rs {{ number_format((float) $booking->cancellation_fine, 0) }}</span>
                                            @else
                                                <span class="badge-suspended">Due Rs {{ number_format((float) $booking->cancellation_fine, 0) }}</span>
                                            @endif
                                        @else
                                            <span class="badge-info">No Fine</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->payment_status === 'paid')
                                            <span class="badge-active">Paid</span>
                                        @elseif($booking->payment_status === 'partially_paid')
                                            <span class="badge-pending">Partial</span>
                                        @elseif($booking->payment_status === 'refunded')
                                            <span class="badge-info">Refunded</span>
                                        @else
                                            <span class="badge-suspended">Unpaid</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->status === 'completed')
                                            <span class="badge-active">Completed</span>
                                        @elseif($booking->status === 'pending')
                                            <span class="badge-pending">Pending</span>
                                        @elseif($booking->status === 'confirmed')
                                            <span class="badge-info">Confirmed</span>
                                        @elseif($booking->status === 'cancelled')
                                            <span class="badge-suspended">Cancelled</span>
                                        @elseif($booking->status === 'no_show')
                                            <span class="badge-suspended">No Show</span>
                                        @else
                                            <span class="badge-info">{{ ucfirst($booking->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->review)
                                            <span class="badge-active">Submitted</span>
                                        @else
                                            <span class="badge-pending">Not Yet</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="icon-box icon-box-blue mx-auto mb-3" style="width:56px; height:56px; font-size:1.75rem;">
                                            <i class="bi bi-calendar-x"></i>
                                        </div>
                                        <p class="fw-semibold mb-1" style="color:var(--text-primary);">No bookings yet</p>
                                        <p class="admin-text-muted small mb-0">This customer has not made any booking.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($bookings->hasPages())
                    <div class="admin-card-body pt-0">
                        {{ $bookings->appends(request()->except('bookings_page'))->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-chat-left-text me-2 text-primary"></i>Review Activity</h5>
                    <span class="cat-count-badge">{{ $reviews->total() }} reviews</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="admin-table-head">
                            <tr>
                                <th class="ps-4 py-3">Date</th>
                                <th class="py-3">Shop</th>
                                <th class="py-3">Barber</th>
                                <th class="py-3">Rating</th>
                                <th class="py-3">Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $review)
                                <tr>
                                    <td class="ps-4 small admin-text-muted">{{ $review->created_at->format('d M Y') }}</td>
                                    <td class="small admin-text-muted">{{ $review->barberShop->name ?? '—' }}</td>
                                    <td class="small admin-text-muted">{{ $review->barber->name ?? '—' }}</td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            @if($review->shop_rating)
                                                <span class="badge-info">Shop: {{ $review->shop_rating }}/5</span>
                                            @endif
                                            @if($review->barber_rating)
                                                <span class="badge-active">Barber: {{ $review->barber_rating }}/5</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="small" style="max-width:260px; color:var(--text-secondary);">
                                        {{ $review->comment ? \Illuminate\Support\Str::limit($review->comment, 90) : 'No comment' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="icon-box icon-box-blue mx-auto mb-3" style="width:56px; height:56px; font-size:1.75rem;">
                                            <i class="bi bi-chat-left-text"></i>
                                        </div>
                                        <p class="fw-semibold mb-1" style="color:var(--text-primary);">No reviews yet</p>
                                        <p class="admin-text-muted small mb-0">This customer has not submitted reviews.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($reviews->hasPages())
                    <div class="admin-card-body pt-0">
                        {{ $reviews->appends(request()->except('reviews_page'))->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
