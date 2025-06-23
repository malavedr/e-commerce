<?php

namespace App\Repositories;

use App\Exceptions\ApiException;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\Contracts\OrderRepositoryInterface;

/**
 * Class OrderRepository
 *
 * Repository implementation for handling order data operations.
 *
 * @package App\Repositories
 */
class OrderRepository implements OrderRepositoryInterface
{
    /**
     * Find an order by ID or throw an API exception if not found.
     *
     * Loads related models: items with products, delivery address, and buyer.
     *
     * @param  int  $order_id
     * @return \App\Models\Order
     *
     * @throws \App\Exceptions\ApiException
     */
    public function findOrFail(int $order_id): Order
    {
        return Order::where('id', $order_id)->with(['items.product', 'deliveryAddress', 'buyer'])->first() ?? 
            throw ApiException::fromKey('orders.not_found', 404, null, $order_id);
    }

    /**
     * Create a new order with associated order items.
     *
     * Iterates through the product data, fetches the latest price, and creates
     * the associated order items.
     *
     * @param  array  $data  Order base data.
     * @param  array  $products  List of items to associate with the order.
     * @return \App\Models\Order
     */
    public function createOrder(array $data, array $products): Order
    {
        $order = Order::create($data);

        foreach ($products as $productData) {
            $product = Product::findOrFail($productData['product_id']);
            $quantity = $productData['quantity'];
            $unitPrice = $product->price;

            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $quantity * $unitPrice,
            ]);
        }

        return $order->fresh(['items.product', 'deliveryAddress', 'buyer']);
    }

    /**
     * Load related data for the given order instance.
     *
     * Useful for returning a fully hydrated order with all relationships.
     *
     * @param  \App\Models\Order  $order
     * @return \App\Models\Order|null
     */
    public function findByIdWithRelations(Order $order): ?Order
    {
        return $order->load(['items.product', 'deliveryAddress', 'buyer']);
    }
}