<?php

namespace App\Exceptions;

/**
 * Class ProductUpdateFailedException
 *
 * Exception thrown when updating a product fails due to server or data issues.
 * Extends ApiException to provide standardized error responses with optional product ID and error details.
 *
 * @package App\Exceptions
 */
class ProductUpdateFailedException extends ApiException
{
    /**
     * ProductUpdateFailedException constructor.
     *
     * @param int|null $entity_id Optional ID of the product that failed to update.
     * @param array|null $errors Optional array of additional error details.
     */
    public function __construct(?int $entity_id, ?array $errors = null)
    {
        parent::__construct(
            message: __('exception.products.update_failed'), 
            status: 500, 
            errors: $errors,
            entity_id: $entity_id,
        );
    }
}
