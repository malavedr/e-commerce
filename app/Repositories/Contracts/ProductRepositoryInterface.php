<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface ProductRepositoryInterface
 *
 * Defines the contract for interacting with Product data.
 * Includes methods for creating, updating, deleting,
 * and retrieving active products with pagination.
 *
 * @package App\Repositories\Contracts
 */
interface ProductRepositoryInterface
{
    /**
     * Get a paginated list of active products.
     *
     * @param  int  $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateActive(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a product by ID or throw an exception if not found.
     *
     * @param  string  $sku
     * @return \App\Models\Product
     */
    public function findOrFail(string $sku): Product;

    /**
     * Create a new product.
     *
     * @param  array  $data
     * @return \App\Models\Product
     */
    public function create(array $data): Product;

    /**
     * Update the given product with new data.
     *
     * @param  \App\Models\Product  $product
     * @param  array  $data
     * @return \App\Models\Product
     */
    public function update(Product $product, array $data): Product;

    /**
     * Delete the specified product.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function delete(Product $product): void;
}
