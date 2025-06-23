<?php

namespace App\Exceptions;

class ProductNotFoundException extends ApiException
{
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
