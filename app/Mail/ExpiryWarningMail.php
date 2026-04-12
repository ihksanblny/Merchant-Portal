<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExpiryWarningMail extends Mailable
{
    use Queueable, SerializesModels;

    public $atRiskBatches;

    /**
     * Create a new message instance.
     */
    public function __construct($atRiskBatches)
    {
        $this->atRiskBatches = $atRiskBatches;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Peringatan: Stok Hampir Kedaluwarsa!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.warnings.expiry',
        );
    }
}
