<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderResource
 *
 * Transforms an Order model into a JSON-serializable array
 * including related user, delivery address, order items,
 * and pricing and status details.
 *
 * @package App\Http\Resources
 */
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request The incoming request instance.
     * @return array The transformed data as an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'buyer' => new UserResource($this->whenLoaded('buyer')),
            'delivery_address' => new DeliveryAddressResource($this->whenLoaded('deliveryAddress')),
            'sub_total' => $this->sub_total,
            'discount_total' => $this->discount_total,
            'tax_total' => $this->tax_total,
            'total' => $this->total,
            'notes' => $this->notes,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'shipped_at' => $this->shipped_at,
            'delivered_at' => $this->delivered_at,
            'canceled_at' => $this->canceled_at,
            'created_at' => $this->created_at,
        ];
    }
}
