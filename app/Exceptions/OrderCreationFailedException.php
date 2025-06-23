<?php

namespace App\Exceptions;

/**
 * Class OrderCreationFailedException
 *
 * Exception thrown when the creation of an order fails due to a server or business logic error.
 * Extends ApiException to standardize API error handling with optional error context.
 *
 * @package App\Exceptions
 */
class OrderCreationFailedException extends ApiException
{
    /**
     * OrderCreationFailedException constructor.
     *
     * @param array|null $errors Optional array of error details to include in the response.
     */
    public function __construct(?array $errors = null)
    {
        parent::__construct(
            message: __('order.errors.creation_failed'), 
            status: 500,
            errors: $errors,
        );
    }
}
