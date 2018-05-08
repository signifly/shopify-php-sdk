<?php

namespace Signifly\Shopify\Actions;

class FulfillmentAction extends Action
{
    protected $requiresParent = [
        '*',
    ];

    public function cancel($id)
    {
        $this->guardAgainstMissingParent('cancel');

        $response = $this->shopify->post($this->path($id)->appends('cancel'));

        return $this->transformItemFromResponse($response);
    }

    public function complete($id)
    {
        $this->guardAgainstMissingParent('complete');

        $response = $this->shopify->post($this->path($id)->appends('complete'));

        return $this->transformItemFromResponse($response);
    }

    public function open($id)
    {
        $this->guardAgainstMissingParent('open');

        $response = $this->shopify->post($this->path($id)->appends('open'));

        return $this->transformItemFromResponse($response);
    }
}
