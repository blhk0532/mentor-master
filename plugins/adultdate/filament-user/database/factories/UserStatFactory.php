<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Database\Factories;

use Adultdate\FilamentUser\Models\UserStat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserStatFactory extends Factory
{
    protected $model = UserStat::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'key' => 'profile_views',
            'value' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
