@extends('frontend.layouts.app')
@section('title', 'Book a Service')
@section('content')

{{-- Block booking if user has unpaid fines across any shop --}}
@if($unpaidFines->isNotEmpty())
<section class="py-5 mt-5">
    <div class="container" style="max-width: 720px;">
        <div class="alert alert-danger">
            <h5><i class="bi bi-exclamation-triangle-fill me-2"></i>Booking Blocked</h5>
            <p class="mb-0">Pay your outstanding fine(s) before making a new booking.</p>
        </div>
        @foreach($unpaidFines as $b)
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-3 d-flex flex-row justify-content-between align-items-center">
                <div>
                    <strong>{{ $b->barberShop?->name }}</strong> — {{ $b->service?->name }}<br>
                    <small class="text-muted">{{ optional($b->booking_date)->format('Y-m-d') }}</small><br>
                    <span class="text-danger fw-semibold">Fine: Rs. {{ number_format($b->cancellation_fine, 2) }}</span>
                </div>
                <form action="{{ route('user.fine.initiate', $b->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-danger">Pay via Khalti</button>
                </form>
            </div>
        @endforeach
    </div>
</section>

@else

<section class="py-5 mt-5">
    <div class="container" style="max-width: 720px;">

        <h1 class="fw-bold mb-1">Book a Service</h1>
        <p class="text-muted mb-4">
            Shop hours:
            <strong>
                {{ \Carbon\Carbon::parse($shop->opening_time)->format('g:i A') }} –
                {{ \Carbon\Carbon::parse($shop->closing_time)->format('g:i A') }}
            </strong>
        </p>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                <form method="POST" action="{{ route('frontend.bookings.store', $shop->id) }}">
                    @csrf

                    {{-- Hidden inputs --}}
                    <input type="hidden" name="service_ids" value="{{ $selectedServices->pluck('id')->join(',') }}">
                    <input type="hidden" name="points_to_redeem" id="hidden-points" value="0">

                    {{-- Shop (read only) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Barber Shop</label>
                        <input type="text" class="form-control" value="{{ $shop->name }}" readonly>
                    </div>

                    {{-- Selected services summary (read only) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Selected Services</label>

                        @if($selectedServices->isEmpty())
                            <div class="alert alert-warning mb-0">
                                No services selected.
                                <a href="{{ route('frontend.shops.show', $shop->id) }}">Go back and select one.</a>
                            </div>
                        @else
                            <div class="border rounded-3 p-3 bg-light">
                                @foreach($selectedServices as $svc)
                                    <div class="d-flex justify-content-between {{ !$loop->last ? 'border-bottom pb-2 mb-2' : '' }}">
                                        <span>
                                            {{ $svc->name }}
                                            <small class="text-muted ms-1">{{ $svc->duration }} min</small>
                                        </span>
                                        <span class="fw-semibold">Rs. {{ number_format($svc->price, 2) }}</span>
                                    </div>
                                @endforeach
                                <div class="d-flex justify-content-between border-top pt-2 mt-2 fw-bold">
                                    <span>Total <small class="fw-normal text-muted">({{ $selectedServices->sum('duration') }} min)</small></span>
                                    <span class="text-trim-blue">Rs. {{ number_format($selectedServices->sum('price'), 2) }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Barber --}}
                    <div class="mb-3">
                        <label for="barber_id" class="form-label fw-semibold">Barber</label>
                        <select name="barber_id" id="barber_id"
                                class="form-select @error('barber_id') is-invalid @enderror" required>
                            <option value="" disabled selected>Select a barber</option>
                            @foreach($shop->barbers as $barber)
                                <option value="{{ $barber->id }}" {{ old('barber_id') == $barber->id ? 'selected' : '' }}>
                                    {{ $barber->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('barber_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Date --}}
                    <div class="mb-3">
                        <label for="booking_date" class="form-label fw-semibold">Date</label>
                        <input type="date" name="booking_date" id="booking_date"
                               class="form-control @error('booking_date') is-invalid @enderror"
                               value="{{ old('booking_date') }}"
                               min="{{ date('Y-m-d') }}" required>
                        @error('booking_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Time slot --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Start Time</label>
                        <input type="hidden" name="start_time" id="start_time_value" value="{{ old('start_time') }}">

                        {{-- Clicking this box opens the slot grid below --}}
                        <div id="slot-display"
                             class="form-select @error('start_time') is-invalid @enderror"
                             style="cursor: pointer; background-image: none;"
                             onclick="document.getElementById('slot-grid').style.display =
                                      document.getElementById('slot-grid').style.display === 'none' ? 'block' : 'none'">
                            {{ old('start_time') ? \Carbon\Carbon::createFromFormat('H:i', old('start_time'))->format('g:i A') : '— Pick a date & barber first —' }}
                        </div>

                        <div id="slot-grid"
                             class="border rounded-3 p-2 mt-1"
                             style="display: none; max-height: 220px; overflow-y: auto;">
                            <p class="text-muted small text-center mb-0" id="slot-placeholder">
                                Select a date and barber to see slots.
                            </p>
                        </div>

                        @error('start_time') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

                        <div class="mt-2 d-flex gap-3 small text-muted">
                            <span>🟢 Available</span>
                            <span>🔴 Booked</span>
                            <span>⚫ Past</span>
                        </div>
                    </div>

                    {{-- Loyalty points --}}
                    <hr>
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-star-fill text-warning me-1"></i> Loyalty Points
                    </h6>
                    <p class="mb-1">
                        Balance: <strong class="text-success">{{ $user->loyalty_points }} points</strong>
                        <small class="text-muted">(1 point = Rs. 5 discount)</small>
                    </p>

                    @if(!$canRedeem)
                        <div class="alert alert-warning small">
                            You need at least <strong>10 points</strong> to redeem.
                            You have <strong>{{ $user->loyalty_points }}</strong>.
                        </div>
                    @else
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @foreach([10, 20, 30, 50] as $tier)
                                @if($tier <= $maxRedeemable)
                                    <button type="button" class="btn btn-outline-primary btn-sm tier-btn"
                                            data-points="{{ $tier }}"
                                            data-discount="{{ $tier === 50 ? 300 : ($tier * 5) }}">
                                        {{ $tier }} pts (−Rs.{{ $tier === 50 ? 300 : ($tier * 5) }})
                                    </button>
                                @endif
                            @endforeach
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="no-redeem-btn">
                                Don't redeem
                            </button>
                        </div>

                        {{-- Price summary --}}
                        <div class="bg-light rounded-3 p-3 small">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Service Price</span>
                                <span>Rs. {{ number_format($selectedServices->sum('price'), 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1 text-success d-none" id="discount-row">
                                <span>Loyalty Discount</span>
                                <span id="discount-cell">−Rs. 0</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold border-top pt-2 mt-1">
                                <span>Total Payable</span>
                                <span id="final-cell">Rs. {{ number_format($selectedServices->sum('price'), 2) }}</span>
                            </div>
                        </div>
                    @endif

                    @error('booking_error')
                        <div class="alert alert-danger mt-3">{{ $message }}</div>
                    @enderror

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-trim text-white px-4">Confirm Booking</button>
                        <a href="{{ route('frontend.shops.show', $shop->id) }}" class="btn btn-outline-secondary">
                            Back to Shop
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>

@endif

{{-- Slot styles --}}
<style>
    .slot-btn {
        width: calc(33% - 6px);
        margin: 3px;
        padding: 5px;
        border-radius: 6px;
        font-size: 0.8rem;
        text-align: center;
        cursor: pointer;
        border: none;
        font-weight: 500;
        display: inline-block;
    }
    .slot-btn.available { background: #f0fdf4; border: 1.5px solid #22c55e; color: #15803d; }
    .slot-btn.available:hover, .slot-btn.selected { background: #22c55e; color: #fff; border-color: #16a34a; }
    .slot-btn.booked { background: #fef2f2; border: 1.5px solid #fca5a5; color: #b91c1c; cursor: not-allowed; }
    .slot-btn.past   { background: #f3f4f6; border: 1.5px solid #d1d5db; color: #9ca3af; cursor: not-allowed; }
</style>

@push('scripts')
<script>
    const openTime       = "{{ \Carbon\Carbon::parse($shop->opening_time)->format('H:i') }}";
    const closeTime      = "{{ \Carbon\Carbon::parse($shop->closing_time)->format('H:i') }}";
    const bookedSlotsUrl = "{{ route('frontend.bookings.booked-slots', $shop->id) }}";
    const totalDuration  = {{ $selectedServices->sum('duration') }};
    const totalPrice     = {{ $selectedServices->sum('price') }};

    const dateInput      = document.getElementById('booking_date');
    const barberSelect   = document.getElementById('barber_id');
    const slotGrid       = document.getElementById('slot-grid');
    const slotDisplay    = document.getElementById('slot-display');
    const startTimeInput = document.getElementById('start_time_value');
    const hiddenPoints   = document.getElementById('hidden-points');
    const discountRow    = document.getElementById('discount-row');
    const discountCell   = document.getElementById('discount-cell');
    const finalCell      = document.getElementById('final-cell');

    // Reload slots when date or barber changes
    dateInput.addEventListener('change', loadSlots);
    barberSelect.addEventListener('change', loadSlots);

    // If returning after validation error, reload slots automatically
    if (dateInput.value && barberSelect.value) loadSlots();

    function loadSlots() {
        const date     = dateInput.value;
        const barberId = barberSelect.value;
        if (!date || !barberId) return;

        fetch(bookedSlotsUrl + '?barber_id=' + barberId + '&date=' + date)
            .then(r => r.json())
            .then(booked => buildSlots(date, booked))
            .catch(() => buildSlots(date, []));
    }

    function buildSlots(selectedDate, booked) {
        slotGrid.innerHTML = '';
        slotGrid.style.display = 'block';

        const openMins  = toMins(openTime);
        const closeMins = toMins(closeTime);
        const today     = new Date();
        const isToday   = selectedDate === today.toISOString().slice(0, 10);
        const nowMins   = today.getHours() * 60 + today.getMinutes();
        let   hasAny    = false;

        for (let mins = openMins; mins < closeMins; mins += 15) {
            if (mins + totalDuration > closeMins) break;

            const hhmm     = toHHMM(mins);
            const label    = toAMPM(mins);
            const isPast   = isToday && mins <= nowMins;
            const isBooked = booked.some(b => mins < toMins(b.end) && (mins + totalDuration) > toMins(b.start));

            const btn      = document.createElement('button');
            btn.type       = 'button';
            btn.className  = 'slot-btn ' + (isPast ? 'past' : isBooked ? 'booked' : 'available');
            btn.textContent = label;
            btn.disabled   = isPast || isBooked;

            // Mark previously selected slot (after validation error)
            if (startTimeInput.value === hhmm) {
                btn.className = 'slot-btn selected';
            }

            btn.addEventListener('click', function() {
                slotGrid.querySelectorAll('.slot-btn.selected').forEach(b => {
                    b.className = 'slot-btn available';
                });
                this.className     = 'slot-btn selected';
                startTimeInput.value = hhmm;
                slotDisplay.textContent = label;
                slotGrid.style.display  = 'none';
            });

            slotGrid.appendChild(btn);
            hasAny = true;
        }

        if (!hasAny) {
            slotGrid.innerHTML = '<p class="text-muted small text-center mb-0">No slots available for this date.</p>';
        }
    }

    // Loyalty tier buttons
    document.querySelectorAll('.tier-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tier-btn').forEach(b => {
                b.classList.remove('btn-primary');
                b.classList.add('btn-outline-primary');
            });
            this.classList.replace('btn-outline-primary', 'btn-primary');

            const points          = parseInt(this.dataset.points, 10);
            const intendedDiscount = parseInt(this.dataset.discount, 10);
            const discount        = Math.min(intendedDiscount, totalPrice);
            const final           = Math.max(0, totalPrice - discount);

            hiddenPoints.value = points;
            discountCell.textContent = '−Rs. ' + discount;
            finalCell.textContent    = 'Rs. ' + final.toFixed(2);
            discountRow.classList.remove('d-none');
        });
    });

    // Don't redeem button
    const noRedeemBtn = document.getElementById('no-redeem-btn');
    if (noRedeemBtn) {
        noRedeemBtn.addEventListener('click', function() {
            hiddenPoints.value = 0;
            discountRow.classList.add('d-none');
            finalCell.textContent = 'Rs. ' + totalPrice.toFixed(2);
            document.querySelectorAll('.tier-btn').forEach(b => {
                b.classList.replace('btn-primary', 'btn-outline-primary');
            });
        });
    }

    // Helpers
    function toMins(hhmm) {
        const [h, m] = hhmm.split(':').map(Number);
        return h * 60 + m;
    }
    function toHHMM(mins) {
        return String(Math.floor(mins / 60)).padStart(2, '0') + ':' + String(mins % 60).padStart(2, '0');
    }
    function toAMPM(mins) {
        const h = Math.floor(mins / 60), m = mins % 60;
        return (h % 12 || 12) + ':' + String(m).padStart(2, '0') + ' ' + (h >= 12 ? 'PM' : 'AM');
    }
</script>
@endpush

@endsection
