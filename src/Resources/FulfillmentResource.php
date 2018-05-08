<?php

namespace Signifly\Shopify\Resources;

class FulfillmentResource extends ApiResource
{
    public function cancel()
    {
        return $this->shopify->orderFulfillments($this->order_id)->cancel($this->id);
    }

    public function complete()
    {
        return $this->shopify->orderFulfillments($this->order_id)->complete($this->id);
    }

    public function open()
    {
        return $this->shopify->orderFulfillments($this->order_id)->open($this->id);
    }
}
