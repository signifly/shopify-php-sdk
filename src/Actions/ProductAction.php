<?php

namespace Signifly\Shopify\Actions;

class ProductAction extends Action
{
    public function all()
    {
        return $this->shopify->get("products.json");
    }

    public function count()
    {
        return $this->get("products/count.json");
    }

    public function create(array $data)
    {
        return $this->post("products.json", $data);
    }

    public function destroy($id)
    {
        return $this->delete("products/{$id}.json");
    }

    public function find($id)
    {
        return $this->get("products/{$id}.json");
    }

    public function update($id, array $data)
    {
        return $this->put("products/{$id}.json", $data);
    }
}
