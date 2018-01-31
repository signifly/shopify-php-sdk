<?php

namespace Signifly\Shopify\Resources;

class WebhookResource extends ApiResource
{
    public function delete()
    {
        return $this->shopify->variants()->destroy($this->id);
    }

    public function update(array $data)
    {
        return $this->shopify->variants()->update($this->id, $data);
    }
}
