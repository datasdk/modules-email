<?php

namespace Modules\Email\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Email\Models\Email;

class UserInvitation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Email $email,
        public array $params = []
    ) {}

    public function build(): self
    {
        return $this->to($this->email->to)
            ->subject($this->email->subject)
            ->view('Email::emails.user-invitation', [
                'url' => $this->params['url'] ?? null,
            ]);
    }
}
