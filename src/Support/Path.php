<?php

namespace Signifly\Shopify\Support;

use Signifly\Shopify\Exceptions\InvalidFormatException;

class Path
{
    const FORMAT_JSON = 'json';
    const FORMAT_XML = 'xml';

    const VALID_FORMATS = [
        self::FORMAT_JSON,
        self::FORMAT_XML,
    ];

    /** @var string|null */
    protected $appends;

    /** @var string */
    protected $format = self::FORMAT_JSON;

    /** @var int|string */
    protected $id;

    /** @var array */
    protected $params = [];

    /** @var string|null */
    protected $prepends;

    /** @var \Signifly\Shopify\Support\ResourceKey */
    protected $resourceKey;

    /**
     * Create a new Path instance.
     *
     * @param \Signifly\Shopify\Support\ResourceKey $resourceKey
     */
    public function __construct(ResourceKey $resourceKey)
    {
        $this->resourceKey = $resourceKey;
    }

    /**
     * Instantiate a new Path.
     *
     * @param  array $args
     * @return self
     */
    public static function make(...$args): self
    {
        return new self(...$args);
    }

    /**
     * String to append to path.
     *
     * @param  string $appends
     * @return self
     */
    public function appends(string $appends): self
    {
        $this->appends = $appends;

        return $this;
    }

    /**
     * Build the path.
     *
     * @return string
     */
    public function build(): string
    {
        $path = collect([
            $this->prepends,
            $this->resourceKey->plural(),
            $this->id,
            $this->appends,
        ])
        ->filter()
        ->implode('/');

        $uri = "{$path}.{$this->format}";

        return $this->hasParams() ? $uri.'?'.http_build_query($this->params) : $uri;
    }

    /**
     * Set the return format.
     *
     * @param  string $format
     * @return self
     */
    public function format(string $format): self
    {
        if (! in_array($format, self::VALID_FORMATS)) {
            throw InvalidFormatException::for($format);
        }

        $this->format = $format;

        return $this;
    }

    /**
     * Checks if there are any params set.
     *
     * @return bool
     */
    public function hasParams(): bool
    {
        return count($this->params) > 0;
    }

    /**
     * The resource identifier.
     *
     * @param  int|string $id
     * @return self
     */
    public function id($id): self
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
    public function prepends(string $prepends): self
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
    public function withParams(array $params): self
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
