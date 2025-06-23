<?php

namespace App\Exceptions;

use App\Traits\LogsApiExceptions;
use Exception;
use Illuminate\Http\JsonResponse;

class ApiException extends Exception
{
    use LogsApiExceptions;

    public int $status;
    public int $entity_id;
    public array $errors;

    public function __construct(string $message = '', int $status = 500, ?array $errors = null, ?int $entity_id = 0) 
    {
        $this->status = $status;
        $this->errors = $errors ?? [];
        $this->entity_id = $entity_id;

        parent::__construct($message, $status);
    }

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

    public static function fromKey(string $key, int $status = 400, ?array $errors = null, ?int $entity_id = 0): self
    {
        return new self(__('exception.' . $key), $status, $errors, $entity_id);
    }
}
