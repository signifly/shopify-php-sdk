<?php

namespace Signifly\Shopify\Actions;

use Signifly\Shopify\Shopify;

abstract class Action
{
    protected $shopify;

    public function __construct(Shopify $shopify)
    {
        $this->shopify = $shopify;
    }
}
