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

    /**
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->getAttribute($key);
        }

        throw new Exception('Property ' . $key . ' does not exist on ' . get_called_class());
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return ! array_key_exists($this->attributes[$offset]);
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
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
        return $this->setAttribute($offset, $value);
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

    /**
     * Get an attribute.
     *
     * @param  string $key
     * @return mixed
     */
    protected function getAttribute($key)
    {
        return $this->attributes[$key];
    }

    /**
     * Set an attribute.
     *
     * @param string $key
     * @param mixed $value
     */
    protected function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }
}
