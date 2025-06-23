<?php

namespace App\Repositories\Contracts;

use App\Models\Order;

interface OrderRepositoryInterface
{
    public function createOrder(array $data, array $products): Order;
    public function findByIdWithRelations(int|string $id): ?Order;
}