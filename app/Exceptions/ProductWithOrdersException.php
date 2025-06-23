<?php

namespace App\Exceptions;

class ProductWithOrdersException extends ApiException
{
    public function __construct(int $entity_id)
    {
        parent::__construct(
            message: __('exception.products.has_orders'), 
            status: 409,
            entity_id: $entity_id
        );
    }
}
