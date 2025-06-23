<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class DeliveryAddressResource
 *
 * Transforms a DeliveryAddress model into a JSON-serializable array
 * with selected address fields for API responses.
 *
 * @package App\Http\Resources
 */
class DeliveryAddressResource extends JsonResource
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
            'line' => $this->address_line,
            'locality' => $this->locality,
            'province' => $this->province,
            'zipcode' => $this->zipcode,
        ];
    }
}
