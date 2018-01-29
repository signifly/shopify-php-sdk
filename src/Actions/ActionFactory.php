<?php

namespace Signifly\Shopify\Actions;

use Exception;
use Illuminate\Support\Str;

class ActionFactory
{
    protected $resourceKey;

    public function __construct($resourceKey)
    {
        $this->resourceKey = $resourceKey;
    }

    public function make($shopify)
    {
        $class = $this->getQualifiedClassName();

        if (! class_exists($class)) {
            throw new Exception('Action does not exist');
        }

        return new $class($shopify);
    }

    protected function getQualifiedClassName()
    {
        return __NAMESPACE__ . '\\' . Str::studly(Str::singular($this->resourceKey)) . 'Action';
    }
}
