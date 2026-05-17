<?php

namespace Modules\Email\Contracts\Interfaces;

use Modules\Email\Models\Email;

interface EmailInterface
{
    public function handle(Email $email): bool;
}
