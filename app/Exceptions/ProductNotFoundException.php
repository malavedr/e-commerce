<?php

namespace App\Exceptions;

/**
 * Class ProductNotFoundException
 *
 * Exception thrown when a product cannot be found in the database.
 * Returns a standardized 404 Not Found API response with optional product ID and error details.
 *
 * @package App\Exceptions
 */
class ProductNotFoundException extends ApiException
{
    /**
     * ProductNotFoundException constructor.
     *
     * @param int|null $entity_id Optional ID of the missing product.
     * @param array|null $errors Optional array of additional error details.
     */
    public function __construct(?int $entity_id, ?array $errors = null)
    {
        parent::__construct(
            message: __('exception.products.not_found'), 
            status: 404,
            errors: $errors,
            entity_id: $entity_id,
        );
    }
}
