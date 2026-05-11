@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="admin-title mb-1">Barber Shops</h2>
            <p class="admin-text-muted mb-0">Manage all registered barber shops.</p>
        </div>
        <a href="{{ route('admin.barbershops.create') }}" class="btn-admin-primary">
            <i class="bi bi-plus-lg me-1"></i> Add New Shop
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- search and filter --}}
    <div class="admin-card admin-card-body mb-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="admin-label">Search</label>
                <input type="text" name="search" class="admin-input"
                       placeholder="Shop name or address..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="admin-label">Status</label>
                <select name="status" class="admin-input" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                    <option value="approved"  {{ request('status') === 'approved'  ? 'selected' : '' }}>Approved</option>
                    <option value="rejected"  {{ request('status') === 'rejected'  ? 'selected' : '' }}>Rejected</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn-admin-primary">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.barbershops.index') }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- table --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h5><i class="bi bi-shop me-2 text-primary"></i>All Barber Shops</h5>
            <span class="cat-count-badge">{{ $barberShops->total() }} shops</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="admin-table-head">
                    <tr>
                        <th class="ps-4 py-3">S.N.</th>
                        <th class="py-3">Shop</th>
                        <th class="py-3">Address</th>
                        <th class="py-3">Phone</th>
                        <th class="py-3">Rating</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Joined</th>
                        <th class="py-3 text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barberShops as $index => $barberShop)
                        <tr>
                            <td class="ps-4 text-muted fw-medium">
                                {{ $barberShops->firstItem() + $index }}
                            </td>

                            {{-- shop name + image --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($barberShop->shop_image)
                                        <img src="{{ asset('storage/' . $barberShop->shop_image) }}"
                                             alt="{{ $barberShop->name }}"
                                             style="width:42px; height:42px; object-fit:cover; border-radius:var(--radius);">
                                    @else
                                        <div class="icon-box icon-box-blue"
                                             style="width:42px; height:42px;">
                                            <i class="bi bi-shop"></i>
                                        </div>
                                    @endif
                                    <p class="mb-0 fw-medium small" style="color:var(--text-primary);">
                                        {{ $barberShop->name }}
                                    </p>
                                </div>
                            </td>

                            <td class="small admin-text-muted">
                                {{ Str::limit($barberShop->address, 40) }}
                            </td>

                            <td class="small admin-text-muted">
                                {{ $barberShop->phone ?? '—' }}
                            </td>

                            <td>
                                @if($barberShop->average_rating > 0)
                                    <span class="cat-count-badge">
                                        <i class="bi bi-star-fill text-warning me-1" style="font-size:0.7rem;"></i>
                                        {{ number_format($barberShop->average_rating, 1) }}
                                    </span>
                                @else
                                    <span class="admin-text-muted small">No ratings</span>
                                @endif
                            </td>

                            <td>
                                @if($barberShop->status === 'approved')
                                    <span class="badge-active">Approved</span>
                                @elseif($barberShop->status === 'pending')
                                    <span class="badge-pending">Pending</span>
                                @elseif($barberShop->status === 'suspended')
                                    <span class="badge-suspended">Suspended</span>
                                @else
                                    <span class="badge-suspended">Rejected</span>
                                @endif
                            </td>

                            <td class="small admin-text-muted">
                                {{ $barberShop->created_at->format('d M Y') }}
                            </td>

                            {{-- actions --}}
                            <td class="text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">

                                    <a href="{{ route('admin.barbershops.show', $barberShop) }}"
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>

                                    <a href="{{ route('admin.barbershops.edit', $barberShop) }}"
                                       class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </a>

                                    {{-- suspend or restore depending on current status --}}
                                    @if($barberShop->status === 'suspended')
                                        <form action="{{ route('admin.barbershops.restore', $barberShop) }}"
                                              method="POST"
                                              onsubmit="return confirm('Restore this barber shop?');">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-success rounded-pill px-3">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.barbershops.suspend', $barberShop) }}"
                                              method="POST"
                                              onsubmit="return confirm('Suspend this barber shop? They will not be able to take new bookings.');">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                <i class="bi bi-slash-circle me-1"></i> Suspend
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="icon-box icon-box-blue mx-auto mb-3"
                                     style="width:56px; height:56px; font-size:1.75rem;">
                                    <i class="bi bi-shop"></i>
                                </div>
                                <p class="fw-semibold mb-1" style="color:var(--text-primary);">No barber shops found</p>
                                <p class="admin-text-muted small mb-0">Try adjusting your search or filters.</p>
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
