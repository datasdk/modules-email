<?php

namespace Modules\Email\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Email\Models\Email;

class ResetPassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Email $email,
        public array $params = []
    ) {}

    public function build(): self
    {
        $first_name = $this->params['first_name'] ?? '';
        $url = $this->params['url'] ?? '';
        $company = $this->params['company'] ?? ($_SERVER['HTTP_HOST'] ?? 'localhost');
        $replyEmail = $this->params['email'] ?? config('mail.from.reply_address');

        return $this->to($this->email->to)
            ->subject($this->email->subject)
            ->replyTo($replyEmail)
            ->view('Email::emails.reset-password', [
                'first_name' => $first_name,
                'url'        => $url,
                'company'    => $company,
                'email'      => $replyEmail,
            ]);
    }
}
