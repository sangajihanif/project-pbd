<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMessage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $token, public string $url) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Kode Verifikasi OTP');
    }

    public function content(): Content
    {
        return new Content(markdown: 'send-message');
    }

    public function attachments(): array
    {
        return [];
    }
}