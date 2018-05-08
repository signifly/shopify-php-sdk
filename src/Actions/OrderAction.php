<?php

namespace Signifly\Shopify\Actions;

class OrderAction extends Action
{
    public function cancel($id)
    {
        $this->guardAgainstMissingParent('cancel');

        $response = $this->shopify->post($this->path($id)->appends('cancel'));

        return $this->transformItemFromResponse($response);
    }

    public function close($id)
    {
        $this->guardAgainstMissingParent('close');

        $response = $this->shopify->post($this->path($id)->appends('close'));

        return $this->transformItemFromResponse($response);
    }

    public function open($id)
    {
        $this->guardAgainstMissingParent('open');

        $response = $this->shopify->post($this->path($id)->appends('open'));

        return $this->transformItemFromResponse($response);
    }
}
