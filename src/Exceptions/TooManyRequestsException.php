<?php

namespace Signifly\Shopify\Exceptions;

use Exception;

class TooManyRequestsException extends Exception
{
    public function __construct($message = null)
    {
        parent::__construct($message ?? 'Too many requests.');
    }
}
