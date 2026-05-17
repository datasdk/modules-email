<?php

namespace Modules\Email\Contracts\Emails;

use Modules\Email\Models\Email;
use App\Models\User;
use Modules\Email\Contracts\Abstract\EmailContract;;


class UserInvitation extends EmailContract
{
    public function handle(Email $email): bool
    {
        return true;
    }
}
