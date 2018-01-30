<?php

namespace Signifly\Shopify\Actions;

use Illuminate\Support\Str;
use Signifly\Shopify\Shopify;
use Illuminate\Support\Collection;
use Signifly\Shopify\Resources\ApiResource;

abstract class Action
{
    protected $guarded = [];

    protected $shopify;

    public function __construct(Shopify $shopify)
    {
        $this->shopify = $shopify;
    }

    public function all()
    {
        $response = $this->shopify->get($this->path());

        return $this->transformCollection($response[$this->getResourceKey()], $this->getResourceClass());
    }

    public function count()
    {
        return $this->shopify->get($this->path(null, 'count'));
    }

    public function create(array $data)
    {
        $key = Str::singular($this->getResourceKey());

        $response = $this->shopify->post($this->path(), [$key => $data]);

        return $this->transformItem($response[$key], $this->getResourceClass());
    }

    public function destroy($id)
    {
        return $this->shopify->delete($this->path($id));
    }

    public function find($id)
    {
        return $this->shopify->get($this->path());
    }

    public function update($id, array $data)
    {
        return $this->shopify->put($this->path($id), $data);
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

    protected function path($id = null, $appends = '', $prepends = '', $format = '.json')
    {
        $path = collect([$prepends, $this->getResourceKey(), $id, $appends])
            ->filter()
            ->implode('/');

        return $path . $format;
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
