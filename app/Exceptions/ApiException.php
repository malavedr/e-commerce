<?php

namespace App\Exceptions;

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Exceptions\Concerns\LogsApiExceptions;

class ApiException extends Exception
{
    use LogsApiExceptions;

    public int $status;
    public array $errors;

    public function __construct(
        string $message = '',
        int $status = 500,
        ?array $errors = null
    ) {
        $this->status = $status;
        $this->errors = $errors ?? [];

        parent::__construct($message, $status);
    }

    public function render($request): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
            'status' => $this->status,
            'errors' => $this->errors,
        ], $this->status);
    }

    public static function fromKey(string $key, int $status = 400, ?array $errors = null): self
    {
        return new self(
            message: __('exception.' . $key),
            status: $status,
            errors: $errors
        );
    }
}