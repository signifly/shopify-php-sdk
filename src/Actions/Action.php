<?php

namespace Signifly\Shopify\Actions;

use Exception;
use Illuminate\Support\Str;
use Signifly\Shopify\Shopify;
use Illuminate\Support\Collection;
use Signifly\Shopify\Support\Path;
use Signifly\Shopify\Resources\ApiResource;

abstract class Action
{
    protected $parent;

    protected $parentId;

    protected $shopify;

    protected $requiresParent = [];

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
            throw new Exception($methodName.' requires parent');
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
