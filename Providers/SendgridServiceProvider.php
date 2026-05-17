<?php

namespace Modules\Email\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;
use App\Mail\SendGridTransport;
use GuzzleHttp\Client;

class SendgridServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->extend('swift.transport', function ($app) {
            return $app['mail.manager']->extend('sendgrid', function () {
                $config = $this->app['config']->get('services.sendgrid', []);

                return new SendGridTransport(
                    new Client(),
                    $config['api_key']
                );
            });
        });
    }

    public function boot()
    {
        //
    }
}
