<?php

namespace App\Exceptions;

/**
 * Class InvalidCredentialsException
 *
 * Exception thrown when authentication fails due to invalid user credentials.
 * Extends the base ApiException to provide a standardized JSON response with status code 401.
 *
 * @package App\Exceptions
 */
class InvalidCredentialsException extends ApiException
{
    /**
     * InvalidCredentialsException constructor.
     *
     * Initializes the exception with a 401 Unauthorized status and a localized message.
     */
    public function __construct()
    {
        parent::__construct(
            message: __('auth.invalid_credentials'),
            status: 401
        );
    }
}