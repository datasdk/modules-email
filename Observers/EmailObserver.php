<?php

namespace Modules\Email\Observers;

use Modules\Email\Models\Email;
use Modules\Email\Models\MailTemplateParam;
use Illuminate\Support\Facades\Log;

class EmailObserver
{

    
    public function creating(Email $email): void
    {

        $this->logTemplateWarnings($email);

        $this->updateTemplateParams($email);

    }


    public function deleting(Email $email): void
    {

        $this->deleteEmailAttachments($email);

    }


    /**
     * Log hvis email indeholder {{ markers }}
     */
    protected function logTemplateWarnings(Email $email): void
    {

        if ($email->hasTemplateMarkers()) {

            Log::warning('EmailObserver: Email subject or message contains template placeholders {{ or }}.', [
                'email_id'      => $email->id ?? null,
                'email_subject' => $email->subject,
                'message'       => $email->message ?? null,
            ]);

        }

    }


    /**
     * Opdater MailTemplateParams baseret på email->params
     */
    protected function updateTemplateParams(Email $email): void
    {

        if (!$email->template_id) {
            return;
        }


        MailTemplateParam::updateFromEmailParams(
            $email->template_id,
            $email->params ?? []
        );

    }


    /**
     * Slet attachments sikkert
     */
    protected function deleteEmailAttachments(Email $email): void
    {

        try {

            if ($email->hasMedia('attachments')) {

                $email->clearMediaCollection('attachments');

            }

        } catch (\Throwable $e) {

            Log::error('Failed to delete attachments for email ID ' . $email->id, [
                'error' => $e->getMessage(),
            ]);

        }

    }

}
