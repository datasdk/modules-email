<?php

namespace Modules\Email\Database\Seeders;

use Modules\Email\Database\Seeders\Contracts\AbstractEmailTemplateSeeder;

class EmailTemplateSeeder extends AbstractEmailTemplateSeeder
{


    protected string $moduleName = 'email';

    
    protected array $templates = [
        'mailserver-test' => \Modules\Email\Contracts\Emails\MailserverTest::class,
        'user-invitation' => \Modules\Email\Contracts\Emails\UserInvitation::class,
        'admin-invitation' => \Modules\Email\Contracts\Emails\AdminInvitation::class,
        'user-activation' => \Modules\Email\Contracts\Emails\UserActivation::class,
        'reset-password' => \Modules\Email\Contracts\Emails\ResetPassword::class,
    ];

}
