<?php

namespace App\Exceptions;

class InvalidCredentialsException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            message: __('auth.invalid_credentials'),
            status: 401
        );
    }
}