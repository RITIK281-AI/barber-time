@extends('barbershop.layouts.shop')

@section('content')
<div class="container-fluid py-4">

    {{-- page heading --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Shop Schedule</h2>
            <p class="text-muted mb-0">Manage your weekly closed days and specific holiday dates.</p>
        </div>
    </div>

    {{-- alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- weekly closed days section --}}
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-calendar-week me-2 text-primary"></i>Weekly Closed Days
                    </h5>
                    <p class="text-muted small mb-0 mt-1">These days repeat every week.</p>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('shop.schedule.closed-days.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="d-flex flex-column gap-2">
                            @foreach($dayNames as $dayNum => $dayName)
                                @php $isClosed = in_array($dayNum, $closedDays); @endphp

                                <div class="day-row d-flex align-items-center justify-content-between p-3 rounded border
                                    {{ $isClosed ? 'border-danger bg-danger bg-opacity-10' : 'border-light bg-light' }}"
                                    id="row_{{ $dayNum }}">

                                    <div class="d-flex align-items-center gap-2">
                                        @if(in_array($dayNum, [0, 6]))
                                            <i class="bi bi-sun text-warning fs-5"></i>
                                        @else
                                            <i class="bi bi-calendar2 text-secondary fs-5"></i>
                                        @endif
                                        <span class="fw-medium">{{ $dayName }}</span>
                                        @if(in_array($dayNum, [0, 6]))
                                            <span class="badge bg-warning text-dark">Weekend</span>
                                        @endif
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <span class="small text-muted day-label" id="label_{{ $dayNum }}">
                                            {{ $isClosed ? 'Closed' : 'Open' }}
                                        </span>
                                        <div class="form-check form-switch mb-0">
                                            <input
                                                class="form-check-input day-toggle"
                                                type="checkbox"
                                                name="closed_days[]"
                                                value="{{ $dayNum }}"
                                                id="day_{{ $dayNum }}"
                                                data-day="{{ $dayNum }}"
                                                {{ $isClosed ? 'checked' : '' }}
                                                style="cursor:pointer; width:2.5em; height:1.3em;"
                                            >
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Save Closed Days
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- holiday dates section --}}
        <div class="col-lg-7">
            <div class="row g-4">

                {{-- add holiday form --}}
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="bi bi-calendar-plus me-2 text-danger"></i>Add Holiday Date
                            </h5>
                            <p class="text-muted small mb-0 mt-1">All pending and confirmed bookings on this date will be cancelled.</p>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('shop.schedule.holiday.confirm') }}" method="GET">
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label for="date" class="form-label fw-medium">Date <span class="text-danger">*</span></label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            id="date"
                                            name="date"
                                            min="{{ now()->toDateString() }}"
                                            value="{{ old('date') }}"
                                            required
                                        >
                                    </div>
                                    <div class="col-md-5">
                                        <label for="reason" class="form-label fw-medium">Reason <span class="text-muted small">(optional)</span></label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="reason"
                                            name="reason"
                                            placeholder="e.g. Dashain, Staff Training"
                                            maxlength="255"
                                            value="{{ old('reason') }}"
                                        >
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="bi bi-eye me-1"></i>Preview
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- holidays list --}}
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold">
                                <i class="bi bi-calendar-x me-2 text-warning"></i>Scheduled Holidays
                            </h5>
                            <span class="badge bg-secondary">{{ $holidays->count() }} total</span>
                        </div>
                        <div class="card-body p-0">
                            @if($holidays->isEmpty())
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-calendar-check fs-1 d-block mb-2"></i>
                                    No holiday dates added yet.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Day</th>
                                                <th>Reason</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($holidays as $holiday)
                                                <tr>
                                                    <td class="fw-medium">
                                                        {{ $holiday->date->format('d M Y') }}
                                                        @if($holiday->date->isToday())
                                                            <span class="badge bg-danger ms-1">Today</span>
                                                        @elseif($holiday->date->isFuture())
                                                            <span class="badge bg-warning text-dark ms-1">Upcoming</span>
                                                        @else
                                                            <span class="badge bg-secondary ms-1">Past</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-muted">{{ $holiday->date->format('l') }}</td>
                                                    <td class="text-muted">{{ $holiday->reason ?? '—' }}</td>
                                                    <td class="text-end">
                                                        <form action="{{ route('shop.schedule.holiday.destroy', $holiday->id) }}" method="POST"
                                                            onsubmit="return confirm('Remove this holiday date?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    // update row style and label when a toggle is switched
    document.querySelectorAll('.day-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            var day   = this.dataset.day;
            var row   = document.getElementById('row_' + day);
            var label = document.getElementById('label_' + day);

            if (this.checked) {
                label.textContent = 'Closed';
                row.classList.add('border-danger', 'bg-danger', 'bg-opacity-10');
                row.classList.remove('border-light', 'bg-light');
            } else {
                label.textContent = 'Open';
                row.classList.remove('border-danger', 'bg-danger', 'bg-opacity-10');
                row.classList.add('border-light', 'bg-light');
            }
        });
    });
</script>

@endsection
