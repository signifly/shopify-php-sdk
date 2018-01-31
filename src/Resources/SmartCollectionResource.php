<?php

namespace Signifly\Shopify\Resources;

class SmartCollectionResource extends ApiResource
{
    public function delete()
    {
        return $this->shopify->smartCollections()->destroy($this->id);
    }

    public function update(array $data)
    {
        return $this->shopify->smartCollections()->update($this->id, $data);
    }
}
