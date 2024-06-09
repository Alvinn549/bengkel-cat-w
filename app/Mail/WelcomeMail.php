<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(private $name)
    {
        //
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Welcome',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.welcome',
            with: [
                'name' => $this->name,
            ]
        );
    }

    public function attachments()
    {
        return [];
    }
}
