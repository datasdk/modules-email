<?php

namespace Modules\Email\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailSMTPRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $provider = $this->input('default');

        $rules = [
            'default' => 'required|string|in:smtp,sendgrid',
        ];

        if ($provider === 'smtp') {
            $rules = array_merge($rules, [
                'smtp.active' => 'sometimes|boolean',
                'smtp.host' => 'required|string',
                'smtp.port' => 'required|integer|min:4',
                'smtp.username' => 'required|string',
                'smtp.password' => 'required|string',
                'smtp.encryption' => 'required|string',
                'from.name' => 'required|string',
                'from.address' => 'required|email',
                'from.reply_address' => 'required|email',
            ]);
        } elseif ($provider === 'sendgrid') {
            $rules = array_merge($rules, [
                'sendgrid.api' => 'required|string',
                'from.name' => 'required|string',
                'from.address' => 'required|email',
                'from.reply_address' => 'sometimes|email',
            ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'default.required' => 'Vælg venligst en provider.',
            'default.in' => 'Provider skal være enten smtp eller sendgrid.',
            'smtp.host.required' => 'SMTP host er påkrævet.',
            'smtp.port.required' => 'SMTP port er påkrævet.',
            'smtp.username.required' => 'SMTP username er påkrævet.',
            'smtp.password.required' => 'SMTP password er påkrævet.',
            'smtp.encryption.required' => 'SMTP encryption er påkrævet.',
            'from.name.required' => 'From name er påkrævet.',
            'from.address.required' => 'From email er påkrævet.',
            'from.address.email' => 'From email skal være gyldig.',
            'from.reply_address.required' => 'Reply email er påkrævet.',
            'from.reply_address.email' => 'Reply email skal være gyldig.',
            'sendgrid.api.required' => 'SendGrid API key er påkrævet.',
        ];
    }
}
