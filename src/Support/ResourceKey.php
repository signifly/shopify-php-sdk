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
        $this->name = Str::lower(Str::snake($name));
    }

    /**
     * Get the action class name for the resource.
     *
     * @return string
     */
    public function actionClassName(): string
    {
        return "Signifly\\Shopify\\Actions\\{$this->resourceKey->studly()}Action";
    }

    /**
     * Get the resource key in a class name context.
     *
     * @return string
     */
    public function studly(): string
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
     * Get the plural name of the resurce key.
     *
     * @return string
     */
    public function plural(): string
    {
        return Str::plural($this->name);
    }

    /**
     * Get the fully qualified class name for the resource.
     *
     * @return string
     */
    public function resourceClassName(): string
    {
        return "Signifly\\Shopify\\Resources\\{$this->studly()}Resource";
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
