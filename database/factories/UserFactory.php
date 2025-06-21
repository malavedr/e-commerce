<?php

namespace Database\Factories;

use App\Models\DeliveryAddress;
use App\Models\User;
use App\Enums\UserStatusEnum;
use App\Models\UserContact;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Enums\ContactTypeEnum;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'phone' => $this->faker->phoneNumber(),
            'billing_name' => $this->faker->name(),
            'billing_tax_id' => $this->faker->unique()->numerify('###########'),
            'billing_address_line' => $this->faker->streetAddress(),
            'billing_province' => $this->faker->state(),
            'billing_locality' => $this->faker->city(),
            'billing_zipcode' => $this->faker->postcode(),
            'status' => $this->faker->randomElement(UserStatusEnum::all()),
            'remember_token' => Str::random(10),
        ];

    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function withActiveDeliveryAddresses(int $count = 1): static
    {
        return $this->has(
            DeliveryAddress::factory()->count($count)->state(['is_active' => true]),
            'deliveryAddresses'
        );
    }

    public function withInactiveDeliveryAddresses(int $count = 1): static
    {
        return $this->has(
            DeliveryAddress::factory()->count($count)->state(['is_active' => false]),
            'deliveryAddresses'
        );
    }

    public function withPhoneContact(int $count = 1): static
    {
        return $this->has(
            UserContact::factory()->count($count)->state([
                'type' => ContactTypeEnum::MOBILE->value,
            ]),
            'contacts'
        );
    }
}
