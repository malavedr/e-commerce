<?php

namespace App\Exceptions;

/**
 * Class ProductDeletionFailedException
 *
 * Exception thrown when a product deletion fails, typically due to constraints or server issues.
 * Extends ApiException to standardize the error format and optionally attach the product ID.
 *
 * @package App\Exceptions
 */
class ProductDeletionFailedException extends ApiException
{
    /**
     * ProductDeletionFailedException constructor.
     *
     * @param int|null $entity_id Optional ID of the product that failed to delete.
     * @param array|null $errors Optional array of additional error details.
     */
    public function __construct(?int $entity_id, ?array $errors = null)
    {
        parent::__construct(
            message: __('exception.products.deletion_failed'), 
            status: 500,
            errors: $errors,
            entity_id: $entity_id,
        );
    }
}
