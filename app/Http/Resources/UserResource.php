<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 *
 * Transforms a User model into a JSON-serializable array
 * including user details such as name, email, phone, billing information,
 * status, and timestamps.
 *
 * @package App\Http\Resources
 */
class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'billing_name' => $this->billing_name,
            'billing_tax_id' => $this->billing_tax_id,
            'billing_address_line' => $this->billing_address_line,
            'billing_province' => $this->billing_province,
            'billing_locality' => $this->billing_locality,
            'billing_zipcode' => $this->billing_zipcode,
            'status' => $this->status,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
