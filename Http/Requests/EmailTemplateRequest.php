<?php

namespace Modules\Email\Http\Requests;

use Orion\Http\Requests\Request;
use Modules\Crm\Rules\StorageFileExists;

class EmailTemplateRequest extends Request
{
    /**
     * Validation rules for creating a new Email Template
     */
    public function storeRules(): array
    {
        return [
            "subject"        => "required",
            "html_template"  => "required",
            "text_template"  => "sometimes|nullable",

            "attachments"     => "sometimes|nullable|array",
            "attachments.*"   => ["sometimes", "nullable", "string", new StorageFileExists()],

            "categories"      => "sometimes|array",
            "categories.*"    => "integer|exists:email_categories,id",

            "active"      => "sometimes"
        ];
    }

    /**
     * Validation rules for updating an Email Template
     */
    public function updateRules(): array
    {
        return [
            "subject"        => "sometimes",
            "html_template"  => "sometimes",
            "text_template"  => "sometimes|nullable",

            "attachments"     => "sometimes|nullable|array",
            "attachments.*"   => ["sometimes", "nullable", "string", new StorageFileExists()],

            "categories"      => "sometimes|array",
            "categories.*"    => "integer|exists:email_categories,id",

            "active"      => "sometimes"
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            "subject.required"        => "Subject is required.",
            "html_template.required"  => "HTML template is required.",

            "subject.array"           => "Subject must be an array of translations.",
            "html_template.array"     => "HTML template must be an array of translations.",
            "text_template.array"     => "Text template must be an array of translations.",

            "attachments.array"       => "Attachments must be an array.",
            "attachments.*.string"    => "Each attachment must be a valid storage file path.",

            "categories.array"        => "Categories must be an array of category IDs.",
            "categories.*.exists"     => "One or more selected categories do not exist.",
        ];
    }
}
