<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class ApiResponder
{
    public static function success(string $message, mixed $data = null, int $status = 200): JsonResponse
    {
        $attributes = [
            'message' => __($message),
            'status' => $status,
            'data' => $data,
        ];

        if (is_null($data)) unset($attributes['data']);

        return response()->json($attributes, $status);
    }

    public static function error(string $message, ?array $errors = [], int $status = 400)
    {
        $attributes = [
            'message' => $message,
            'status' => $status,
            'errors' => $errors,
        ];

        if (is_null($errors)) unset($attributes['errors']);

        return response()->json($attributes, $status);
    }
}