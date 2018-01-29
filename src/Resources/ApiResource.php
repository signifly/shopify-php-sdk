<?php

namespace Signifly\Shopify\Resources;

use Exception;
use ArrayAccess;
use Signifly\Shopify\Shopify;

abstract class ApiResource implements ArrayAccess
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

    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return ! is_null($this->attributes[$offset]);
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->attributes[$offset];
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->attributes[$offset] = $value;
    }

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }
}
