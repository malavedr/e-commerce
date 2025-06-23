<?php

namespace App\Repositories\Contracts;

use App\Models\Order;

interface OrderRepositoryInterface
{
    /**
     * Find an order by its ID or fail.
     *
     * @param int $order_id
     * @return Order
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $order_id): Order;

    /**
     * Create a new order with associated products.
     *
     * @param array $data Basic order data.
     * @param array $products List of product details to attach to the order.
     * @return Order The created order instance.
     */
    public function createOrder(array $data, array $products): Order;

    /**
     * Find an order by ID and eager load its relations.
     *
     * @param Order $order The order instance to load relations on.
     * @return Order|null The order with loaded relations or null if not found.
     */
    public function findByIdWithRelations(Order $order): ?Order;
}
