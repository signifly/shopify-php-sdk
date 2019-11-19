<?php

namespace Signifly\Shopify\Actions;

use Illuminate\Support\Collection;
use Signifly\Shopify\Exceptions\InvalidActionException;
use Signifly\Shopify\Resources\ApiResource;
use Signifly\Shopify\Shopify;
use Signifly\Shopify\Support\Cursor;
use Signifly\Shopify\Support\Path;
use Signifly\Shopify\Support\ResourceKey;
use Signifly\Shopify\TransformsResources;

abstract class Action
{
    use TransformsResources;

    /**
     * The parent resource key.
     *
     * @var string
     */
    protected $parent;

    /**
     * The parent resource id.
     *
     * @var int
     */
    protected $parentId;

    /**
     * The actions that require a parent.
     *
     * @var array
     */
    protected $requiresParent = [];

    /**
     * The resource key of the action.
     *
     * @var \Signifly\Shopify\Support\ResourceKey
     */
    protected $resourceKey;

    /**
     * The shopify client.
     *
     * @var \Signifly\Shopify\Shopify
     */
    protected $shopify;

    /**
     * Create a new Action.
     *
     * @param \Signifly\Shopify\Shopify     $shopify
     * @param \Signifly\Shopify\Support\ResourceKey $resourceKey
     */
    public function __construct(Shopify $shopify, ResourceKey $resourceKey)
    {
        $this->shopify = $shopify;
        $this->resourceKey = $resourceKey;
    }

    /**
     * Retrieve all resources (paginated due to limit).
     *
     * @param  array  $params
     * @return Collection
     */
    public function all(array $params = []): Collection
    {
        $this->guardAgainstMissingParent('all');

        $response = $this->shopify->get(
            $this->path()->withParams($params)
        );

        return $this->transformCollectionFromResponse($response);
    }

    /**
     * Count all the resources.
     *
     * @param  array  $params
     * @return int
     */
    public function count(array $params = []): int
    {
        $this->guardAgainstMissingParent('count');

        $response = $this->shopify->get(
            $this->path()->appends('count')->withParams($params)
        );

        return $response['count'] ?? 0;
    }

    /**
     * Create a resource.
     *
     * @param  array  $data
     * @return ApiResource
     */
    public function create(array $data): ApiResource
    {
        $this->guardAgainstMissingParent('create');

        $response = $this->shopify->post($this->path(), [
            $this->resourceKey->singular() => $data,
        ]);

        return $this->transformItemFromResponse($response);
    }

    /** Alias of self::destroy() */
    public function delete($id): void
    {
        $this->destroy($id);
    }

    /**
     * Destroy a resource.
     *
     * @param  int|string $id
     * @return void
     */
    public function destroy($id): void
    {
        $this->guardAgainstMissingParent('destroy');

        $this->shopify->delete($this->path($id));
    }

    /**
     * Find a resource.
     *
     * @param  int|string $id
     * @return ApiResource
     */
    public function find($id): ApiResource
    {
        $this->guardAgainstMissingParent('find');

        $response = $this->shopify->get($this->path($id));

        return $this->transformItemFromResponse($response);
    }

    /** Alias of self::all() */
    public function get(array $params = []): Collection
    {
        return $this->all($params);
    }

    /**
     * Paginate results.
     *
     * @param  array  $params
     * @return \Signifly\Shopify\Support\Cursor
     */
    public function paginate(array $params = []): Cursor
    {
        $this->guardAgainstMissingParent('paginate');

        $response = $this->shopify->get(
            $this->path()->withParams($params)
        );

        return new Cursor($this->shopify, $this->resourceKey, $response);
    }

    /**
     * Update the resource.
     *
     * @param  int|string $id
     * @param  array  $data
     * @return ApiResource
     */
    public function update($id, array $data): ApiResource
    {
        $this->guardAgainstMissingParent('update');

        $response = $this->shopify->put($this->path($id), [
            $this->resourceKey->singular() => $data,
        ]);

        return $this->transformItemFromResponse($response);
    }

    /**
     * Set the parent and parentId.
     *
     * @param  string $parent
     * @param  int|string $parentId
     * @return self
     */
    public function with(string $parent, $parentId): self
    {
        $this->parent = $parent;
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Guard against missing parent.
     *
     * @param  string $methodName
     * @return void
     */
    protected function guardAgainstMissingParent(string $methodName): void
    {
        if ($this->requiresParent($methodName) && ! $this->hasParent()) {
            throw InvalidActionException::requiresParent(static::class, $methodName);
        }
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

    /**
     * Determine if the action has a parent.
     *
     * @return bool
     */
    protected function hasParent(): bool
    {
        return $this->parent && $this->parentId;
    }

    /**
     * Get the parent path.
     *
     * @return string
     */
    protected function parentPath(): string
    {
        return $this->hasParent() ? "{$this->parent}/{$this->parentId}" : '';
    }

    /**
     * Create a new path.
     *
     * @param  int|string|null $id
     * @return \Signifly\Shopify\Support\Path
     */
    protected function path($id = null): Path
    {
        return Path::make($this->resourceKey)
            ->prepends($this->parentPath())
            ->id($id);
    }

    /**
     * Check if the action requires a parent resource.
     *
     * @param  string $methodName
     * @return bool
     */
    protected function requiresParent(string $methodName): bool
    {
        if (in_array('*', $this->requiresParent)) {
            return true;
        }

        return in_array($methodName, $this->requiresParent);
    }
}
