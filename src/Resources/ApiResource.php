<?php

namespace Signifly\Shopify\Resources;

use Exception;
use Signifly\Shopify\Shopify;

abstract class ApiResource
{
    /** @var array */
    protected $attributes = [];

    /** @var \Signifly\Shopify\Shopify */
    protected $shopify;

    /**
     * @param  array $attributes
     * @param  \Signifly\Shopify\Shopify $shopify
     */
    public function __construct(array $attributes, Shopify $shopify)
    {
        $this->attributes = $attributes;

        $this->shopify = $shopify;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        throw new Exception('Bad key');
    }
}
