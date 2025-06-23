<?php

namespace App\Traits;

use App\Support\ApiResponder;
use Illuminate\Http\JsonResponse;

/**
 * Trait HandlesApiResponses
 *
 * Provides helper methods to standardize API JSON responses
 * in controllers or other classes.
 */
trait HandlesApiResponses
{
    /**
     * Return a success JSON response.
     *
     * @param string $message Localization key for success message.
     * @param mixed|null $data Optional data payload.
     * @param int $status HTTP status code (default 200).
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success(string $message = 'app.action.success', mixed $data = null, int $status = 200)
    {
        return ApiResponder::success($message, $data, $status);
    }

    /**
     * Return a JSON response indicating a resource was created.
     *
     * @param string $message Localization key for created message.
     * @param mixed|null $data Optional data payload.
     * @return \Illuminate\Http\JsonResponse
     */
    protected function created(string $message = 'app.action.created', mixed $data = null): JsonResponse
    {
        return ApiResponder::success($message, $data, 201);
    }

    /**
     * Return a no content (204) JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }

    /**
     * Return an error JSON response.
     *
     * @param string $message Error message.
     * @param array $errors Additional error details.
     * @param int $status HTTP status code (default 400).
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error(string $message, array $errors = [], int $status = 400)
    {
        return ApiResponder::error($message, $errors, $status);
    }
}
