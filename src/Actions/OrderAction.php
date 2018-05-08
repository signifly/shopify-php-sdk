<?php

namespace Signifly\Shopify\Actions;

class OrderAction extends Action
{
    public function cancel($id)
    {
        return $this->shopify->post($this->path($id)->appends('cancel'));
    }

    public function close($id)
    {
        return $this->shopify->post($this->path($id)->appends('close'));
    }

    public function open($id)
    {
        return $this->shopify->post($this->path($id)->('open'));
    }
}
