<?php

namespace Modules\Email\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Email\Models\EmailCampaigne;
use Modules\Email\Models\MailTemplates;

class EmailCampaigneFactory extends Factory
{
    protected $model = EmailCampaigne::class;

    public function definition()
    {
        return [
            'template_id' => MailTemplates::factory(),
            'send_day' => $this->faker->numberBetween(1, 30),
            'send_time' => $this->faker->time,
        ];
    }
}
