@extends('barbershop.layouts.shop')

@section('content')

<div class="container-fluid px-0">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Manage Barbers</h2>
            <p class="text-muted mb-0">View, edit and manage your barbers' availability & performance</p>
        </div>
        <a href="{{ route('shop.barbers.create') }}" class="btn btn-primary btn-lg shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Add New Barber
        </a>
    </div>

    <!-- Search + Filter -->
    <form method="GET" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-5 col-lg-4">
                <label for="search" class="form-label fw-medium mb-1">Search Barbers</label>
                <div class="input-group">
                    <input type="text" name="search" id="search" class="form-control"
                           placeholder="Name, phone or email..." value="{{ request('search') }}">
                    @if(request('search'))
                        <button class="btn btn-outline-secondary" type="button"
                                onclick="document.getElementById('search').value='';this.form.submit()">×</button>
                    @endif
                </div>
            </div>

            <div class="col-md-4 col-lg-3">
                <label for="status" class="form-label fw-medium mb-1">Status</label>
                <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
                <a href="{{ route('shop.barbers.index') }}" class="btn btn-outline-secondary flex-grow-1">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <!-- Active Filters -->
    @if(request('search') || request('status'))
        <div class="mb-4 d-flex flex-wrap gap-2">
            @if(request('search'))
                <span class="badge bg-light text-dark border px-3 py-2">Search: <strong>{{ request('search') }}</strong></span>
            @endif
            @if(request('status'))
                <span class="badge bg-light text-dark border px-3 py-2">Status: <strong>{{ ucfirst(request('status')) }}</strong></span>
            @endif
        </div>
    @endif

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Barbers Table -->
    <div class="stat-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-0">Barber Records</h5>
                <p class="text-muted small mb-0">{{ $barbers->total() }} records found</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:var(--bg-secondary, #f8fafc);">
                        <tr>
                            <th class="py-3 ps-3 small text-muted fw-semibold">S.N.</th>
                            <th class="py-3 small text-muted fw-semibold">Barber</th>
                            <th class="py-3 small text-muted fw-semibold">Phone</th>
                            <th class="py-3 small text-muted fw-semibold">Experience</th>
                            <th class="py-3 small text-muted fw-semibold">Rating</th>
                            <th class="py-3 small text-muted fw-semibold">Status</th>
                            <th class="py-3 text-center small text-muted fw-semibold">Actions</th>
                        </tr>
                </thead>
                <tbody>
                    @forelse($barbers as $index => $barber)
                        <tr>
                                <td class="ps-3 small text-muted">{{ $barbers->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle overflow-hidden me-3" style="width:48px; height:48px;">
                                            @if($barber->profile_image)
                                                <img src="{{ asset('storage/' . $barber->profile_image) }}"
                                                     alt="{{ $barber->name }}" class="w-100 h-100 object-fit-cover">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                    <i class="bi bi-person fs-4 text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-medium small" style="color:var(--text-primary);">{{ $barber->name }}</p>
                                            <small class="text-muted small">{{ $barber->email ?? '—' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="small text-muted">{{ $barber->phone ?? '—' }}</td>
                                <td class="small text-muted">
                                    {{ $barber->experience_years ? $barber->experience_years . ' yrs' : '—' }}
                                </td>
                                <td>
                                    @if($barber->average_rating > 0)
                                        <span class="text-warning me-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                {{ $barber->average_rating >= $i ? '★' : '☆' }}
                                            @endfor
                                        </span>
                                        <small class="text-muted">({{ $barber->total_reviews ?? 0 }})</small>
                                    @else
                                        <small class="text-muted">No ratings yet</small>
                                    @endif
                                </td>
                                <td>
                                    @if($barber->status === 'active')
                                        <span class="dash-badge badge-confirmed">Active</span>
                                    @else
                                        <span class="dash-badge" style="background:#e5e7eb;color:#6b7280;">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('shop.barbers.show', $barber) }}"
                                        class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                            <i class="bi bi-eye me-1"></i> View
                                        </a>
                                        <a href="{{ route('shop.barbers.edit', $barber) }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('shop.barbers.destroy', $barber) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Delete this barber? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                <i class="bi bi-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox fs-2 text-muted opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-0">No barbers found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($barbers->hasPages())
            <div class="pt-3 px-3">
                {{ $barbers->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
