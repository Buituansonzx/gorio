<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Throwable;
class ServerErrorMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $errorData;
    public function __construct(array $errorData)
    {
        $this->errorData = $errorData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $env = strtoupper(config('app.env'));
        $subject = "🚨 [{$env}] Server Error: {$this->errorData['message']}";

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $env = strtoupper(config('app.env'));
        return new Content(
            view: 'emails.server_error',
            with: [
                'error' => $this->errorData,
                'env' => $env,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
