<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

/**
 * Trait LogsApiExceptions
 *
 * Provides a standardized way to log API exceptions to a dedicated log channel.
 */
trait LogsApiExceptions
{
    /**
     * Report the exception by logging its message and related properties.
     *
     * Logs a warning-level message to the 'api_exceptions' channel with
     * details such as status code, errors array, and related entity ID if present.
     *
     * @return void
     */
    public function report(): void
    {
        Log::channel('api_exceptions')->warning($this->getMessage(), [
            'status' => property_exists($this, 'status') ? $this->status : $this->getCode(),
            'errors' => property_exists($this, 'errors') ? $this->errors : null,
            'entity_id' => property_exists($this, 'entity_id') ? $this->entity_id : null,
        ]);
    }
}
