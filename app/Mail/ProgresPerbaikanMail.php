<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProgresPerbaikanMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Progres Perbaikan',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.progres-perbaikan',
        );
    }
    public function attachments()
    {
        return [];
    }
}
