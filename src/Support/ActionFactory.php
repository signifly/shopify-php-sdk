<?php

namespace Signifly\Shopify\Support;

use Exception;
use Illuminate\Support\Str;
use Signifly\Shopify\Shopify;
use Signifly\Shopify\Actions\Action;

class ActionFactory
{
    /** @var string */
    protected $resourceKey;

    /** @var \Signifly\Shopify\Shopify */
    protected $shopify;

    public function __construct(string $resourceKey, Shopify $shopify)
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
            throw new Exception(sprintf('Action `%s` does not exist', $class));
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
        return __NAMESPACE__.'\\'.Str::studly(Str::singular($this->resourceKey)).'Action';
    }
}
