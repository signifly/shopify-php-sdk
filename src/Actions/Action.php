<?php

namespace Signifly\Shopify\Actions;

use Illuminate\Support\Str;
use Signifly\Shopify\Shopify;

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
        return $this->shopify->get($this->path());
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

    protected function getResourceKey()
    {
        $class = class_basename(get_class());

        return Str::snake(
            Str::plural(
                rtrim($class, 'Action')
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
}
