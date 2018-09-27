<?php

namespace Signifly\Shopify\Actions;

use Exception;

class Path
{
    const FORMAT_JSON = 'json';

    const VALID_FORMATS = [
        self::FORMAT_JSON,
    ];

    protected $appends;

    protected $format = self::FORMAT_JSON;

    protected $id;

    protected $params = [];

    protected $prepends;

    protected $resourceKey;

    /**
     * Create a new Path instance.
     *
     * @param string $resourceKey
     */
    public function __construct(string $resourceKey)
    {
        $this->resourceKey = $resourceKey;
    }

    /**
     * String to append to path.
     *
     * @param  string $appends
     * @return self
     */
    public function appends(string $appends) : self
    {
        $this->appends = $appends;

        return $this;
    }

    /**
     * Build the path.
     *
     * @return string
     */
    public function build() : string
    {
        $path = collect([
                $this->prepends,
                $this->resourceKey,
                $this->id,
                $this->appends,
            ])
            ->filter()
            ->implode('/');

        $uri = "{$path}.{$this->format}";

        return $this->hasParams() ? $uri . '?' . http_build_query($this->params) : $uri;
    }

    /**
     * Set the return format.
     *
     * @param  string $format
     * @return self
     */
    public function format(string $format) : self
    {
        if (! in_array($format, self::VALID_FORMATS)) {
            throw new Exception('Invalid format provided to path.');
        }

        $this->format = $format;

        return $this;
    }

    /**
     * Checks if there are any params set.
     *
     * @return bool
     */
    public function hasParams()
    {
        return count($this->params) > 0;
    }

    /**
     * The resource identifier.
     *
     * @param  int $id
     * @return self
     */
    public function id($id) : self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * String to prepend to path.
     *
     * @param  string $prepends
     * @return self
     */
    public function prepends(string $prepends) : self
    {
        $this->prepends = $prepends;

        return $this;
    }

    /**
     * Set the params on the path.
     *
     * @param  array  $params
     * @return self
     */
    public function withParams(array $params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Convert path to string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->build();
    }
}
