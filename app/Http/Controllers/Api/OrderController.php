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
     */
    public function show(int $order_id, OrderRepositoryInterface $repository): OrderResource
    {
        $order = $repository->findOrFail($order_id);
        $this->authorize('view', $order);

        return new OrderResource($order);
    }
}
