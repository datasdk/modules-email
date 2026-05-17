<?php

namespace Modules\Email\Contracts\Interfaces;

interface HasEmailsInterface
{
    /**
     * Polymorphic relation to MailModel
     *
     * @return mixed
     */
    public function mailModels();

    /**
     * Get all emails associated with this model
     *
     * @return mixed
     */
    public function emails();

    /**
     * Check if this model has received a specific email by ID
     *
     * @param int $emailId
     * @return bool
     */
    public function hasReceivedEmail(int $emailId);

    /**
     * Purge all emails for this model
     *
     * @return void
     */
    public function purgeEmails();

    /**
     * Check if a specific template has been sent (by template id or name)
     *
     * @param int|string $template
     * @return bool
     */
    public function hasSentEmail(string|int $template);

    /**
     * Count how many times a specific template has been sent (by template id or name)
     *
     * @param int|string $template
     * @return int
     */
    public function sentEmailCount(string|int $template);
}
