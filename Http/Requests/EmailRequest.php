<?php

namespace Modules\Email\Http\Requests;

use Orion\Http\Requests\Request;
use Modules\Crm\Rules\StorageFileExists;
use Modules\Media\Rules\FileRule;


class EmailRequest extends Request
{
    public function storeRules(): array
    {

        $id = $this->route("email");

        return [
            "to" => "required_without:user_id|string|email",
            "user_id" => "required_without:to|exists:users,id",
            "subject" => "required_without:template_id|string",
            "message" => "required_without:template_id|string",
            "template_id" => "sometimes|nullable|exists:mail_templates,id",
            "attachments" => "sometimes|array",
            "attachments.*" => ["sometimes", "nullable", new FileRule],
            "send_after" => "sometimes|nullable|date|after_or_equal:-5 minutes",
            "ignore" => "sometimes|array",
            "ignore.*" => "string",
        ];
    }

    public function updateRules(): array
    {
        

        return [
            "to" => "sometimes|required_without:user_id|string|email",
            "user_id" => "sometimes|required_without:to|exists:users,id",
            "subject" => "sometimes|string",
            "message" => "sometimes|string",
            "template_id" => "sometimes|nullable|exists:mail_templates,id",
            "attachments" => "sometimes|array",
            "attachments.*" => ["sometimes", "nullable",  new FileRule],
            "send_after" => "sometimes|nullable|date|after_or_equal:-5 minutes",
            "ignore" => "sometimes|array",
            "ignore.*" => "string",
        ];
    }

    public function messages(): array
    {
        return [
            'to.required' => 'The recipient email is required.',
            'to.email' => 'The recipient email must be a valid email address.',
            'subject.required_without' => 'The subject is required when template ID is not provided.',
            'message.required_without' => 'The message is required when template ID is not provided.',
            'template_id.exists' => 'The selected template ID is invalid.',
            'attachments.required' => 'Attachments must be an array if provided.',
            'attachments.*.string' => 'Each attachment must be a valid file path.',
            'send_after.date_format' => 'The send after field must be a valid date (Y-m-d H:i).',
            'send_after.after_or_equal' => 'The send after date must be in the future.',
        ];
    }
}
