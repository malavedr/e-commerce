<?php

namespace App\Exceptions;

class ProductUpdateFailedException extends ApiException
{
    public function __construct(?array $errors = null)
    {
        parent::__construct(__('exception.products.update_failed'), 500, $errors);
    }
}
