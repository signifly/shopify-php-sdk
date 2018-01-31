<?php

namespace Signifly\Shopify\Actions;

class VariantAction extends Action
{
    protected $requiresParent = [
        'all',
        'count',
        'create',
        'destroy',
    ];
}
