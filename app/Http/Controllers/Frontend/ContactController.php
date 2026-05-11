<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\contact\ContactAdminMail;
use App\Mail\contact\ContactReplyMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // Save to database
        ContactMessage::create($validated);

        // Send email to admin
        $adminEmail = config('app.admin_email') ?? env('ADMIN_EMAIL', 'trimtime66@gmail.com');
        Mail::to($adminEmail)->send(new ContactAdminMail(
            $validated['name'],
            $validated['email'],
            $validated['subject'],
            $validated['message'],
        ));

        // Send confirmation email to user
        Mail::to($validated['email'])->send(new ContactReplyMail($validated['name']));

        return redirect()->route('contact.index')->with('success', 'Thank you for your message. We will get back to you soon!');
    }
}
