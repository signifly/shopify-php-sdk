<?php

namespace Signifly\Shopify\Support;

use Illuminate\Support\Collection;
use Iterator;
use RuntimeException;
use Signifly\Shopify\Shopify;
use Signifly\Shopify\TransformsResources;

class Cursor implements Iterator
{
    use TransformsResources;

    const LINK_REGEX = '/<(.*page_info=([a-z0-9\-]+).*)>; rel="?{type}"?/i';

    /** @var \Signifly\Shopify\Shopify */
    protected $shopify;

    /** @var \Signifly\Shopify\Support\ResourceKey */
    protected $resourceKey;

    /** @var int */
    protected $position = 0;

    /** @var array */
    protected $links = [];

    /** @var array */
    protected $results = [];

    public function __construct(Shopify $shopify, ResourceKey $resourceKey, array $response)
    {
        $this->shopify = $shopify;
        $this->resourceKey = $resourceKey;
        $this->results[$this->position] = $response;

        $this->extractLinks();
    }

    /**
     * Get the current result set from the cursor.
     *
     * @return Collection
     */
    public function current(): Collection
    {
        return $this->transformCollectionFromResponse(
            $this->results[$this->position]
        );
    }

    /**
     * Determine if the cursor has next results.
     *
     * @return bool
     */
    public function hasNext(): bool
    {
        return ! empty($this->links['next']);
    }

    /**
     * Determine if the cursor has previous results.
     *
     * @return bool
     */
    public function hasPrev(): bool
    {
        return $this->position > 0;
    }

    /**
     * Get the key of the iterator.
     *
     * @return scalar
     */
    public function key(): scalar
    {
        return $this->position;
    }

    /**
     * Get the next results from the cursor.
     *
     * @return void
     */
    public function next(): void
    {
        $this->position++;

        if (! $this->valid() && $this->hasNext()) {
            $this->results[$this->position] = $this->shopify->get($this->links['next']);
            $this->extractLinks();
        }
    }

    /**
     * Get the previous results from the cursor.
     *
     * @return void
     */
    public function prev(): void
    {
        if (! $this->hasPrev()) {
            throw new RuntimeException('No previous results available.');
        }

        $this->position--;
    }

    /**
     * Rewind the cursor/iterator.
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * Checks if current position is valid.
     *
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->results[$this->position]);
    }

    /**
     * Extract links from the last response header.
     *
     * @return void
     */
    protected function extractLinks(): void
    {
        $response = $this->shopify->getLastResponse();

        if (! $response->hasHeader('Link')) {
            return;
        }

        $links = [
            'next' => null,
            'previous' => null,
        ];

        foreach (array_keys($links) as $type) {
            $matched = preg_match(
                str_replace('{type}', $type, static::LINK_REGEX),
                $response->getHeader('Link')[0],
                $matches
            );

            if ($matched) {
                $links[$type] = $matches[1];
            }
        }

        $this->links = $links;
    }

    /**
     * Get the resource key.
     *
     * @return \Signifly\Shopify\Support\ResourceKey
     */
    protected function getResourceKey(): ResourceKey
    {
        return $this->resourceKey;
    }
}
