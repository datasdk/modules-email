<?php

namespace Modules\Email\Traits;

use PHPUnit\Framework\Assert;
use Modules\Email\Models\Email;

trait TestsEmailTrait
{
    /**
     * Assert that an email does not contain unresolved template markers.
     */
    public function assertEmailHasNoTemplateMarkers(Email $email): void
    {
        $subject = $email->subject;
        $message = $email->message;

        Assert::assertFalse(
            $email->hasTemplateMarkers(),
            "Email contains unresolved template parameters.\n\nSubject: {$subject}\n\message:\n{$message}"
        );
    }
}
