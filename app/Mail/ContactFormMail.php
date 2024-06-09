<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(private $name, private $email, private $pesan)
    {
        //
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Contact Form',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.contact-form',
            with: [
                'name' => $this->name,
                'email' => $this->email,
                'pesan' => $this->pesan
            ]
        );
    }

    public function attachments()
    {
        return [];
    }
}
