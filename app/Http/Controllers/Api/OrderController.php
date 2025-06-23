<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Support\ApiResponder;
use App\Services\OrderService;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use HandlesApiResponses, AuthorizesRequests;

    public function __construct(
        protected OrderService $service
    ) {}

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $this->authorize('create', Order::class);

        $inputs = $request->validated();
        $products = collect($inputs['products'])->pluck('quantity', 'sku')->toArray();
        
        // dump($products);
        // return;

        $data = [
            'buyer_id' => $request->user()->id,
            'notes' => $inputs['notes'] ?? null,
            'status' => OrderStatusEnum::PENDING->value,
            'payment_status' => PaymentStatusEnum::UNPAID->value,
            'products' => $products,
        ];

        // try {
            DB::beginTransaction();
            $order = $this->service->placeOrder($data, $products);
            DB::commit();
        // } catch (\Throwable $e) {
        //     DB::rollBack();
        //     return ApiResponder::error('order.failed', ['exception' => $e->getMessage()], 500);
        // }

        return $this->created(__('order.created'), new OrderResource($order));
    }

    public function show(Order $order)
    {
        // $this->authorize('view', $order);

        $order = $this->service->orderDetails($order);
        // dump($order);

        // if (! $order) {
        //     return ApiResponder::notFound('order.not_found');
        // }

        return new OrderResource($order);
    }


}

