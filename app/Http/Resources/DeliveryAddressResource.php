<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryAddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'line' => $this->address_line,
            'locality' => $this->locality,
            'province' => $this->province,
            'zipcode' => $this->zipcode,
        ];
    }
}