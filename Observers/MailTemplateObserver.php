<?php

namespace Modules\Email\Observers;

use Modules\Email\Models\MailTemplates;
use DataSDK\Tools\Helpers\HtmlHelper;


class MailTemplateObserver
{

    
    public function creating(MailTemplates $mailTemplate)
    {

        $this->sanitizeHtml($mailTemplate);

    }


    public function updating(MailTemplates $mailTemplate)
    {

        $this->sanitizeHtml($mailTemplate);

    }


    protected function sanitizeHtml(MailTemplates $mailTemplate)
    {

        foreach (['html_template', 'text_template'] as $field) {

            if (isset($mailTemplate->$field)) {

                $mailTemplate->$field = HtmlHelper::cleanHtml($mailTemplate->$field);

            }

        }

    }

}
