@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Contact Messages</h4>
        <span class="badge bg-danger fs-6">{{ $counts['unread'] }} Unread</span>
    </div>

    {{-- Filter Tabs --}}
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $filter === 'all' ? 'active' : '' }}"
               href="{{ route('admin.contact.index', ['filter' => 'all']) }}">
                All <span class="badge bg-secondary">{{ $counts['all'] }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $filter === 'unread' ? 'active' : '' }}"
               href="{{ route('admin.contact.index', ['filter' => 'unread']) }}">
                Unread <span class="badge bg-danger">{{ $counts['unread'] }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $filter === 'resolved' ? 'active' : '' }}"
               href="{{ route('admin.contact.index', ['filter' => 'resolved']) }}">
                Resolved <span class="badge bg-success">{{ $counts['resolved'] }}</span>
            </a>
        </li>
    </ul>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="admin-card mb-4">
        <div class="admin-card-header">
            <h5><i class="bi bi-envelope me-2 text-primary"></i>Contact Messages</h5>
            <span class="cat-count-badge">{{ $messages->total() }} records</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="admin-table-head">
                    <tr>
                        <th class="py-3">Status</th>
                        <th class="py-3">Name</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Subject</th>
                        <th class="py-3">Received</th>
                        <th class="py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $msg)
                    <tr class="{{ $msg->status === 'unread' ? 'fw-bold' : '' }}">
                        <td>
                            @if($msg->status === 'unread')
                                <span class="badge-suspended">Unread</span>
                            @elseif($msg->status === 'read')
                                <span class="cat-count-badge">Read</span>
                            @else
                                <span class="badge-active">Resolved</span>
                            @endif
                        </td>
                        <td class="small" style="color:var(--text-primary);">{{ $msg->name }}</td>
                        <td class="small admin-text-muted">{{ $msg->email }}</td>
                        <td class="small admin-text-muted">{{ Str::limit($msg->subject, 40) }}</td>
                        <td class="small admin-text-muted">{{ $msg->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('admin.contact.show', $msg) }}"
                               class="btn btn-sm btn-outline-primary rounded-pill px-3">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-inbox fs-2 text-muted opacity-50 d-block mb-2"></i>
                            <p class="admin-text-muted mb-0">No messages found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($messages->hasPages())
            <div class="admin-card-body pt-0">{{ $messages->appends(['filter' => $filter])->links() }}</div>
        @endif
    </div>
</div>
@endsection
