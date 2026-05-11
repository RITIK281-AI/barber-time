<?php

namespace App\Mail\contact;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We received your message - TrimTime',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-reply',
            with: [
                'name' => $this->name,
            ],
        );
    }
}
