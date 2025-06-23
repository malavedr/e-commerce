<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

/**
 * Class ApiResponder
 *
 * Helper class to standardize JSON API responses.
 */
class ApiResponder
{
    /**
     * Return a successful JSON response.
     *
     * @param string $message A localized message describing the success.
     * @param mixed|null $data Optional data payload to include in the response.
     * @param int $status HTTP status code, default is 200.
     * @return JsonResponse
     */
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

    /**
     * Return an error JSON response.
     *
     * @param string $message A message describing the error.
     * @param array|null $errors Optional array of error details.
     * @param int $status HTTP status code, default is 400.
     * @return JsonResponse
     */
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

    /**
     * Create an ApiResponder instance from a localization key.
     *
     * @param string $key The localization key for the message.
     * @param int $status HTTP status code, default is 400.
     * @param array|null $errors Optional error details.
     * @return self
     */
    public static function fromKey(string $key, int $status = 400, ?array $errors = null)
    {
        return new self(__($key), $status, $errors);
    }
}
