<?php

namespace Signifly\Shopify\Actions;

class InventoryLevelAction extends Action
{
    public function adjust(array $data)
    {
        $this->guardAgainstMissingParent('adjust');

        $response = $this->shopify->post($this->path()->appends('adjust'), $data);

        return $this->transformItemFromResponse($response);
    }

    public function connect(array $data)
    {
        $this->guardAgainstMissingParent('connect');

        $response = $this->shopify->post($this->path()->appends('connect'), $data);

        return $this->transformItemFromResponse($response);
    }

    public function set(array $data)
    {
        $this->guardAgainstMissingParent('set');

        $response = $this->shopify->post($this->path()->appends('set'), $data);

        return $this->transformItemFromResponse($response);
    }
}
