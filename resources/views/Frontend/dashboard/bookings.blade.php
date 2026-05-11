@php
    $upcoming  = $bookings->whereIn('status', ['pending', 'confirmed'])
                          ->filter(fn($b) => $b->booking_date->gte(today()));
    $completed = $bookings->where('status', 'completed');
    $cancelled = $bookings->where('status', 'cancelled');

    $totalCount     = $bookings->count();
    $completedCount = $completed->count();
    $upcomingCount  = $upcoming->count();
    $cancelledCount = $cancelled->count();
    $history        = $bookings->sortByDesc(fn($b) => optional($b->booking_date)->timestamp ?? 0)->values();

    // Next tier thresholds
    $points         = auth()->user()->loyalty_points ?? 0;
    $nextTier       = 500;
    $progressPct    = min(100, round(($points % $nextTier) / $nextTier * 100));
    $pointsToNext   = $nextTier - ($points % $nextTier);
@endphp

{{-- ── Stat Cards ──────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="dashboard-panel py-3 px-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon orange"><i class="bi bi-calendar3"></i></div>
                <div>
                    <div class="fw-bold fs-5">{{ $totalCount }}</div>
                    <div class="text-muted" style="font-size:0.78rem">Total</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="dashboard-panel py-3 px-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
                <div>
                    <div class="fw-bold fs-5">{{ $completedCount }}</div>
                    <div class="text-muted" style="font-size:0.78rem">Completed</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="dashboard-panel py-3 px-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon blue"><i class="bi bi-clock"></i></div>
                <div>
                    <div class="fw-bold fs-5">{{ $upcomingCount }}</div>
                    <div class="text-muted" style="font-size:0.78rem">Upcoming</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="dashboard-panel py-3 px-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon red"><i class="bi bi-x-circle"></i></div>
                <div>
                    <div class="fw-bold fs-5">{{ $cancelledCount }}</div>
                    <div class="text-muted" style="font-size:0.78rem">Cancelled</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Upcoming Appointments ─────────────────────────────────── --}}
<div class="dashboard-panel mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="panel-title">Upcoming Appointments</div>
            <div class="panel-subtitle">Your scheduled sessions</div>
        </div>
        <a href="{{ route('frontend.shops.index') }}" class="btn btn-trim-orange btn-sm px-3">
            <i class="bi bi-plus me-1"></i>New Booking
        </a>
    </div>

    @if($upcoming->isEmpty())
        <div class="text-center py-4 text-muted">
            <i class="bi bi-calendar-check d-block mb-2" style="font-size:2.5rem;color:#ddd"></i>
            <div class="fw-semibold mb-1">No upcoming appointments</div>
            <p class="small mb-3">Book your next grooming session!</p>
            <a href="{{ route('frontend.shops.index') }}" class="btn btn-trim-orange btn-sm px-4">
                Find a Barber Shop
            </a>
        </div>
    @else
        <div class="row g-3">
            @foreach($upcoming as $booking)
                @php
                    $serviceNames = $booking->services->pluck('name')->join(', ')
                                    ?: ($booking->service?->name ?? '—');

                    $appointmentTime = \Carbon\Carbon::parse(
                        $booking->booking_date->format('Y-m-d') . ' ' .
                        \Carbon\Carbon::parse($booking->start_time)->format('H:i:s')
                    );

                    // Urgency label logic
                    $hoursUntil = now()->diffInHours($appointmentTime, false);
                    if ($hoursUntil >= 0 && $hoursUntil <= 3) {
                        $urgencyClass = 'today';
                        $urgencyLabel = 'In ' . max(1, $hoursUntil) . ' hour' . ($hoursUntil != 1 ? 's' : '');
                    } elseif ($booking->booking_date->isToday()) {
                        $urgencyClass = 'today';
                        $urgencyLabel = 'Today!';
                    } elseif ($booking->booking_date->isTomorrow()) {
                        $urgencyClass = 'tomorrow';
                        $urgencyLabel = 'Tomorrow';
                    } elseif ($hoursUntil <= 72) {
                        $urgencyClass = 'soon';
                        $urgencyLabel = 'In ' . $booking->booking_date->diffInDays(today()) . ' days';
                    } else {
                        $urgencyClass = null;
                        $urgencyLabel = null;
                    }

                    $isLateCancellation = now()->gte($appointmentTime->copy()->subHour())
                                      && now()->lte($appointmentTime);
                    $fineAmount = round(max(30, min(($booking->service?->price ?? 0) * 0.10, 80)), 2);
                    $cancelMsg  = $isLateCancellation
                        ? 'WARNING: Late cancellation! A fine of Rs ' . $fineAmount . ' will be charged. Continue?'
                        : 'Are you sure you want to cancel this booking?';
                @endphp

                <div class="col-md-6">
                    <div class="upcoming-card">

                        {{-- Urgency badge --}}
                        @if($urgencyLabel)
                            <div class="urgency-badge {{ $urgencyClass }}">
                                <i class="bi bi-alarm-fill"></i> {{ $urgencyLabel }}
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="fw-bold fs-6">{{ $booking->barberShop?->name ?? '—' }}</div>
                            @if($booking->status === 'confirmed')
                                <span class="badge bg-success rounded-pill px-3">Confirmed</span>
                            @else
                                <span class="badge bg-warning text-dark rounded-pill px-3">Booked</span>
                            @endif
                        </div>

                        <div class="upcoming-detail">
                            <i class="bi bi-scissors"></i>
                            <span>{{ $serviceNames }}</span>
                        </div>
                        <div class="upcoming-detail">
                            <i class="bi bi-person"></i>
                            <span>{{ $booking->barber?->name ?? '—' }}</span>
                        </div>
                        <div class="upcoming-detail">
                            <i class="bi bi-calendar3"></i>
                            <span>{{ $booking->booking_date->format('D, d M Y') }}</span>
                        </div>
                        <div class="upcoming-detail">
                            <i class="bi bi-clock"></i>
                            <span>
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                                –
                                {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                            </span>
                        </div>
                        <div class="upcoming-detail">
                            <i class="bi bi-cash"></i>
                            <span>Rs. {{ number_format($booking->final_amount ?? $booking->total_price ?? 0, 2) }}</span>
                        </div>

                        {{-- payment guidance based on booking/payment state --}}
                        @if($booking->status === 'pending' && $booking->payment_status === 'unpaid')
                            <div class="small text-muted mt-2">
                                <i class="bi bi-hourglass-split me-1"></i>
                                Waiting for shop confirmation. Payment is available after confirmation.
                            </div>
                        @elseif($booking->payment_method === 'cod' && $booking->payment_status === 'unpaid' && $booking->status === 'confirmed')
                            <div class="small text-muted mt-2">
                                <i class="bi bi-cash-coin me-1"></i>
                                COD selected. Pay cash at the shop after your service is completed.
                            </div>
                        @elseif($booking->payment_method === 'cod' && $booking->payment_status === 'unpaid' && $booking->status === 'completed')
                            <div class="small text-warning mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Service completed. Waiting for shop to record cash payment.
                            </div>
                        @endif

                        <div class="d-flex gap-2 mt-3 flex-wrap">
                            <a href="{{ route('frontend.bookings.show', $booking->id) }}"
                               class="btn btn-outline-dark btn-sm">
                                <i class="bi bi-eye me-1"></i>View Details
                            </a>

                            @if($booking->status === 'confirmed' && $booking->payment_status === 'unpaid' && $booking->payment_method !== 'cod')
                                <a href="{{ route('payment.confirm', $booking->id) }}"
                                   class="btn btn-success btn-sm">
                                    <i class="bi bi-credit-card me-1"></i>Pay Now
                                </a>
                            @endif

                            @if($booking->status === 'confirmed' && $booking->payment_status === 'unpaid' && $booking->payment_method === 'cod')
                                <a href="{{ route('payment.confirm', $booking->id) }}"
                                   class="btn btn-outline-primary btn-sm"
                                   onclick="return confirm('Switch from COD to online payment via Khalti?')">
                                    <i class="bi bi-arrow-repeat me-1"></i>Pay Online Instead
                                </a>
                            @endif

                            @if($booking->payment_status !== 'paid')
                                <form action="{{ route('frontend.bookings.cancel', $booking->id) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('{{ $cancelMsg }}')">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-x me-1"></i>Cancel
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if($isLateCancellation)
                            <div class="text-danger small mt-2">
                                <i class="bi bi-exclamation-circle"></i>
                                Late cancel = Rs {{ number_format($fineAmount, 2) }} fine
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ── Booking History ───────────────────────────────────────── --}}
<div class="dashboard-panel p-0 overflow-hidden mb-4">
    <div class="px-4 py-3 border-bottom">
        <div class="panel-title mb-0">Booking History</div>
        <div class="panel-subtitle mb-0">All your bookings across every status</div>
    </div>

    @if($history->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-clock-history d-block mb-2" style="font-size:2.5rem;color:#ddd"></i>
            <div class="small">No booking history yet</div>
        </div>
    @else
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-primary">
                    <tr>
                        <th class="ps-4">Shop</th>
                        <th>Services</th>
                        <th>Barber</th>
                        <th>Date</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $booking)
                        @php
                            $serviceNames = $booking->services->pluck('name')->join(', ')
                                            ?: ($booking->service?->name ?? '—');
                            $shopId       = $booking->barberShop?->id;

                            $statusLabel = match($booking->status) {
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'pending'   => 'Pending',
                                'confirmed' => 'Confirmed',
                                'no_show'   => 'No Show',
                                default     => ucfirst(str_replace('_', ' ', (string) $booking->status)),
                            };

                            $statusClass = match($booking->status) {
                                'completed' => 'bg-primary',
                                'cancelled' => 'bg-secondary',
                                'pending'   => 'bg-warning text-dark',
                                'confirmed' => 'bg-success',
                                'no_show'   => 'bg-danger',
                                default     => 'bg-dark',
                            };
                        @endphp
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $booking->barberShop?->name ?? '—' }}</td>
                            <td>{{ $serviceNames }}</td>
                            <td>{{ $booking->barber?->name ?? '—' }}</td>
                            <td>
                                {{ $booking->booking_date->format('d M Y') }}
                                <br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} -
                                    {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                                </small>
                            </td>
                            <td>
                                Rs. {{ number_format($booking->final_amount ?? $booking->total_price ?? 0, 2) }}
                                @if(($booking->discount_amount ?? 0) > 0)
                                    <br><small class="text-success">
                                        <i class="bi bi-tag-fill"></i>
                                        −Rs. {{ number_format($booking->discount_amount, 2) }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $statusClass }} rounded-pill px-3 py-2">{{ $statusLabel }}</span>
                                @if(($booking->cancellation_fine ?? 0) > 0 && !$booking->fine_paid)
                                    <br><span class="badge bg-danger rounded-pill px-2 py-1 mt-1">
                                        Fine: Rs. {{ number_format($booking->cancellation_fine, 2) }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('frontend.bookings.show', $booking->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>View Details
                                    </a>

                                    @if($shopId)
                                        <a href="{{ route('frontend.shops.show', $shopId) }}"
                                           class="book-again-btn history-book-again">
                                            <i class="bi bi-arrow-repeat"></i> Book Again
                                        </a>
                                    @endif

                                    @if($booking->status === 'completed')
                                        @if($booking->review)
                                            <a href="{{ route('frontend.reviews.edit', $booking->review->id) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil me-1"></i>Edit
                                            </a>
                                        @else
                                            <a href="{{ route('frontend.reviews.create', $booking->id) }}"
                                               class="btn btn-sm btn-warning">
                                                <i class="bi bi-star me-1"></i>Review
                                            </a>
                                        @endif
                                    @endif

                                    @if(($booking->cancellation_fine ?? 0) > 0 && !$booking->fine_paid)
                                        <form action="{{ route('user.fine.initiate', $booking->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Pay fine of Rs. {{ number_format($booking->cancellation_fine, 2) }} via Khalti?')">
                                                <i class="bi bi-exclamation-triangle me-1"></i>Pay Fine
                                            </button>
                                        </form>
                                    @elseif(($booking->cancellation_fine ?? 0) > 0 && $booking->fine_paid)
                                        <span class="text-success small"><i class="bi bi-check-circle me-1"></i>Fine Paid</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- ── Recommended Nearby Shops ─────────────────────────────── --}}
@if(isset($recommendedShops) && $recommendedShops->isNotEmpty())
<div class="dashboard-panel">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="panel-title">Recommended For You</div>
            <div class="panel-subtitle">Top-rated shops you haven't tried yet</div>
        </div>
        <a href="{{ route('frontend.shops.index') }}" class="btn btn-outline-secondary btn-sm px-3">
            View All
        </a>
    </div>
    <div class="row g-3">
        @foreach($recommendedShops as $shop)
            <div class="col-6 col-lg-3">
                <div class="recommended-card">
                    @if($shop->shop_image)
                        <img src="{{ asset('storage/' . $shop->shop_image) }}"
                             class="recommended-card-img" alt="{{ $shop->name }}">
                    @else
                        <div class="recommended-card-img-placeholder">
                            <i class="bi bi-scissors"></i>
                        </div>
                    @endif
                    <div class="recommended-card-body">
                        <div class="fw-semibold" style="font-size:0.88rem;line-height:1.3">
                            {{ $shop->name }}
                        </div>
                        <div class="text-muted mt-1" style="font-size:0.75rem">
                            <i class="bi bi-geo-alt me-1"></i>{{ $shop->district ?? $shop->address ?? 'Nepal' }}
                        </div>
                        @if($shop->reviews_avg_rating)
                            <div class="mt-1" style="font-size:0.75rem;color:#f48c06">
                                <i class="bi bi-star-fill"></i>
                                {{ number_format($shop->reviews_avg_rating, 1) }}
                                <span class="text-muted">({{ $shop->reviews_count }})</span>
                            </div>
                        @endif
                        <a href="{{ route('frontend.shops.show', $shop->id) }}"
                           class="btn btn-trim-orange btn-sm w-100 mt-auto mt-2"
                           style="font-size:0.78rem">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
