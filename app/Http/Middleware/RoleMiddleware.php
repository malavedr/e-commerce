<?php

namespace App\Http\Middleware;

use App\Support\ApiResponder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RoleMiddleware
 *
 * Middleware to restrict access to routes based on user roles.
 * Checks if the authenticated user has at least one of the specified roles.
 * Returns a 403 Forbidden JSON response if authorization fails.
 *
 * @package App\Http\Middleware
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request The current HTTP request instance.
     * @param Closure $next The next middleware or request handler.
     * @param string ...$roles One or more roles required to access the route.
     * @return Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user() || ! $request->user()->hasRole($roles)) {
            return ApiResponder::error(__('auth.unauthorized'), null, 403);
        }

        return $next($request);
    }
}
