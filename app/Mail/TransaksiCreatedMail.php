<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransaksiCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaksi;

    public function __construct($transaksi)
    {
        $this->transaksi = $transaksi;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Transaksi Created',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.transaksi-created',
            with: [
                'transaksi' => $this->transaksi
            ],
        );
    }

    public function attachments()
    {
        return [];
    }
}
