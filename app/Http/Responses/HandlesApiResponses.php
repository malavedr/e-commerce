<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

trait HandlesApiResponses
{
    protected function success(string $message = 'app.action.success', mixed $data = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'message' => __($message),
            'status' => $status,
            'data' => $data,
        ], $status);
    }

    protected function created(string $message = 'app.action.created', mixed $data = null): JsonResponse
    {
        return $this->success($message, $data, 201);
    }

    protected function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }

    protected function error(string $message, array $errors = [], int $status = 400): JsonResponse
    {
        return response()->json([
            'message' => __($message),
            'status' => $status,
            'errors' => $errors,
        ], $status);
    }
}
