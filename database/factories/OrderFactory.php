<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\DeliveryAddress;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 1000, 100000);
        $discount = $this->faker->randomFloat(2, 0, 5000);
        $tax = $this->faker->randomFloat(2, 0, 10000);

        return [
            'buyer_id' => User::factory(),
            'delivery_address_id' => DeliveryAddress::factory(),

            'sub_total' => $subtotal,
            'discount_total' => $discount,
            'tax_total' => $tax,
            'total' => $subtotal - $discount + $tax,

            'status' => $this->faker->randomElement(OrderStatusEnum::all()),
            'payment_status' => $this->faker->randomElement(PaymentStatusEnum::all()),

            'shipped_at' => $this->faker->optional()->dateTimeBetween('-1 week', 'now'),
            'delivered_at' => $this->faker->optional()->dateTimeBetween('now', '+1 week'),
            'canceled_at' => null,
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}