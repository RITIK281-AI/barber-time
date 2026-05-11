<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = ContactMessage::latest();

        if ($filter === 'unread') {
            $query->where('status', 'unread');
        } elseif ($filter === 'resolved') {
            $query->where('status', 'resolved');
        }

        $messages = $query->paginate(15);

        // For badge counts
        $counts = [
            'all'      => ContactMessage::count(),
            'unread'   => ContactMessage::where('status', 'unread')->count(),
            'resolved' => ContactMessage::where('status', 'resolved')->count(),
        ];

        return view('admin.contact.index', compact('messages', 'filter', 'counts'));
    }

    public function show(ContactMessage $contactMessage)
    {
        // Auto-mark as read when admin opens it
        if ($contactMessage->status === 'unread') {
            $contactMessage->update(['status' => 'read']);
        }

        return view('admin.contact.show', compact('contactMessage'));
    }

    public function markResolved(ContactMessage $contactMessage)
    {
        $contactMessage->update(['status' => 'resolved']);

        return redirect()->route('admin.contact.index')
                         ->with('success', 'Message marked as resolved.');
    }

    public function markUnread(ContactMessage $contactMessage)
    {
        $contactMessage->update(['status' => 'unread']);

        return redirect()->back()->with('success', 'Message marked as unread.');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact.index')
                         ->with('success', 'Message deleted.');
    }
}
