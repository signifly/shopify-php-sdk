<?php

namespace Signifly\Shopify\Resources;

class LocationResource extends ApiResource
{
    public function inventoryLevels()
    {
        return $this->shopify->locationInventoryLevels($this->id)->all();
    }
}
