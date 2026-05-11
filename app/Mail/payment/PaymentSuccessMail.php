<?php

namespace App\Mail\Payment;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Successful - Thank You for Choosing TrimTime!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-success',
        );
    }
}
