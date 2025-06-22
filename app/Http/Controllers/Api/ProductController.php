<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Contracts\ProductRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Responses\HandlesApiResponses;

class ProductController extends Controller
{
    use HandlesApiResponses;

    public function index(Request $request, ProductRepositoryInterface $repository)
    {
        $perPage = $request->input('per_page', 15);
        $products = $repository->paginateActive($perPage);

        return ProductResource::collection($products);
    }

    public function store(ProductStoreRequest $request, ProductRepositoryInterface $repository)
    {
        $product = $repository->create($request->validated());

        return $this->success(__('product.action.created'), new ProductResource($product));
    }

    public function show(string $id, ProductRepositoryInterface $repository)
    {
        $product = $repository->findOrFail($id);

        return new ProductResource($product);
    }

    public function update(ProductUpdateRequest $request, int $id, ProductRepositoryInterface $repository)
    {
        $product = $repository->findOrFail($id);
        $updated = $repository->update($product, $request->validated());

        return $this->success(__('product.action.updated'), new ProductResource($updated));
    }

    public function destroy(int $id, ProductRepositoryInterface $repository)
    {
        $product = $repository->findOrFail($id);
        $repository->delete($product);

        return $this->success(__('product.action.deleted'));
    }
}
