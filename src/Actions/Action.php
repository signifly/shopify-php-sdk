<?php

namespace Signifly\Shopify\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Signifly\Shopify\Exceptions\InvalidActionException;
use Signifly\Shopify\Resources\ApiResource;
use Signifly\Shopify\Shopify;
use Signifly\Shopify\Support\Path;

abstract class Action
{
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
     * The shopify client.
     *
     * @var \Signifly\Shopify\Shopify
     */
    protected $shopify;

    public function __construct(Shopify $shopify)
    {
        $this->shopify = $shopify;
    }

    public function all(array $params = []): Collection
    {
        $this->guardAgainstMissingParent('all');

        $response = $this->shopify->get(
            $this->path()->withParams($params)
        );

        return $this->transformCollection(
            $response[$this->getResourceKey()],
            $this->getResourceClass()
        );
    }

    public function count(array $params = []): int
    {
        $this->guardAgainstMissingParent('count');

        $response = $this->shopify->get(
            $this->path()->appends('count')->withParams($params)
        );

        return $response['count'] ?? 0;
    }

    public function create(array $data): ApiResource
    {
        $this->guardAgainstMissingParent('create');

        $response = $this->shopify->post($this->path(), [
            $this->getSingularResourceKey() => $data,
        ]);

        return $this->transformItemFromResponse($response);
    }

    /** Alias of self::destroy() */
    public function delete($id): void
    {
        $this->destroy($id);
    }

    public function destroy($id): void
    {
        $this->guardAgainstMissingParent('destroy');

        $this->shopify->delete($this->path($id));
    }

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

    public function update($id, array $data): ApiResource
    {
        $this->guardAgainstMissingParent('update');

        $response = $this->shopify->put($this->path($id), [
            $this->getSingularResourceKey() => $data,
        ]);

        return $this->transformItemFromResponse($response);
    }

    public function with($parent, $parentId): self
    {
        $this->parent = $parent;
        $this->parentId = $parentId;

        return $this;
    }

    protected function getResourceClass(): string
    {
        return "Signifly\\Shopify\\Resources\\{$this->getResourceString()}Resource";
    }

    protected function getResourceKey(): string
    {
        return Str::snake(Str::plural($this->getResourceString()));
    }

    protected function getResourceString(): string
    {
        return substr(class_basename(get_called_class()), 0, -6);
    }

    protected function getSingularResourceKey(): string
    {
        return Str::singular($this->getResourceKey());
    }

    protected function guardAgainstMissingParent(string $methodName): void
    {
        if ($this->requiresParent($methodName) && ! $this->hasParent()) {
            throw InvalidActionException::requiresParent(static::class, $methodName);
        }
    }

    protected function hasParent(): bool
    {
        return $this->parent && $this->parentId;
    }

    protected function parentPath(): string
    {
        return $this->hasParent() ? "{$this->parent}/{$this->parentId}" : '';
    }

    protected function path($id = null): Path
    {
        $path = (new Path($this->getResourceKey()))->prepends($this->parentPath());

        return $id ? $path->id($id) : $path;
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

    /**
     * Transform the items of the collection to the given class.
     *
     * @param  array $collection
     * @param  string $class
     * @return Collection
     */
    protected function transformCollection(array $collection, string $class): Collection
    {
        return collect($collection)->map(function ($attributes) use ($class) {
            return $this->transformItem($attributes, $class);
        });
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
    protected function transformItemFromResponse($response): ApiResource
    {
        return $this->transformItem(
            $response[$this->getSingularResourceKey()],
            $this->getResourceClass()
        );
    }
}
