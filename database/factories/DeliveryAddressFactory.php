<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryAddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'recipient_name' => $this->faker->name(),
            'address_line' => $this->faker->streetAddress(),
            'province' => $this->faker->state(),
            'locality' => $this->faker->city(),
            'zipcode' => $this->faker->postcode(),
            'is_active' => $this->faker->boolean(90),
        ];
    }

    /**
     * Indicate that the address is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the address is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}