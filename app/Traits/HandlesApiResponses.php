<?php

namespace App\Traits;

use App\Support\ApiResponder;
use Illuminate\Http\JsonResponse;

trait HandlesApiResponses
{
    protected function success(string $message = 'app.action.success', mixed $data = null, int $status = 200)
    {
        return ApiResponder::success($message, $data, $status);
    }

    protected function created(string $message = 'app.action.created', mixed $data = null): JsonResponse
    {
        return ApiResponder::success($message, $data, 201);
    }

    protected function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }

    protected function error(string $message, array $errors = [], int $status = 400)
    {
        return ApiResponder::error($message, $errors, $status);
    }
}
