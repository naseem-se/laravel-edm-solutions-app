<?php

// ════════════════════════════════════════════════════════════════
// FIXED MAIL CLASS
// ════════════════════════════════════════════════════════════════

// php artisan make:mail ShiftInvitationMail

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShiftInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $shift;
    public $facility;
    public $worker;
    public $webLink;

    public function __construct($shift, $facility, $worker, $webLink)
    {
        $this->shift = $shift;
        $this->facility = $facility;
        $this->worker = $worker;
        $this->webLink = $webLink;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Shift Invitation from {$this->facility->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.shift-invitation',
            with: [
                'shift' => $this->shift,
                'facility' => $this->facility,
                'worker' => $this->worker,
                'webLink' => $this->webLink,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}