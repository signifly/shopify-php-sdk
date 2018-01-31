<?php

namespace Signifly\Shopify\Resources;

class ImageResource extends ApiResource
{
    public function delete()
    {
        return $this->shopify->images()->destroy($this->id);
    }

    public function update(array $data)
    {
        return $this->shopify->images()->update($this->id, $data);
    }
}
