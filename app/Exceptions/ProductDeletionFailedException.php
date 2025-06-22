<?php

namespace App\Exceptions;

class ProductDeletionFailedException extends ApiException
{
    public function __construct(?array $errors = null)
    {
        parent::__construct(__('exception.products.deletion_failed'), 500, $errors);
    }
}
