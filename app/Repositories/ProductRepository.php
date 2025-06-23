<?php

namespace App\Repositories;

use App\Exceptions\ApiException;
use App\Exceptions\ProductCreationFailedException;
use App\Exceptions\ProductDeletionFailedException;
use App\Exceptions\ProductUpdateFailedException;
use App\Exceptions\ProductWithOrdersException;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class ProductRepository
 *
 * Concrete implementation of the ProductRepositoryInterface.
 * Provides methods for interacting with the Product model, including
 * create, update, delete, and pagination of active records.
 * Handles domain-specific exceptions for better API response management.
 *
 * @package App\Repositories
 */
class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Paginate all active products.
     *
     * @param  int  $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateActive(int $perPage = 15): LengthAwarePaginator
    {
        return Product::active()->paginate($perPage);
    }

    /**
     * Find a product by SKU or throw a not found API exception.
     *
     * @param  string  $sku
     * @return \App\Models\Product
     *
     * @throws \App\Exceptions\ApiException
     */
    public function findOrFail($sku): Product
    {
        return Product::where('sku', $sku)->first() ?? throw ApiException::fromKey('products.not_found', 404);
    }

    /**
     * Create a new product using the given data.
     *
     * @param  array  $data
     * @return \App\Models\Product
     *
     * @throws \App\Exceptions\ProductCreationFailedException
     */
    public function create(array $data): Product
    {
        try {
            return Product::create($data);
        } catch (\Throwable $e) {
            throw new ProductCreationFailedException([
                'exception' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update the given product with new data.
     *
     * @param  \App\Models\Product  $product
     * @param  array  $data
     * @return \App\Models\Product
     *
     * @throws \App\Exceptions\ProductUpdateFailedException
     */
    public function update(Product $product, array $data): Product
    {
        try {
            $product->update($data);
            return $product->refresh();
        } catch (\Throwable $e) {
            throw new ProductUpdateFailedException($product->id, [
                'exception' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete the given product if it has no associated order items.
     *
     * @param  \App\Models\Product  $product
     * @return void
     *
     * @throws \App\Exceptions\ProductWithOrdersException
     * @throws \App\Exceptions\ProductDeletionFailedException
     */
    public function delete(Product $product): void
    {
        if ($product->items()->exists()) {
            throw new ProductWithOrdersException($product->id);
        }

        try {
            $product->delete();
        } catch (\Throwable $e) {
            throw new ProductDeletionFailedException($product->id, [
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
