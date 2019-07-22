<?php

namespace Signifly\Shopify\Exceptions;

use Exception;

class InvalidActionException extends Exception
{
    public static function doesNotExist(string $className): self
    {
        return new static(sprintf('Action `%s` does not exist', $className));
    }

    public static function requiresParent(string $className, string $methodName): self
    {
        return new static(sprintf('%s::%s required a parent', $className, $methodName));
    }
}
