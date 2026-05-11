@extends('admin.layouts.app')

@section('content')

<div class="container-fluid px-0">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Barbers</h2>
            <p class="text-muted mb-0">Manage all registered barbers across shops</p>
        </div>
    </div>

    <!-- Search + Filter Form -->
    <form method="GET" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-5 col-lg-4">
                <label for="search" class="form-label fw-medium mb-1">Search</label>
                <div class="input-group">
                    <input type="text" name="search" id="search" class="form-control"
                           placeholder="Name, phone, email or shop..." value="{{ request('search') }}">
                    @if(request('search'))
                        <button class="btn btn-outline-secondary" type="button"
                                onclick="document.getElementById('search').value=''; this.form.submit()">×</button>
                    @endif
                </div>
            </div>

            <div class="col-md-3 col-lg-2">
                <label for="status" class="form-label fw-medium mb-1">Status</label>
                <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="col-md-3 col-lg-3">
                <label for="shop" class="form-label fw-medium mb-1">Shop</label>
                <select name="shop" id="shop" class="form-select" onchange="this.form.submit()">
                    <option value="">All Shops</option>
                    @foreach($shops ?? [] as $s)
                        <option value="{{ $s->id }}" {{ request('shop') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
                <a href="{{ route('admin.barbers.index') }}" class="btn btn-outline-secondary flex-grow-1">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <!-- Active Filters -->
    @if(request('search') || request('status') || request('shop'))
        <div class="mb-4 d-flex flex-wrap gap-2">
            @if(request('search'))
                <span class="badge bg-light text-dark border px-3 py-2">
                    Search: <strong>{{ request('search') }}</strong>
                </span>
            @endif
            @if(request('status'))
                <span class="badge bg-light text-dark border px-3 py-2">
                    Status: <strong>{{ ucfirst(request('status')) }}</strong>
                </span>
            @endif
            @if(request('shop'))
                <span class="badge bg-light text-dark border px-3 py-2">
                    Shop: <strong>{{ $shops->firstWhere('id', request('shop'))?->name ?? 'Unknown' }}</strong>
                </span>
            @endif
        </div>
    @endif

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Barbers Table -->
    <div class="admin-card mb-4">
        <div class="admin-card-header">
            <h5><i class="bi bi-scissors me-2 text-primary"></i>Barber Records</h5>
            <span class="cat-count-badge">{{ $barbers->total() }} records</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:var(--bg-secondary, #f8fafc);">
                        <tr>
                            <th class="py-3 ps-3 small admin-text-muted fw-semibold">S.N.</th>
                            <th class="py-3 small admin-text-muted fw-semibold">Barber</th>
                            <th class="py-3 small admin-text-muted fw-semibold">Shop</th>
                            <th class="py-3 small admin-text-muted fw-semibold">Phone</th>
                            <th class="py-3 small admin-text-muted fw-semibold">Experience</th>
                            <th class="py-3 small admin-text-muted fw-semibold">Rating</th>
                            <th class="py-3 small admin-text-muted fw-semibold">Status</th>
                            <th class="py-3 text-center small admin-text-muted fw-semibold pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barbers as $index => $barber)
                            <tr>
                                <td class="ps-3 small admin-text-muted">{{ $barbers->firstItem() + $index }}</td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle overflow-hidden me-3"
                                            style="width:48px; height:48px; flex-shrink:0;">
                                            @if($barber->profile_image)
                                                <img src="{{ asset('storage/' . $barber->profile_image) }}"
                                                    alt="{{ $barber->name }}"
                                                    class="w-100 h-100"
                                                    style="object-fit:cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                    <i class="bi bi-person fs-4 text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                            <p class="mb-0 fw-medium small" style="color:var(--text-primary);">{{ $barber->name }}</p>
                                            <small class="admin-text-muted small">{{ $barber->email ?? '—' }}</small>
                                        </div>
                                    </div>
                                </td>

                                <td class="small admin-text-muted">
                                    @if($barber->shop)
                                        <a href="{{ route('admin.barbershops.show', $barber->shop) }}?from=barbers"
                                           class="fw-semibold text-primary text-decoration-none">
                                            {{ $barber->shop->name }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>

                                <td class="small admin-text-muted">{{ $barber->phone ?? '—' }}</td>

                                <td class="small admin-text-muted">
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
                                        <small class="text-muted">No ratings</small>
                                    @endif
                                </td>

                                <td>
                                    @if($barber->status === 'active')
                                        <span class="badge-active">Active</span>
                                    @else
                                        <span class="cat-count-badge">Inactive</span>
                                    @endif
                                </td>

                                <td class="text-center pe-3">
                                    <a href="{{ route('admin.barbers.show', $barber) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-inbox fs-2 text-muted opacity-50 d-block mb-2"></i>
                                    <p class="admin-text-muted mb-0">No barbers found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @if($barbers->hasPages())
            <div class="admin-card-body pt-0">
                {{ $barbers->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>

@endsection
