<?php

namespace Modules\Email\Traits;

use Modules\Email\Models\MailModel;
use Modules\Email\Models\Email;
use Modules\Email\Models\MailTemplates;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Carbon\Carbon;


trait HasEmails
{
    public function mailModels(): MorphMany
    {
        return $this->morphMany(MailModel::class, 'model');
    }

    public function emails()
    {
        return $this->hasManyThrough(
            Email::class,
            MailModel::class,
            'model_id',   // mail_models.model_id
            'id',         // emails.id
            'id',         // lokal id (modellen)
            'email_id'    // mail_models.email_id
        )->where('mail_models.model_type', get_class($this));
    }

    public function hasReceivedEmail(int $emailId): bool
    {
        return $this->mailModels()->where('email_id', $emailId)->exists();
    }

    public function purgeEmails(): void
    {
        $this->mailModels()->delete();
    }

    /**
     * Check if a specific template has been sent (by template id or name)
     */
    public function hasSentEmail(string|int $template,?Carbon $date = null): bool
    {
        return $this->mailModels()
            ->whereHas('email.template', function ($q) use ($template) {

                if (is_int($template)) {

                    $q->where('id', $template);

                } else {

                    $q->where('name', $template);

                }

            })->when($date, function ($q) use ($date) {
                
                $q->whereDate('created_at', $date);

            })
            ->exists();
    }

    /**
     * Count how many times a specific template has been sent (by template id or name)
     */
    public function sentEmailCount(string|int $template): int
    {
        return $this->mailModels()
            ->whereHas('email.template', function ($query) use ($template) {
                if (is_int($template)) {
                    $query->where('id', $template);
                } else {
                    $query->where('name', $template);
                }
            })->count();
    }
}
