<?php

namespace Signifly\Shopify\Actions;

class ImageAction extends Action
{
    protected $requiresParent = [
        '*',
    ];

    /**
     * Create an image from a src.
     *
     * @param  string $src
     * @return \Signifly\Shopify\Resources\ImageResource
     */
    public function createFromSrc($src)
    {
        return $this->create(compact('src'));
    }
}
