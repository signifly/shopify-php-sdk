<?php

namespace Signifly\Shopify\Support;

use Illuminate\Support\Collection;
use RuntimeException;
use Signifly\Shopify\Shopify;
use Signifly\Shopify\TransformsResources;

class Cursor
{
    use TransformsResources;

    const LINK_REGEX = '/<(.*page_info=([a-z0-9\-]+).*)>; rel="?{type}"?/i';

    /** @var \Signifly\Shopify\Shopify */
    protected $shopify;

    /** @var \Signifly\Shopify\Support\ResourceKey */
    protected $resourceKey;

    /** @var array */
    protected $response;

    /** @var array */
    protected $links = [];

    public function __construct(Shopify $shopify, ResourceKey $resourceKey, array $response)
    {
        $this->shopify = $shopify;
        $this->resourceKey = $resourceKey;
        $this->response = $response;

        $this->extractLinks();
    }

    /**
     * Get the current result set from the cursor.
     *
     * @return Collection
     */
    public function current(): Collection
    {
        return $this->transformCollectionFromResponse($this->response);
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
        return ! empty($this->links['previous']);
    }

    /**
     * Get the next results from the cursor.
     *
     * @return Collection
     */
    public function next(): Collection
    {
        if (! $this->hasNext()) {
            throw new RuntimeException('No next results available.');
        }

        $response = $this->shopify->get($this->links['next']);

        $this->response = $response;
        $this->extractLinks();

        return $this->transformCollectionFromResponse($response);
    }

    /**
     * Get the previous results from the cursor.
     *
     * @return Collection
     */
    public function prev(): Collection
    {
        if (! $this->hasPrev()) {
            throw new RuntimeException('No previous results available.');
        }

        $response = $this->shopify->get($this->links['previous']);

        $this->response = $response;
        $this->extractLinks();

        return $this->transformCollectionFromResponse($response);
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
