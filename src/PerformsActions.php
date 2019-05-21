<?php

namespace Signifly\Shopify;

use Signifly\Shopify\Resources\ShopResource;

trait PerformsActions
{
    public function locationInventoryLevels($id)
    {
        return $this->inventoryLevels()->with('locations', $id);
    }

    public function orderFulfillments($id)
    {
        return $this->fulfillments()->with('orders', $id);
    }

    public function orderTransactions($id)
    {
        return $this->transactions()->with('orders', $id);
    }

    public function productImages($id)
    {
        return $this->images()->with('products', $id);
    }

    public function productMetafields($id)
    {
        return $this->metafields()->with('products', $id);
    }

    public function productVariants($id)
    {
        return $this->variants()->with('products', $id);
    }

    public function shop()
    {
        $response = $this->get('shop.json');

        return new ShopResource($response['shop'], $this);
    }

    public function variantMetafields($id)
    {
        return $this->metafields()->with('variants', $id);
    }
}
