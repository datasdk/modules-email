<?php

namespace Modules\Email\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Email\Models\EmailPlaceholders;
use Modules\Email\Models\MailTemplates;

class EmailPlaceholdersFactory extends Factory
{
    protected $model = EmailPlaceholders::class;

    public function definition()
    {
        return [
            'mail_template_id' => MailTemplates::factory(),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'meta' => $this->faker->json,
        ];
    }
}
