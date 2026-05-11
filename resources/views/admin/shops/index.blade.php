@extends('admin.layouts.app')

@section('content')

<div class="container-fluid px-0">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Barber Shops</h2>
            <p class="text-muted mb-0">Manage and overview all registered barber shops</p>
        </div>
        <a href="{{ route('admin.shops.create') }}" class="btn btn-primary btn-lg shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Add New Shop
        </a>
    </div>

    <!-- Search + Filter Form -->
    <form method="GET" class="mb-4">
        <div class="row g-3 align-items-end">
            <!-- Search -->
            <div class="col-md-5 col-lg-4">
                <label for="search" class="form-label fw-medium mb-1">Search</label>
                <div class="input-group">
                    <input type="text"
                           name="search"
                           id="search"
                           class="form-control"
                           placeholder="Shop name or address..."
                           value="{{ request('search') }}">
                    @if(request('search'))
                        <button class="btn btn-outline-secondary" type="button"
                                onclick="document.getElementById('search').value=''; this.closest('form').submit()">
                            ×
                        </button>
                    @endif
                </div>
            </div>

            <!-- Status Filter -->
            <div class="col-md-4 col-lg-3">
                <label for="status" class="form-label fw-medium mb-1">Status</label>
                <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
                <a href="{{ route('admin.shops.index') }}"
                   class="btn btn-outline-secondary flex-grow-1">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <!-- Active Filters Display (optional but nice) -->
    @if(request('search') || request('status'))
        <div class="mb-3 d-flex flex-wrap gap-2">
            @if(request('search'))
                <span class="badge bg-light text-dark border">
                    Search: <strong>{{ request('search') }}</strong>
                </span>
            @endif
            @if(request('status'))
                <span class="badge bg-light text-dark border">
                    Status: <strong>{{ ucfirst(request('status')) }}</strong>
                </span>
            @endif
        </div>
    @endif

    <!-- Main Table Card -->
    <div class="admin-card mb-4">
        <div class="admin-card-header">
            <h5><i class="bi bi-shop me-2 text-primary"></i>Shop Records</h5>
            <span class="cat-count-badge">{{ $barberShops->total() }} records</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="admin-table-head">
                        <tr>
                            <th class="ps-4 py-3">S.N.</th>
                            <th class="py-3">Shop Name</th>
                            <th class="py-3">Address</th>
                            <th class="py-3">Image</th>
                            <th class="py-3">Phone</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Created</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($barberShops as $index => $barberShop)
                            <tr>
                                <td class="ps-4 text-muted fw-medium">
                                    {{ $barberShops->firstItem() + $index }}
                                </td>
                                <td>
                                    <p class="mb-0 fw-medium small" style="color:var(--text-primary);">{{ $barberShop->name }}</p>
                                </td>
                                <td class="small admin-text-muted">
                                    {{ Str::limit($barberShop->address, 45) }}
                                </td>

                                <td>
                                    @if($barberShop->shop_image)
                                        <img src="{{ asset('storage/' . $barberShop->shop_image) }}"
                                             alt="{{ $barberShop->name }}"
                                             class="rounded shadow-sm"
                                             style="width: 70px; height: 70px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="width: 70px; height: 70px;">
                                            <i class="bi bi-scissors text-muted fs-4"></i>
                                        </div>
                                    @endif
                                </td>

                                <td class="small admin-text-muted">
                                    {{ $barberShop->phone ?? '—' }}
                                </td>

                                <td>
                                    @if($barberShop->status === 'approved')
                                        <span class="badge-active">Approved</span>
                                    @elseif($barberShop->status === 'pending')
                                        <span class="badge-pending">Pending</span>
                                    @else
                                        <span class="badge-suspended">Rejected</span>
                                    @endif
                                </td>

                                <td class="small admin-text-muted">
                                    {{ $barberShop->created_at->format('d M Y') }}
                                </td>

                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('admin.shops.edit', $barberShop) }}"
                                           class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>

                                        <form action="{{ route('admin.shops.destroy', $barberShop) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this barber shop?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                <i class="bi bi-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-inbox fs-2 text-muted opacity-50 d-block mb-2"></i>
                                    <p class="admin-text-muted mb-0">No barber shops found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @if($barberShops->hasPages())
            <div class="admin-card-body pt-0">
                {{ $barberShops->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>

</div>

@endsection
