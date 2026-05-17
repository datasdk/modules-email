<?php

namespace Modules\Email\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Email\Models\UserEmailSetting;
use App\Models\User;
use Modules\Email\Models\MailTemplates;

class UserEmailSettingFactory extends Factory
{
    protected $model = UserEmailSetting::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'template_id' => MailTemplates::factory(),
            'active' => $this->faker->boolean,
        ];
    }
}
