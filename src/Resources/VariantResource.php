<?php

namespace Signifly\Shopify\Resources;

class VariantResource extends ApiResource
{
    public function delete()
    {
        return $this->shopify->variants()->destroy($this->id);
    }

    public function update(array $data)
    {
        return $this->shopify->variants()->update($this->id, $data);
    }
}
