<?php

namespace Signifly\Shopify\Actions;

class LocationAction extends Action
{
    public function inventoryLevels($id)
    {
        return $this->shopify->locationInventoryLevels($id)->all();
    }
}
