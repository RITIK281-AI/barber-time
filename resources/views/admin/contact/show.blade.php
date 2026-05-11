@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Message Detail</h4>
        <a href="{{ route('admin.contact.index') }}" class="btn btn-outline-secondary btn-sm">
            ← Back to Inbox
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <table class="table table-borderless mb-0">
                <tr>
                    <th width="120">From</th>
                    <td>{{ $contactMessage->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a></td>
                </tr>
                <tr>
                    <th>Subject</th>
                    <td>{{ $contactMessage->subject }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($contactMessage->status === 'unread')
                            <span class="badge bg-danger">Unread</span>
                        @elseif($contactMessage->status === 'read')
                            <span class="badge bg-secondary">Read</span>
                        @else
                            <span class="badge bg-success">Resolved</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Received</th>
                    <td>{{ $contactMessage->created_at->format('d M Y, h:i A') }}</td>
                </tr>
            </table>

            <hr>

            <h6 class="fw-bold">Message</h6>
            <p class="text-muted">{{ $contactMessage->message }}</p>
        </div>
    </div>

    {{-- Actions --}}
    <div class="d-flex gap-2">
        @if($contactMessage->status !== 'resolved')
        <form action="{{ route('admin.contact.resolve', $contactMessage) }}" method="POST" class="d-inline">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-success">✓ Mark as Resolved</button>
        </form>
        @endif

        @if($contactMessage->status === 'read' || $contactMessage->status === 'resolved')
        <form action="{{ route('admin.contact.unread', $contactMessage) }}" method="POST" class="d-inline">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-outline-warning">Mark as Unread</button>
        </form>
        @endif

        <form action="{{ route('admin.contact.destroy', $contactMessage) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Delete this message permanently?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">Delete</button>
        </form>
    </div>
</div>
@endsection
