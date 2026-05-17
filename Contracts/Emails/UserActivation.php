<?php

namespace Modules\Email\Contracts\Emails;

use Modules\Email\Models\Email;
use Modules\Email\Contracts\Abstract\EmailContract;;

class UserActivation extends EmailContract
{
    public function handle(Email $email): bool
    {
        return true;
    }
}
