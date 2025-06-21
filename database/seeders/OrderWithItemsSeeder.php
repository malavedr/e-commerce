<?php

namespace Database\Seeders;

use App\Enums\PaymentStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderWithItemsSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::has('deliveryAddresses')->get();
        $products_all = Product::active()->get();

        foreach ($users as $user) {
            $address = $user->deliveryAddresses()->active()->inRandomOrder()->first();

            $order = Order::create([
                'buyer_id' => $user->id,
                'delivery_address_id' => $address->id,
                'sub_total' => 0,
                'discount_total' => 0,
                'tax_total' => 0,
                'total' => 0,
                'status' => OrderStatusEnum::PENDING->value,
                'payment_status' => PaymentStatusEnum::UNPAID->value,
            ]);

            $sub_total = 0;
            $products = $products_all->random(rand(2, 5));

            foreach ($products as $product) {
                $quantity = rand(1, 3);
                $unit_price = $product->price;
                $total_price = $quantity * $unit_price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unit_price,
                    'total_price' => $total_price,
                ]);

                $sub_total += $total_price;
            }

            $discount = round($sub_total * 0.05, 2);
            $tax = round(($sub_total - $discount) * 0.21, 2);
            $total = $sub_total - $discount + $tax;

            $order->update([
                'sub_total' => $sub_total,
                'discount_total' => $discount,
                'tax_total' => $tax,
                'total' => $total,
            ]);
        }
    }
}
