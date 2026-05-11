@extends('admin.layouts.app')

@section('content')

<div class="container-fluid px-0">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Partner Requests</h2>
            <p class="text-muted mb-0">Review and manage partnership applications</p>
        </div>
    </div>

    <!-- Status Filter Tabs -->
    <div class="mb-4">
        <ul class="nav nav-pills gap-2">
            @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $key => $label)
                <li class="nav-item">
                    <a href="{{ route('admin.partners.index', ['status' => $key]) }}"
                       class="nav-link {{ $status === $key ? 'active' : 'bg-light text-dark' }}">
                        {{ $label }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Table Card -->
    <div class="admin-card mb-4">
        <div class="admin-card-header">
            <h5><i class="bi bi-people me-2 text-primary"></i>Partner Requests</h5>
            <span class="cat-count-badge">{{ $partnerRequests->total() }} records</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="admin-table-head">
                        <tr>
                            <th class="ps-4 py-3">S.N.</th>
                            <th class="py-3">Shop</th>
                            <th class="py-3">Owner</th>
                            <th class="py-3">Location</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Submitted</th>
                            <th class="py-3 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($partnerRequests as $index => $request)
                            <tr>
                                <td class="ps-4 text-muted fw-medium">
                                    {{ $partnerRequests->firstItem() + $index }}
                                </td>
                                <td>
                                    <p class="mb-0 fw-medium small" style="color:var(--text-primary);">{{ $request->name }}</p>
                                    <small class="admin-text-muted">{{ $request->number_of_barbers }} barber(s)</small>
                                </td>
                                <td>
                                    <div class="small" style="color:var(--text-primary);">{{ $request->owner_name }}</div>
                                    <small class="admin-text-muted">{{ $request->email }}</small>
                                </td>
                                <td class="small admin-text-muted">
                                    {{ $request->address }}, {{ $request->district }}
                                </td>
                                <td>
                                    @if($request->status === 'approved')
                                        <span class="badge-active">Approved</span>
                                    @elseif($request->status === 'pending')
                                        <span class="badge-pending">Pending</span>
                                    @else
                                        <span class="badge-suspended">Rejected</span>
                                    @endif
                                </td>
                                <td class="small admin-text-muted">
                                    {{ $request->created_at->format('d M Y') }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.partners.show', $request) }}"
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox fs-2 text-muted opacity-50 d-block mb-2"></i>
                                    <p class="admin-text-muted mb-0">No partner requests found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @if($partnerRequests->hasPages())
            <div class="admin-card-body pt-0">
                {{ $partnerRequests->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>

</div>

@endsection
