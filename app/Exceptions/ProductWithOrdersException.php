<?php

namespace App\Exceptions;

/**
 * Class ProductWithOrdersException
 *
 * Exception thrown when attempting to delete a product that has associated orders.
 * Returns a 409 Conflict status indicating the product cannot be deleted due to existing dependencies.
 *
 * @package App\Exceptions
 */
class ProductWithOrdersException extends ApiException
{
    /**
     * ProductWithOrdersException constructor.
     *
     * @param int $entity_id ID of the product that has associated orders.
     */
    public function __construct(int $entity_id)
    {
        parent::__construct(
            message: __('exception.products.has_orders'), 
            status: 409,
            entity_id: $entity_id
        );
    }
}
