<?php

namespace Signifly\Shopify\Actions;

use Illuminate\Support\Str;
use Signifly\Shopify\Shopify;
use Illuminate\Support\Collection;

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
        return $this->shopify->post($this->path(), $data);
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
        $class = class_basename(get_called_class());

        return 'Signifly\\Shopify\\Resources\\' . substr($class, 0, -6) . 'Resource';
    }

    protected function getResourceKey()
    {
        $class = class_basename(get_called_class());

        return Str::snake(
            Str::plural(
                substr($class, 0, -6)
            )
        );
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
     * @param  array $extraData
     * @return array
     */
    protected function transformCollection(array $collection, string $class) : Collection
    {
        return collect($collection)->map(function ($attributes) use ($class) {
            return new $class($attributes, $this->shopify);
        });
    }
}
