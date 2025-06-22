<?php

namespace App\Exceptions;

class ProductCreationFailedException extends ApiException
{
    public function __construct(?array $errors = null)
    {
        parent::__construct(__('exception.products.creation_failed'), 500, $errors);
    }
}
