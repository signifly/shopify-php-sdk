<?php

namespace Signifly\Shopify\Resources;

class ProductResource extends ApiResource
{
    public function delete()
    {
        return $this->shopify->products()->destroy($this->id);
    }

    public function update(array $data)
    {
        return $this->shopify->products()->update($this->id, $data);
    }
}
