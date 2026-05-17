<?php

namespace Modules\Email\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Email\Models\SendGrid;
use Modules\Email\Models\Email;

class SendGridFactory extends Factory
{
    protected $model = SendGrid::class;

    public function definition()
    {
        return [
            'email_id' => Email::factory(),
        ];
    }
}
