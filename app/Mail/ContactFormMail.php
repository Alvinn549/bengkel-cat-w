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

    public $name;
    public $email;
    public $pesan;

    public function __construct($name,  $email,  $pesan)
    {
        $this->name = $name;
        $this->email = $email;
        $this->pesan = $pesan;
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
