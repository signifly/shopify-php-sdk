<?php

namespace Signifly\Shopify\Support;

use Illuminate\Support\Str;

/**
 * @internal
 */
class ResourceKey
{
    /**
     * The name of the resource key.
     *
     * @var string
     */
    protected $name;

    /**
     * Create a new ResourceKey instance.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the resource key in a class name context.
     *
     * @return string
     */
    public function className(): string
    {
        return Str::studly($this->singular());
    }

    /**
     * Get the name of the resource key.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Get the resource key in singular form.
     *
     * @return string
     */
    public function singular(): string
    {
        return Str::singular($this->name);
    }
}
