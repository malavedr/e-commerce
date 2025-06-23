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
     *
     * @OA\Get(
     *     path="/api/v1.0.0/products",
     *     summary="List active products (paginated)",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Number of products per page",
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of active products",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="ssd"),
     *                     @OA\Property(property="sku", type="string", example="SKU-22276"),
     *                     @OA\Property(property="price", type="string", example="333.00"),
     *                     @OA\Property(property="description", type="string", nullable=true, example="11111111111111111"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-23T13:06:36+00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-23T13:42:33+00:00")
     *                 )
     *             ),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", example="http://e-commerce.test/api/v1.0.0/products?page=1"),
     *                 @OA\Property(property="last", type="string", example="http://e-commerce.test/api/v1.0.0/products?page=4"),
     *                 @OA\Property(property="prev", type="string", nullable=true, example=null),
     *                 @OA\Property(property="next", type="string", nullable=true, example="http://e-commerce.test/api/v1.0.0/products?page=2")
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=4),
     *                 @OA\Property(property="path", type="string", example="http://e-commerce.test/api/v1.0.0/products"),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="to", type="integer", example=15),
     *                 @OA\Property(property="total", type="integer", example=52)
     *             )
     *         )
     *     )
     * )
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
     *
     * @OA\Get(
     *     path="/api/v1.0.0/products/{sku}",
     *     summary="Get product details by SKU",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="sku",
     *         in="path",
     *         required=true,
     *         description="Product SKU",
     *         @OA\Schema(type="string", example="SKU-2227665")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=77),
     *                 @OA\Property(property="name", type="string", example="ssd"),
     *                 @OA\Property(property="sku", type="string", example="SKU-2227665"),
     *                 @OA\Property(property="price", type="string", example="333.00"),
     *                 @OA\Property(property="description", type="string", example="11111111111111111"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-23T13:39:20+00:00"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-23T13:53:41+00:00")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="The requested product does not exist"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="You must be authenticated to access this resource."
     *     )
     * )
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
     *
     * @OA\Post(
     *     path="/api/v1.0.0/products",
     *     summary="Create a new product",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "sku", "price"},
     *             @OA\Property(property="name", type="string", example="product 44"),
     *             @OA\Property(property="sku", type="string", example="SKU-345345"),
     *             @OA\Property(property="price", type="string", example="333.23"),
     *             @OA\Property(property="description", type="string", nullable=true, example="adl sdh sdfh sdfuk sd k dfkL SDF  SDFKH SDKFD")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The product has been successfully created."),
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=79),
     *                 @OA\Property(property="name", type="string", example="product 44"),
     *                 @OA\Property(property="sku", type="string", example="SKU-345345"),
     *                 @OA\Property(property="price", type="string", example="333.23"),
     *                 @OA\Property(property="description", type="string", example="adl sdh sdfh sdfuk sd k dfkL SDF  SDFKH SDKFD"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-23T18:14:44+00:00"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-23T18:14:44+00:00")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="The sku has already been taken."
     *     )
     * )
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
     *
     * @OA\Put(
     *     path="/api/v1.0.0/products/{sku}",
     *     summary="Update a product by SKU",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="sku",
     *         in="path",
     *         required=true,
     *         description="SKU of the product to update",
     *         @OA\Schema(type="string", example="SKU-2227665D")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "sku", "price"},
     *             @OA\Property(property="name", type="string", example="ssd"),
     *             @OA\Property(property="sku", type="string", example="SKU-2227665D"),
     *             @OA\Property(property="price", type="string", example="333.00"),
     *             @OA\Property(property="description", type="string", nullable=true, example="Test")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The product has been successfully updated."),
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=77),
     *                 @OA\Property(property="name", type="string", example="ssd"),
     *                 @OA\Property(property="sku", type="string", example="SKU-2227665D"),
     *                 @OA\Property(property="price", type="string", example="333.00"),
     *                 @OA\Property(property="description", type="string", example="Test"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-23T13:39:20+00:00"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-23T18:18:33+00:00")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="The sku has already been taken."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="The requested product does not exist."
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="You must be authenticated to access this resource."
     *     )
     * )
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
     *
     * @OA\Delete(
     *     path="/api/v1.0.0/products/{sku}",
     *     summary="Delete a product by SKU",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="sku",
     *         in="path",
     *         required=true,
     *         description="SKU of the product to delete",
     *         @OA\Schema(type="string", example="SKU-345345")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The product has been successfully deleted."),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="You must be authenticated to access this resource."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="The requested product does not exist."
     *     )
     * )
     */
    public function destroy(string $sku, ProductRepositoryInterface $repository)
    {
        $product = $repository->findOrFail($sku);
        $this->authorize('delete', $product);

        $repository->delete($product);

        return $this->success(__('product.action.deleted'));
    }
}
