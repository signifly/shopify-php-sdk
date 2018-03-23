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

    public function images()
    {
        return $this->shopify->productImages($this->id);
    }

    public function metafields()
    {
        return $this->shopify->productMetafields($this->id);
    }

    public function variants()
    {
        return $this->shopify->productVariants($this->id);
    }
}
