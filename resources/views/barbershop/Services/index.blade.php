{{-- resources/views/barbershop/services/index.blade.php --}}

@extends('barbershop.layouts.shop')

@section('content')

<div class="container-fluid px-0">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Manage Services</h2>
            <p class="text-muted mb-0">Add, edit and organize all services offered at your barbershop</p>
        </div>
        <a href="{{ route('shop.services.create') }}" class="btn btn-primary btn-lg shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Add New Service
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Services Table -->
    <div class="stat-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-0">Service Records</h5>
                <p class="text-muted small mb-0">{{ $services->count() }} records found</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:var(--bg-secondary, #f8fafc);">
                        <tr>
                            <th class="py-3 ps-3 small text-muted fw-semibold">S.N.</th>
                            <th class="py-3 small text-muted fw-semibold">Service Name</th>
                            <th class="py-3 small text-muted fw-semibold">Category</th>
                            <th class="py-3 small text-muted fw-semibold">Description</th>
                            <th class="py-3 small text-muted fw-semibold">Price</th>
                            <th class="py-3 small text-muted fw-semibold">Duration</th>
                            <th class="py-3 small text-muted fw-semibold">Status</th>
                            <th class="py-3 text-center small text-muted fw-semibold">Actions</th>
                        </tr>
                </thead>
                <tbody>
                    @forelse($services as $index => $service)
                        <tr>
                                <td class="ps-3 small text-muted">
                                    {{ $index + 1 }}
                                </td>
                                <td>
                                    <p class="mb-0 fw-medium small" style="color:var(--text-primary);">{{ $service->name }}</p>
                                </td>
                                <td class="small text-muted">
                                    {{ $service->category->name ?? '—' }}
                                </td>
                                <td class="small text-muted">
                                    {{ Str::limit($service->description ?? '—', 60) }}
                                </td>
                                <td class="small fw-medium" style="color:var(--text-primary);">
                                    Rs {{ number_format($service->price, 2) }}
                                </td>
                                <td class="small text-muted">
                                    {{ $service->duration }} min
                                </td>
                                <td>
                                    @if($service->status === 'active' || $service->status === 'available')
                                        <span class="dash-badge badge-confirmed">Active</span>
                                    @else
                                        <span class="dash-badge" style="background:#e5e7eb;color:#6b7280;">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('shop.services.edit', $service) }}"
                                           class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('shop.services.destroy', $service) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this service?');">
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
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox fs-2 text-muted opacity-50 d-block mb-2"></i>
                                <p class="text-muted mb-0">No services found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
