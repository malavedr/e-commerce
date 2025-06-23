<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use App\Traits\HandlesApiResponses;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

/**
 * Class ProductController
 *
 * Handles CRUD operations for products via API.
 * Utilizes the repository pattern for data access and applies
 * authorization policies and structured API responses.
 *
 * @package App\Http\Controllers\Api
 */
class ProductController extends Controller
{
    use HandlesApiResponses, AuthorizesRequests;

    /**
     * Display a paginated list of active products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Repositories\Contracts\ProductRepositoryInterface  $repository
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, ProductRepositoryInterface $repository)
    {
        $this->authorize('viewAny', Product::class);

        $perPage = $request->input('per_page', 15);
        $products = $repository->paginateActive($perPage);

        return ProductResource::collection($products);
    }

    /**
     * Show the details of a specific product.
     *
     * @param  string  $sku
     * @param  \App\Repositories\Contracts\ProductRepositoryInterface  $repository
     * @return \App\Http\Resources\ProductResource
     */
    public function show(string $sku, ProductRepositoryInterface $repository)
    {
        $product = $repository->findOrFail($sku);
        $this->authorize('view', $product);

        return new ProductResource($product);
    }

    /**
     * Store a newly created product in the database.
     *
     * @param  \App\Http\Requests\ProductStoreRequest  $request
     * @param  \App\Repositories\Contracts\ProductRepositoryInterface  $repository
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductStoreRequest $request, ProductRepositoryInterface $repository)
    {
        $this->authorize('create', Product::class);
        $product = $repository->create($request->validated());

        return $this->success(__('product.action.created'), new ProductResource($product));
    }

    /**
     * Update the specified product in the database.
     *
     * @param  \App\Http\Requests\ProductUpdateRequest  $request
     * @param  string  $sku
     * @param  \App\Repositories\Contracts\ProductRepositoryInterface  $repository
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductUpdateRequest $request,string $sku, ProductRepositoryInterface $repository)
    {
        $product = $repository->findOrFail($sku);
        $this->authorize('update', $product);
        
        $updated = $repository->update($product, $request->validated());

        return $this->success(__('product.action.updated'), new ProductResource($updated));
    }

    /**
     * Remove the specified product from the database.
     *
     * @param  string  $sku
     * @param  \App\Repositories\Contracts\ProductRepositoryInterface  $repository
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $sku, ProductRepositoryInterface $repository)
    {
        $product = $repository->findOrFail($sku);
        $this->authorize('delete', $product);

        $repository->delete($product);

        return $this->success(__('product.action.deleted'));
    }
}
