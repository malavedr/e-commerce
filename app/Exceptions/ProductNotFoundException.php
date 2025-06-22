<?php

namespace App\Exceptions;

class ProductNotFoundException extends ApiException
{
    public function __construct()
    {
        parent::__construct(__('exception.products.not_found'), 404);
    }
}
