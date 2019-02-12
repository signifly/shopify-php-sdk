<?php

namespace Signifly\Shopify\Actions;

use Exception;
use Illuminate\Support\Str;

class ActionFactory
{
    protected $resourceKey;

    protected $shopify;

    public function __construct($resourceKey, $shopify)
    {
        $this->resourceKey = $resourceKey;
        $this->shopify = $shopify;
    }

    public function make()
    {
        $class = $this->getQualifiedClassName();

        if (! class_exists($class)) {
            throw new Exception('Action does not exist');
        }

        return new $class($this->shopify);
    }

    protected function getQualifiedClassName()
    {
        return __NAMESPACE__.'\\'.Str::studly(Str::singular($this->resourceKey)).'Action';
    }
}
