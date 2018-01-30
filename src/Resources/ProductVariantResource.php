<?php

namespace Signifly\Shopify\Resources;

class ProductVariantResource extends ApiResource
{
    public function delete()
    {
        return $this->shopify->productVariants()->destroy($this->id);
    }

    public function update(array $data)
    {
        return $this->shopify->productVariants()->update($this->id, $data);
    }
}
