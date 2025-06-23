<?php

namespace App\Exceptions;

use App\Traits\LogsApiExceptions;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Class ApiException
 *
 * A generic exception class for API-related errors.
 * Provides structured error responses and supports custom status codes, error details,
 * and optional entity identifiers for tracking the error context.
 *
 * @package App\Exceptions
 */
class ApiException extends Exception
{
    use LogsApiExceptions;

    public int $status;
    public int $entity_id;
    public array $errors;

    /**
     * ApiException constructor.
     *
     * @param string $message Error message.
     * @param int $status HTTP status code (default: 500).
     * @param array|null $errors Optional array of error details.
     * @param int|null $entity_id Optional related entity ID.
     */
    public function __construct(string $message = '', int $status = 500, ?array $errors = null, ?int $entity_id = 0) 
    {
        $this->status = $status;
        $this->errors = $errors ?? [];
        $this->entity_id = $entity_id;

        parent::__construct($message, $status);
    }

    /**
     * Render the exception as a JSON response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request): JsonResponse
    {
        $attributes = [
            'message' => $this->getMessage(),
            'status' => $this->status,
        ];

        if (!empty($this->entity_id)) {
            $attributes['entity_id'] = $this->entity_id;
        }

        return response()->json($attributes, $this->status);
    }

    /**
     * Create a new ApiException using a translation key.
     *
     * Looks for a message under the `exception.{key}` translation path.
     *
     * @param string $key Translation key for the message.
     * @param int $status HTTP status code.
     * @param array|null $errors Optional array of error details.
     * @param int|null $entity_id Optional related entity ID.
     * @return static
     */
    public static function fromKey(string $key, int $status = 400, ?array $errors = null, ?int $entity_id = 0): self
    {
        return new self(__('exception.' . $key), $status, $errors, $entity_id);
    }
}
