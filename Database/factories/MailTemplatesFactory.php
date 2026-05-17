<?php

namespace Modules\Email\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Email\Models\MailTemplates;
use Modules\Email\Models\MailTemplateParam;

class MailTemplatesFactory extends Factory
{
    protected $model = MailTemplates::class;

    public function definition()
    {
        return [
            'subject' => $this->faker->sentence,  // Random subject for the email template
            'html_template' => $this->faker->paragraph,  // Random HTML template body
            'text_template' => $this->faker->paragraph,  // Random text version of the template
            'params' => [],  // You can generate parameters if needed, or leave it empty for now
        ];
    }

    /**
     * Configure the factory for adding parameters to the template
     */
    public function withParams($count = 1)
    {
        return $this->has(MailTemplateParam::factory()->count($count), 'params');
    }
}
