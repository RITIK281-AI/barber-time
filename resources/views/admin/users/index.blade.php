@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="admin-title mb-1">Customers</h2>
            <p class="admin-text-muted mb-0">Monitor customer activity, loyalty, bookings, and penalties.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card h-100">
                <div class="stat-header">Total Customers</div>
                <div class="stat-value">{{ number_format($summary['total_customers']) }}</div>
                <div class="stat-note">Based on current filters</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card h-100">
                <div class="stat-header">With Completed Bookings</div>
                <div class="stat-value">{{ number_format($summary['with_completed_bookings']) }}</div>
                <div class="stat-note">Customers with at least one completed visit</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card h-100">
                <div class="stat-header">Pending Fine Cases</div>
                <div class="stat-value">{{ number_format($summary['with_unpaid_fines']) }}</div>
                <div class="stat-note">Customers with unpaid cancellation/no-show fine</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card h-100">
                <div class="stat-header">Total Loyalty Points</div>
                <div class="stat-value">{{ number_format($summary['total_loyalty_points']) }}</div>
                <div class="stat-note">Current points balance across customers</div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- search --}}
    <div class="admin-card admin-card-body mb-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="admin-label">Search</label>
                <input type="text" name="search" class="admin-input"
                       placeholder="Name, email or phone..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn-admin-primary">
                    <i class="bi bi-search me-1"></i> Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- users table --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h5><i class="bi bi-people me-2 text-primary"></i>All Customers</h5>
            <span class="cat-count-badge">{{ $users->total() }} customers</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="admin-table-head">
                    <tr>
                        <th class="ps-4 py-3">S.N.</th>
                        <th class="py-3">Customer</th>
                        <th class="py-3">Contact</th>
                        <th class="py-3">Joined</th>
                        <th class="py-3">Loyalty Points</th>
                        <th class="py-3">Completed Bookings</th>
                        <th class="py-3">Unpaid Fine</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td class="ps-4 text-muted fw-medium">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    {{-- avatar initial --}}
                                    <div class="icon-box icon-box-blue"
                                         style="width:38px; height:38px; border-radius:50%; font-size:1rem; font-weight:700;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-medium small" style="color:var(--text-primary);">
                                            {{ $user->name }}
                                        </p>
                                        <small class="admin-text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="small">
                                <div class="fw-medium" style="color:var(--text-primary);">{{ $user->phone ?? '—' }}</div>
                                <div class="admin-text-muted">{{ $user->email }}</div>
                            </td>
                            <td class="admin-text-muted small">{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                @if($user->loyalty_points > 0)
                                    <span class="cat-count-badge">
                                        <i class="bi bi-gift me-1" style="font-size:0.7rem;"></i>
                                        {{ $user->loyalty_points }} pts
                                    </span>
                                @else
                                    <span class="admin-text-muted small">0 pts</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="badge-active">{{ $user->completed_bookings_count }} completed</span>
                                    <small class="admin-text-muted">{{ $user->bookings_count }} total</small>
                                </div>
                            </td>
                            <td>
                                @php($unpaidFineAmount = (float) ($user->unpaid_fine_amount ?? 0))
                                @if($unpaidFineAmount > 0)
                                    <span class="badge-suspended">Rs {{ number_format($unpaidFineAmount, 0) }}</span>
                                @else
                                    <span class="badge-active">No due</span>
                                @endif
                            </td>
                            <td>
                                @if($user->unpaid_fines_count > 0)
                                    <span class="badge-suspended">Needs Attention</span>
                                @elseif($user->completed_bookings_count >= 5)
                                    <span class="badge-active">Loyal</span>
                                @elseif($user->bookings_count > 0)
                                    <span class="badge-info">Active</span>
                                @else
                                    <span class="badge-pending">New</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="icon-box icon-box-blue mx-auto mb-3"
                                     style="width:56px; height:56px; font-size:1.75rem;">
                                    <i class="bi bi-people"></i>
                                </div>
                                <p class="fw-semibold mb-1" style="color:var(--text-primary);">No users found</p>
                                <p class="admin-text-muted small mb-0">Try adjusting your search.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- pagination --}}
        @if($users->hasPages())
            <div class="admin-card-body pt-0">
                {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

</div>
@endsection
