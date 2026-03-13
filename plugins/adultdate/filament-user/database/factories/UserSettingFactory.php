<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Database\Factories;

use Adultdate\FilamentUser\Models\UserSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSettingFactory extends Factory
{
    protected $model = UserSetting::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'key' => 'notification_email',
            'value' => $this->faker->boolean() ? '1' : '0',
        ];
    }
}
