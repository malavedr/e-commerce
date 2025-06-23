<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'buyer_id' => $this->buyer_id,
            'delivery_address' => new DeliveryAddressResource($this->whenLoaded('deliveryAddress')),
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'sub_total' => $this->sub_total,
            'discount_total' => $this->discount_total,
            'tax_total' => $this->tax_total,
            'total' => $this->total,
            'notes' => $this->notes,
            'shipped_at' => $this->shipped_at,
            'delivered_at' => $this->delivered_at,
            'canceled_at' => $this->canceled_at,
            'created_at' => $this->created_at,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}