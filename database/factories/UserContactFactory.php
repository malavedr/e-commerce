<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\ContactTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserContact>
 */
class UserContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first(),
            'type' => ContactTypeEnum::MOBILE->value,
            'value' => $this->faker->phoneNumber(),
        ];
    }
}
