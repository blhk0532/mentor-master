<?php

declare(strict_types=1);

namespace Adultdate\FilamentUser\Database\Factories;

use Adultdate\FilamentUser\Models\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserTypeFactory extends Factory
{
    protected $model = UserType::class;

    public function definition()
    {
        return [
            'slug' => $this->faker->unique()->word(),
            'label' => $this->faker->words(2, true),
        ];
    }
}
