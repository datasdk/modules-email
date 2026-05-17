<?php

namespace Modules\Email\Contracts\Abstract;

use Modules\Email\Models\Email;
use Modules\Email\Contracts\Interfaces\EmailInterface;

abstract class EmailContract implements EmailInterface
{
    protected Email $email;

    /**
     * Tjek om e-mail kan sendes ved at kalde handle automatisk
     */
    public function canSend(Email $email): bool
    {
        $this->email = $email;

        // Kald handle automatisk
        if (method_exists($this, 'handle')) {
            $success = $this->handle($email);

            // Hvis handle returnerer false, tilføj en error til email
            if (!$success) {
            
                return false;
            }
        }

        return true;
    }

    /**
     * Tilføj fejl til email-modellen
     */
    public function addError(string|array $error): void
    {
        if (!$this->email) {
            return;
        }

        // Hvis det er en string, lav det til array
        $errors = is_array($error) ? $error : [$error];

        foreach ($errors as $err) {
            $this->email->addError($err);
        }
    }

    /**
     * Child-klasser skal implementere handle
     */
    abstract public function handle(Email $email): bool;
}
