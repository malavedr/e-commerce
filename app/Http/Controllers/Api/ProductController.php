<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use App\Traits\HandlesApiResponses;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use HandlesApiResponses, AuthorizesRequests;

    public function index(Request $request, ProductRepositoryInterface $repository)
    {
        $this->authorize('viewAny', Product::class);

        $perPage = $request->input('per_page', 15);
        $products = $repository->paginateActive($perPage);

        return ProductResource::collection($products);
    }

    public function show(string $id, ProductRepositoryInterface $repository)
    {
        $product = $repository->findOrFail($id);
        $this->authorize('view', $product);

        return new ProductResource($product);
    }

    public function store(ProductStoreRequest $request, ProductRepositoryInterface $repository)
    {
        $this->authorize('create', Product::class);
        $product = $repository->create($request->validated());

        return $this->success(__('product.action.created'), new ProductResource($product));
    }

    public function update(ProductUpdateRequest $request, $id, ProductRepositoryInterface $repository)
    {
        $product = $repository->findOrFail($id);
        $this->authorize('update', $product);
        
        $updated = $repository->update($product, $request->validated());

        return $this->success(__('product.action.updated'), new ProductResource($updated));
    }

    public function destroy($id, ProductRepositoryInterface $repository)
    {
        $product = $repository->findOrFail($id);
        $this->authorize('delete', $product);

        $repository->delete($product);

        return $this->success(__('product.action.deleted'));
    }
}
