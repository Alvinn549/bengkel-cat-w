<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChangedStatusPerbaikanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $perbaikan;

    public function __construct($perbaikan)
    {
        $this->perbaikan = $perbaikan;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Changed Status Perbaikan',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.changed-status-perbaikan',
            with: [
                'perbaikan' => $this->perbaikan
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}
