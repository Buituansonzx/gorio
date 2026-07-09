<?php

namespace App\Ship\Services;
use Illuminate\Support\Facades\Mail;

class MailService {
    public function send(string $to, string $subject, string $view, array $data = []): void
    {
        Mail::send($view, $data, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }
}
