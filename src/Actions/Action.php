<?php

namespace Signifly\Shopify\Actions;

use Exception;
use Illuminate\Support\Str;
use Signifly\Shopify\Shopify;
use Illuminate\Support\Collection;
use Signifly\Shopify\Resources\ApiResource;

abstract class Action
{
    protected $guarded = [];

    protected $parent;

    protected $parentId;

    protected $shopify;

    protected $requiresParent = [];

    public function __construct(Shopify $shopify)
    {
        $this->shopify = $shopify;
    }

    public function all()
    {
        $this->guardAgainstMissingParent('all');

        $response = $this->shopify->get($this->path());

        return $this->transformCollection($response[$this->getResourceKey()], $this->getResourceClass());
    }

    public function count()
    {
        $this->guardAgainstMissingParent('count');

        $response = $this->shopify->get($this->path(null, 'count'));

        return $response['count'];
    }

    public function create(array $data)
    {
        $this->guardAgainstMissingParent('create');

        $key = Str::singular($this->getResourceKey());

        $response = $this->shopify->post($this->path(null, '', $this->parentPath()), [$key => $data]);

        return $this->transformItem($response[$key], $this->getResourceClass());
    }

    public function destroy($id)
    {
        $this->guardAgainstMissingParent('destroy');

        return $this->shopify->delete($this->path($id));
    }

    public function find($id)
    {
        $this->guardAgainstMissingParent('find');

        return $this->shopify->get($this->path());
    }

    public function update($id, array $data)
    {
        $this->guardAgainstMissingParent('update');

        return $this->shopify->put($this->path($id), $data);
    }

    public function with($parent, $parentId)
    {
        $this->parent = $parent;
        $this->parentId = $parentId;

        return $this;
    }

    protected function getResourceClass()
    {
        return "Signifly\\Shopify\\Resources\\{$this->getResourceString()}Resource";
    }

    protected function getResourceKey()
    {
        return Str::snake(Str::plural($this->getResourceString()));
    }

    protected function getResourceString()
    {
        return substr(class_basename(get_called_class()), 0, -6);
    }

    protected function guardAgainstMissingParent(string $methodName)
    {
        if ($this->requiresParent($methodName) && ! $this->hasParent()) {
            throw new Exception('Requires parent');
        }
    }

    protected function hasParent()
    {
        return ($this->parent && $this->parentId);
    }

    protected function parentPath()
    {
        return $this->hasParent() ? "{$this->parent}/{$this->parentId}" : "";
    }

    protected function path($id = null, $appends = '', $prepends = '', $format = '.json')
    {
        $path = collect([$prepends, $this->getResourceKey(), $id, $appends])
            ->filter()
            ->implode('/');

        return $path . $format;
    }

    protected function requiresParent(string $methodName)
    {
        return in_array($methodName, $this->requiresParent);
    }

    /**
     * Transform the items of the collection to the given class.
     *
     * @param  array $collection
     * @param  string $class
     * @return Collection
     */
    protected function transformCollection(array $collection, string $class) : Collection
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
    protected function transformItem(array $attributes, string $class) : ApiResource
    {
        return new $class($attributes, $this->shopify);
    }
}
