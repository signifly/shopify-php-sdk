<?php

namespace Signifly\Shopify\Actions;

class TransactionAction extends Action
{
    protected $requiresParent = [
        'all',
        'count',
        'create',
        'find',
    ];
}
