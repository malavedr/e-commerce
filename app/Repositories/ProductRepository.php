<?php

namespace App\Repositories;

use App\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use App\Exceptions\ApiException;
use App\Exceptions\ProductCreationFailedException;
use App\Exceptions\ProductUpdateFailedException;
use App\Exceptions\ProductDeletionFailedException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function paginateActive(int $perPage = 15): LengthAwarePaginator
    {
        return Product::active()->paginate($perPage);
    }

    public function findOrFail(int $id): Product
    {
        return Product::find($id) ?? throw ApiException::fromKey('products.not_found', 404);
    }

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

    public function update(Product $product, array $data): Product
    {
        try {
            $product->update($data);
            return $product->refresh();
        } catch (\Throwable $e) {
            throw new ProductUpdateFailedException([
                'exception' => $e->getMessage(),
            ]);
        }
    }

    public function delete(Product $product): void
    {
        try {
            $product->delete();
        } catch (\Throwable $e) {
            throw new ProductDeletionFailedException([
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
