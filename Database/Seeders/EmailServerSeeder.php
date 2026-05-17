<?php

namespace Modules\Email\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Email\Models\MailServer;
use Illuminate\Support\Facades\Config;

class EmailServerSeeder extends Seeder
{
    /**
     * Kør databasen seeder.
     *
     * @return void
     */
    public function run()
    {
        // Hent mail konfiguration fra config/mail.php
        $mailConfig = Config::get('mail');

        // Opret en email server baseret på den aktuelle mail konfiguration
        MailServer::create([
            'provider' => $mailConfig['driver'] ?? 'smtp', // Mail driver (f.eks. 'smtp', 'sendmail', 'mailgun', etc.)
            'host' => $mailConfig['host'] ?? 'smtp.example.com', // SMTP-serverens værtsnavn
            'port' => $mailConfig['port'] ?? 587, // SMTP-port
            'username' => $mailConfig['username'] ?? 'user@example.com', // SMTP-brugernavn
            'password' => $mailConfig['password'] ?? 'secret', // SMTP-adgangskode
            'encryption' => $mailConfig['encryption'] ?? 'tls', // Kryptering (f.eks. 'tls', 'ssl')
            'sender_name' => $mailConfig['from']['name'] ?? 'Example Sender', // Senderens navn
            'sender_email' => $mailConfig['from']['address'] ?? 'noreply@example.com', // Senderens e-mailadresse
            'reply_to_email' => $mailConfig['from']['address'] ?? 'noreply@example.com', // Svar-til e-mailadresse
            'is_default' => true, // Standard MailServer (kan ændres, hvis nødvendigt)
        ]);
    }
}
