@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="admin-title mb-1">Service Categories</h2>
            <p class="admin-text-muted mb-0">Manage categories that barber shops use for their services.</p>
        </div>
        <button class="btn-admin-primary"
                data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-lg me-1"></i> Add Category
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- summary row --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-blue">
                    <i class="bi bi-tag"></i>
                </div>
                <div>
                    <p class="admin-text-muted small mb-0">Total Categories</p>
                    <h4 class="mb-0 fw-bold" style="color:var(--text-primary);">{{ $categories->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-green">
                    <i class="bi bi-scissors"></i>
                </div>
                <div>
                    <p class="admin-text-muted small mb-0">Total Services Linked</p>
                    <h4 class="mb-0 fw-bold" style="color:var(--text-primary);">{{ $categories->sum('services_count') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="admin-card admin-card-body d-flex align-items-center gap-3">
                <div class="icon-box icon-box-amber">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div>
                    <p class="admin-text-muted small mb-0">Empty Categories</p>
                    <h4 class="mb-0 fw-bold" style="color:var(--text-primary);">{{ $categories->where('services_count', 0)->count() }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- table --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h5><i class="bi bi-list-ul me-2 text-primary"></i>All Categories</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="admin-table-head">
                    <tr>
                        <th class="ps-4 py-3">S.N.</th>
                        <th class="py-3">Category Name</th>
                        <th class="py-3">Slug</th>
                        <th class="py-3">Sort Order</th>
                        <th class="py-3">Services</th>
                        <th class="py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                        <tr>
                            <td class="ps-4 text-muted fw-medium">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="icon-box icon-box-blue" style="width:34px; height:34px; font-size:1rem;">
                                        <i class="bi bi-tag"></i>
                                    </div>
                                    <span class="fw-semibold" style="color:var(--text-primary);">{{ $category->name }}</span>
                                </div>
                            </td>
                            <td><code style="color:var(--primary); background:#eff6ff; padding:2px 8px; border-radius:6px;">{{ $category->slug }}</code></td>
                            <td class="text-muted">{{ $category->sort_order }}</td>
                            <td>
                                @if($category->services_count > 0)
                                    <span class="cat-count-badge">{{ $category->services_count }} services</span>
                                @else
                                    <span class="badge-pending">No services</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editCategoryModal"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                            data-sort="{{ $category->sort_order }}">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.category.categories.destroy', $category) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this category?');">
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
                            <td colspan="6" class="text-center py-5">
                                <div class="icon-box icon-box-blue mx-auto mb-3" style="width:56px; height:56px; font-size:1.75rem;">
                                    <i class="bi bi-tag"></i>
                                </div>
                                <p class="fw-semibold mb-1" style="color:var(--text-primary);">No categories yet</p>
                                <p class="admin-text-muted small mb-0">Click Add Category to create your first one.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Add Category Modal --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content admin-modal-content">
            <div class="admin-modal-header">
                <h5 class="modal-title" style="color:var(--text-primary);">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Add New Category
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.category.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="admin-label">Category Name</label>
                        <input type="text" name="name"
                               class="admin-input @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="e.g. Haircut">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-1">
                        <label class="admin-label">Sort Order</label>
                        <input type="number" name="sort_order"
                               class="admin-input"
                               value="{{ old('sort_order', 0) }}"
                               min="0">
                        <p class="password-hint mt-1">Lower number shows first in the list.</p>
                    </div>
                </div>
                <div class="admin-modal-footer d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-admin-primary">
                        <i class="bi bi-plus me-1"></i> Add Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Category Modal --}}
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content admin-modal-content">
            <div class="admin-modal-header">
                <h5 class="modal-title" style="color:var(--text-primary);">
                    <i class="bi bi-pencil me-2 text-primary"></i>Edit Category
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="admin-label">Category Name</label>
                        <input type="text" name="name" id="editName" class="admin-input">
                    </div>
                    <div class="mb-1">
                        <label class="admin-label">Sort Order</label>
                        <input type="number" name="sort_order" id="editSortOrder"
                               class="admin-input" min="0">
                        <p class="password-hint mt-1">Lower number shows first in the list.</p>
                    </div>
                </div>
                <div class="admin-modal-footer d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-admin-primary">
                        <i class="bi bi-check2 me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('editCategoryModal').addEventListener('show.bs.modal', function (e) {
        var btn  = e.relatedTarget;
        var id   = btn.getAttribute('data-id');
        var name = btn.getAttribute('data-name');
        var sort = btn.getAttribute('data-sort');

        document.getElementById('editName').value      = name;
        document.getElementById('editSortOrder').value = sort;

        // set form action to the correct category
        document.getElementById('editCategoryForm').action = '/admin/category/categories/' + id;
    });
</script>

@endsection
