<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // để mailable này dùng được queue
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    /**
     * Tạo instance của mail, truyền thông tin đặt phòng.
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Tiêu đề email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Xác nhận đặt phòng thành công',
        );
    }

    /**
     * View và dữ liệu hiển thị trong email.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking_success',
            with: [
                'booking' => $this->booking,
            ],
        );
    }

    /**
     * File đính kèm (nếu có).
     */
    public function attachments(): array
    {
        return [];
    }
}
