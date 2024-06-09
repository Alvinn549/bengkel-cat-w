<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransaksiSelesaiMail extends Mailable
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
            subject: 'Transaksi Done',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.transaksi-done',
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
