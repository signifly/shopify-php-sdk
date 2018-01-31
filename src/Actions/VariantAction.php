<?php

namespace Signifly\Shopify\Actions;

use Illuminate\Support\Str;

class VariantAction extends Action
{
    protected $requiresParent = [
        'all',
        'count',
        'create',
        'destroy',
    ];
}
