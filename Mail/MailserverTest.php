<?php

namespace Modules\Email\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Email\Models\Email;

class MailserverTest extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Email $email,
        public array $params = []
    ) {}

    public function build(): self
    {
        $domain = $this->params['domain'] ?? ($_SERVER['HTTP_HOST'] ?? 'localhost');

        return $this->to($this->email->to)
            ->subject($this->email->subject)
            ->view('Email::emails.mailserver-test', [
                'domain' => $domain
            ]);
    }
}
