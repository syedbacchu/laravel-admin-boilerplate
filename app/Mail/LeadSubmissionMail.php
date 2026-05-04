<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeadSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $leadType;

    public function __construct($lead, $leadType = 'customer')
    {
        $this->lead = $lead;
        $this->leadType = $leadType;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->leadType === 'company'
                ? 'New Company Lead Submission'
                : 'New Customer Lead Submission',
            cc: ['zahid@bio-xin.com']
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lead-submission',
            with: [
                'lead' => $this->lead,
                'leadType' => $this->leadType,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
