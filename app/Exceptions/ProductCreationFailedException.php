<?php

namespace App\Exceptions;

/**
 * Class ProductCreationFailedException
 *
 * Exception thrown when a product cannot be created due to a server error or data issue.
 * Provides a standardized JSON response using the base ApiException class.
 *
 * @package App\Exceptions
 */
class ProductCreationFailedException extends ApiException
{
    /**
     * ProductCreationFailedException constructor.
     *
     * @param array|null $errors Optional array of error details to include in the response.
     */
    public function __construct(?array $errors = null)
    {
        parent::__construct(
            message: __('exception.products.creation_failed'), 
            status: 500,
            errors: $errors,
        );
    }
}
