<?php

namespace App\Exceptions;

class ProductUpdateFailedException extends ApiException
{
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
