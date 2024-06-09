<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AddedProgresPerbaikanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $progres;

    public function __construct($progres)
    {
        $this->progres = $progres;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'New Progres Perbaikan',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.added-progres-perbaikan',
            with: [
                'progres' => $this->progres
            ]
        );
    }

    public function attachments()
    {
        return [];
    }
}
