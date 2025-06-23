<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait LogsApiExceptions
{
    public function report(): void
    {
        Log::channel('api_exceptions')->warning($this->getMessage(), [
            'status' => property_exists($this, 'status') ? $this->status : $this->getCode(),
            'errors' => property_exists($this, 'errors') ? $this->errors : null,
            'entity_id' => property_exists($this, 'entity_id') ? $this->entity_id : null,
        ]);
    }
}
