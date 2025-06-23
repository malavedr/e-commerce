<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
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
