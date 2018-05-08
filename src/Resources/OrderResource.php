<?php

namespace Signifly\Shopify\Resources;

class OrderResource extends ApiResource
{
    public function delete()
    {
        return $this->shopify->orders()->destroy($this->id);
    }

    public function update(array $data)
    {
        return $this->shopify->orders()->update($this->id, $data);
    }

    public function cancel()
    {
        return $this->shopify->orders()->cancel($this->id);
    }

    public function close()
    {
        return $this->shopify->orders()->close($this->id);
    }

    public function open()
    {
        return $this->shopify->orders()->open($this->id);
    }

    public function fulfillments()
    {
        return $this->shopify->orderFulfillments($this->id);
    }
}
