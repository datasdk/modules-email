<?php

namespace Modules\Email\Database\factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Email\Models\Email;
use Modules\Email\Models\MailTemplates;
use Carbon\Carbon;

class EmailFactory extends Factory
{
    protected $model = Email::class;

    public function definition()
    {
        return [
            'to' => $this->faker->email,  // Generates a random email address
            'user_id' => User::factory(),  // Assuming a User factory exists to generate users
            'subject' => $this->faker->sentence,  // Random subject line
            'message' => $this->faker->paragraph,  // Random email body message
            'template_id' => MailTemplates::factory(),  // Assuming MailTemplates factory exists
            'status' => 'created',  // Random status
            'send_after' => Carbon::now()->addDays(rand(1, 10)),  // Random send_after time within the next 10 days
            'sent' => $this->faker->randomElement([null, Carbon::now()]),  // Random sent date or null
        ];
    }
}
