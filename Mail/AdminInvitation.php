<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserActivation extends Mailable
{
    use Queueable, SerializesModels;

    public $url;

    /**
     * Create a new message instance.
     */
    public function __construct($params)
    {
        $this->url = $params["url"];
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Activate Your Account')
                    ->view('Email::emails.user-invitation')
                    ->with([
                        'url' => $this->url,
                    ]);
    }
}

