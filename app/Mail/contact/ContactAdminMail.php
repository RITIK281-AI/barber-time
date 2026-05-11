<?php

namespace App\Mail\contact;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
        public string $email,
        public string $contactSubject,
        public string $message,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Contact Message from ' . $this->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-admin',
            with: [
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->contactSubject,
                'contactMessage' => $this->message,
            ],
        );
    }
}
