<?php

namespace Modules\Email\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Email\Models\MailServer;
use Illuminate\Support\Facades\Config;

class MailServerFactory extends Factory
{
    protected $model = MailServer::class;

    public function definition()
    {
        return [
            'provider' => env('MAIL_MAILER', 'smtp'),  // Use MAIL_PROVIDER from .env or null if not found
            'host' => env('MAIL_HOST', null),  // Use MAIL_HOST from .env or null if not found
            'port' => env('MAIL_PORT', null),  // Use MAIL_PORT from .env or null if not found
            'username' => env('MAIL_USERNAME', null),  // Use MAIL_USERNAME from .env or null if not found
            'password' => env('MAIL_PASSWORD', null),  // Use MAIL_PASSWORD from .env or null if not found
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),  // Use MAIL_ENCRYPTION from .env or null if not found
            'from_name' => env('MAIL_FROM_NAME', null),  // Use MAIL_SENDER_NAME from .env or null if not found
            'from_address' => env('MAIL_FROM_ADDRESS', null)  // Use MAIL_SENDER_EMAIL from .env or null if not found
            //'reply_to_email' => env('MAIL_REPLY_TO_EMAIL', null),  // Use MAIL_REPLY_TO_EMAIL from .env or null if not found
          //  'is_default' => env('MAIL_IS_DEFAULT', false),  // Use MAIL_IS_DEFAULT from .env or false if not found
        ];
    }
}
