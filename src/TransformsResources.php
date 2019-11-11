<?php

namespace Signifly\Shopify;

use Illuminate\Support\Collection;
use Signifly\Shopify\Resources\ApiResource;
use Signifly\Shopify\Support\ResourceKey;

trait TransformsResources
{
    /**
     * Get the resource key.
     *
     * @return \Signifly\Shopify\Support\ResourceKey
     */
    abstract protected function getResourceKey(): ResourceKey;

    /**
     * Transform the items of the collection to the given resource class.
     *
     * @param  array $items
     * @param  string $class
     * @return Collection
     */
    protected function transformCollection(array $items, string $class): Collection
    {
        return collect($items)->map(function ($attributes) use ($class) {
            return $this->transformItem($attributes, $class);
        });
    }

    /**
     * Transform the response to a collection of resource items.
     *
     * @param  array $response
     * @return Collection
     */
    protected function transformCollectionFromResponse(array $response): Collection
    {
        $collection = $response[$this->getResourceKey()->plural()];

        return $this->transformCollection(
            $collection,
            $this->getResourceKey()->resourceClassName()
        );
    }

    /**
     * Transform the item to the given class.
     *
     * @param  array $item
     * @param  string $class
     * @return array
     */
    protected function transformItem(array $attributes, string $class): ApiResource
    {
        return new $class($attributes, $this->shopify);
    }

    /**
     * Transform item from response.
     *
     * @param  array $response
     * @return \Signifly\Shopify\Resources\ApiResource
     */
    protected function transformItemFromResponse(array $response): ApiResource
    {
        return $this->transformItem(
            $response[$this->getResourceKey()->singular()],
            $this->getResourceKey()->resourceClassName()
        );
    }
}
