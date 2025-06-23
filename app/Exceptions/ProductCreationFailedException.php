<?php

namespace App\Exceptions;

class ProductCreationFailedException extends ApiException
{
    public function __construct(?array $errors = null)
    {
        parent::__construct(
            message: __('exception.products.creation_failed'), 
            status: 500,
            errors: $errors,
        );
    }
}
