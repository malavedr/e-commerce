<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Exceptions\OrderCreationFailedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\OrderService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Jobs\SendOrderCreatedEmail;

/**
 * Class OrderController
 *
 * Handles API requests related to order creation and retrieval.
 * Integrates with OrderService and OrderRepositoryInterface to manage order workflows.
 *
 * @package App\Http\Controllers\Api
 */
class OrderController extends Controller
{
    use HandlesApiResponses, AuthorizesRequests;

    /**
     * OrderController constructor.
     *
     * @param  \App\Services\OrderService  $service
     */
    public function __construct(
        protected OrderService $service
    ) {}

    /**
     * Store a new order in the system.
     *
     * Authorizes the request, prepares order data from validated input,
     * and delegates the creation process to the OrderService.
     * Rolls back and unlocks resources in case of failure.
     *
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \App\Exceptions\OrderCreationFailedException
     *
     * @OA\Post(
     *     path="/api/v1.0.0/orders",
     *     summary="Create a new order",
     *     tags={"Orders"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"products"},
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="sku", type="string", example="SKU-49005157"),
     *                     @OA\Property(property="quantity", type="integer", example=2)
     *                 )
     *             ),
     *             @OA\Property(property="notes", type="string", example="Please deliver in the morning.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Order created successfully."),
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=38),
     *                 @OA\Property(property="buyer", type="object",
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="name", type="string", example="Diego User Active"),
     *                     @OA\Property(property="email", type="string", example="diego.user.active@e-commerce.com"),
     *                     @OA\Property(property="phone", type="string", example="+1-660-653-6715"),
     *                     @OA\Property(property="billing_name", type="string", example="Gabrielle Hermann"),
     *                     @OA\Property(property="billing_tax_id", type="string", example="36019774668"),
     *                     @OA\Property(property="billing_address_line", type="string", example="299 Ruecker Keys"),
     *                     @OA\Property(property="billing_province", type="string", example="Indiana"),
     *                     @OA\Property(property="billing_locality", type="string", example="Port Myrtle"),
     *                     @OA\Property(property="billing_zipcode", type="string", example="00131"),
     *                     @OA\Property(property="status", type="string", example="active"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-23T13:06:36.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-23T13:06:36.000000Z")
     *                 ),
     *                 @OA\Property(property="delivery_address", type="object",
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="line", type="string", example="817 Aida Bypass Apt. 704"),
     *                     @OA\Property(property="locality", type="string", example="Port Wadetown"),
     *                     @OA\Property(property="province", type="string", example="Maryland"),
     *                     @OA\Property(property="zipcode", type="string", example="18663-4557")
     *                 ),
     *                 @OA\Property(property="sub_total", type="string", example="6306.73"),
     *                 @OA\Property(property="discount_total", type="string", example="0.00"),
     *                 @OA\Property(property="tax_total", type="string", example="0.00"),
     *                 @OA\Property(property="total", type="string", example="6306.73"),
     *                 @OA\Property(property="notes", type="string", nullable=true, example=null),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="payment_status", type="string", example="unpaid"),
     *                 @OA\Property(property="items", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="product", type="object",
     *                             @OA\Property(property="id", type="integer", example=44),
     *                             @OA\Property(property="name", type="string", example="quam eos dicta"),
     *                             @OA\Property(property="sku", type="string", example="SKU-49005157"),
     *                             @OA\Property(property="price", type="string", example="227.60"),
     *                             @OA\Property(property="description", type="string", example="Qui nobis recusandae beatae et maiores."),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-23T13:06:36+00:00"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-23T13:06:36+00:00")
     *                         ),
     *                         @OA\Property(property="quantity", type="integer", example=5),
     *                         @OA\Property(property="unit_price", type="string", example="227.60"),
     *                         @OA\Property(property="total_price", type="string", example="1138.00")
     *                     )
     *                 ),
     *                 @OA\Property(property="shipped_at", type="string", nullable=true, example=null),
     *                 @OA\Property(property="delivered_at", type="string", nullable=true, example=null),
     *                 @OA\Property(property="canceled_at", type="string", nullable=true, example=null),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-23T17:45:53.000000Z")
     *             )
     *         )
     *     ),
    * @OA\Response(
    *     response=422,
    *     description="Validation failed",
    *     @OA\JsonContent(
    *         oneOf={
    *             @OA\Schema(
    *                 example={
    *                     "message": "You have already placed an order with the same products.",
    *                     "errors": {
    *                         "products": {"You have already placed an order with the same products."}
    *                     }
    *                 }
    *             ),
    *             @OA\Schema(
    *                 example={
    *                     "message": "The SKU must be unique across all products.",
    *                     "errors": {
    *                         "products": {"The SKU must be unique across all products."}
    *                     }
    *                 }
    *             )
    *         }
    *     )
    * )
     * )
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $this->authorize('create', Order::class);

        $inputs = $request->validated();
        $products = collect($inputs['products'])->pluck('quantity', 'sku')->toArray();
        $user_id = $request->user()->id;
    
        $data = [
            'buyer_id' => $user_id,
            'notes' => $inputs['notes'] ?? null,
            'status' => OrderStatusEnum::PENDING->value,
            'payment_status' => PaymentStatusEnum::UNPAID->value,
            'products' => $products,
        ];

        try {
            DB::beginTransaction();

            $order = $this->service->placeOrder($data, $products);
            SendOrderCreatedEmail::dispatch($order)->delay(now()->addSeconds(60));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            $productSkus = collect($inputs['products'])->pluck('sku')->sort()->values()->toArray();
            $productIds = Product::whereIn('sku', $productSkus)->pluck('id')->sort()->values()->toArray();
            
            ksort($productIds);
            $key = "orders:lock:$user_id:" . md5(json_encode($productIds));
            Redis::del($key);

            throw new OrderCreationFailedException([
                'exception' => $e->getMessage(),
            ]);
        }

        return $this->created(__('order.created'), new OrderResource($order));
    }

    /**
     * Display the specified order.
     *
     * Authorizes the user to view the order and fetches it from the repository.
     *
     * @param  int  $order_id
     * @param  \App\Repositories\Contracts\OrderRepositoryInterface  $repository
     * @return \App\Http\Resources\OrderResource
     *
     * @OA\Get(
     *     path="/api/v1.0.0/orders/{order_id}",
     *     summary="Get a specific order by ID",
     *     tags={"Orders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="order_id",
     *         in="path",
     *         required=true,
     *         description="ID of the order to retrieve",
     *         @OA\Schema(type="integer", example=36)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=36),
     *                 @OA\Property(property="buyer", type="object",
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="name", type="string", example="Diego User Active"),
     *                     @OA\Property(property="email", type="string", example="diego.user.active@e-commerce.com"),
     *                     @OA\Property(property="phone", type="string", example="+1-660-653-6715"),
     *                     @OA\Property(property="billing_name", type="string", example="Gabrielle Hermann"),
     *                     @OA\Property(property="billing_tax_id", type="string", example="36019774668"),
     *                     @OA\Property(property="billing_address_line", type="string", example="299 Ruecker Keys"),
     *                     @OA\Property(property="billing_province", type="string", example="Indiana"),
     *                     @OA\Property(property="billing_locality", type="string", example="Port Myrtle"),
     *                     @OA\Property(property="billing_zipcode", type="string", example="00131"),
     *                     @OA\Property(property="status", type="string", example="active"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-23T13:06:36.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-23T13:06:36.000000Z")
     *                 ),
     *                 @OA\Property(property="delivery_address", type="object",
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="line", type="string", example="817 Aida Bypass Apt. 704"),
     *                     @OA\Property(property="locality", type="string", example="Port Wadetown"),
     *                     @OA\Property(property="province", type="string", example="Maryland"),
     *                     @OA\Property(property="zipcode", type="string", example="18663-4557")
     *                 ),
     *                 @OA\Property(property="sub_total", type="string", example="3865.34"),
     *                 @OA\Property(property="discount_total", type="string", example="0.00"),
     *                 @OA\Property(property="tax_total", type="string", example="0.00"),
     *                 @OA\Property(property="total", type="string", example="3865.34"),
     *                 @OA\Property(property="notes", type="string", nullable=true, example=null),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="payment_status", type="string", example="unpaid"),
     *                 @OA\Property(property="items", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="product", type="object",
     *                             @OA\Property(property="id", type="integer", example=44),
     *                             @OA\Property(property="name", type="string", example="quam eos dicta"),
     *                             @OA\Property(property="sku", type="string", example="SKU-49005157"),
     *                             @OA\Property(property="price", type="string", example="227.60"),
     *                             @OA\Property(property="description", type="string", nullable=true, example="Qui nobis recusandae beatae et maiores."),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-23T13:06:36+00:00"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-23T13:06:36+00:00")
     *                         ),
     *                         @OA\Property(property="quantity", type="integer", example=5),
     *                         @OA\Property(property="unit_price", type="string", example="227.60"),
     *                         @OA\Property(property="total_price", type="string", example="1138.00")
     *                     )
     *                 ),
     *                 @OA\Property(property="shipped_at", type="string", nullable=true, example=null),
     *                 @OA\Property(property="delivered_at", type="string", nullable=true, example=null),
     *                 @OA\Property(property="canceled_at", type="string", nullable=true, example=null),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-23T14:55:05.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="You must be authenticated to access this resource."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="The order does not exist or is not available."
     *     )
     * )
     */
    public function show(int $order_id, OrderRepositoryInterface $repository): OrderResource
    {
        $order = $repository->findOrFail($order_id);
        $this->authorize('view', $order);

        return new OrderResource($order);
    }
}
