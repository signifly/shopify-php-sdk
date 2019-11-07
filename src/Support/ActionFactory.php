<?php

namespace Signifly\Shopify\Support;

use Signifly\Shopify\Actions\Action;
use Signifly\Shopify\Exceptions\InvalidActionException;
use Signifly\Shopify\Shopify;

class ActionFactory
{
    /** @var \Signifly\Shopify\ResourceKey */
    protected $resourceKey;

    /** @var \Signifly\Shopify\Shopify */
    protected $shopify;

    public function __construct(ResourceKey $resourceKey, Shopify $shopify)
    {
        $this->resourceKey = $resourceKey;
        $this->shopify = $shopify;
    }

    /**
     * A static make helper.
     *
     * @param  array $args
     * @return \Signifly\Shopify\Actions\Action
     */
    public static function make(...$args): Action
    {
        return (new static(...$args))->create();
    }

    /**
     * create a new action.
     *
     * @return \Signifly\Shopify\Actions\Action
     */
    public function create(): Action
    {
        $class = $this->getQualifiedClassName();

        if (! class_exists($class)) {
            throw InvalidActionException::doesNotExist($class);
        }

        return new $class($this->shopify);
    }

    /**
     * Get the qualified class name.
     *
     * @return string
     */
    protected function getQualifiedClassName(): string
    {
        return 'Signifly\\Shopify\\Actions\\'.$this->resourceKey->className().'Action';
    }
}
