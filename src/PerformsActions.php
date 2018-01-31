<?php

namespace Signifly\Shopify;

trait PerformsActions
{
    public function productImages($id)
    {
        return $this->images()->with('products', $id);
    }

    public function productVariants($id)
    {
        return $this->variants()->with('products', $id);
    }
}
