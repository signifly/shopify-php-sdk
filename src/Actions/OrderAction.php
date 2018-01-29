<?php

namespace Signifly\Shopify\Actions;

class OrderAction extends Action
{
    public function cancel()
    {
        return $this->shopify->post($this->path($this->id, 'cancel'));
    }

    public function close()
    {
        return $this->shopify->post($this->path($this->id, 'close'));
    }

    public function open()
    {
        return $this->shopify->post($this->path($this->id, 'open'));
    }
}
