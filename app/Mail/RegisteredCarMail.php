<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisteredCarMail extends Mailable
{
    use Queueable, SerializesModels;

    public $kendaraan;

    public function __construct($kendaraan)
    {
        $this->kendaraan = $kendaraan;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Registered Car',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.registered-car',
            with: [
                'kendaraan' => $this->kendaraan,
            ]
        );
    }

    public function attachments()
    {
        return [];
    }
}
