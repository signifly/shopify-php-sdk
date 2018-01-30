<?php

namespace Signifly\Shopify\Test;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Signifly\Shopify\Shopify;
use GuzzleHttp\Handler\MockHandler;
use Signifly\Shopify\Profiles\GuzzleMockHandlerProfile;

class ProductsTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_create_a_product()
    {
        $shopify = new Shopify($this->getGuzzleMockHandlerProfile());

        $data = [
            'title' => 'Burton Custom Freestyle 151',
            'body_html' => '<strong>Good snowboard!</strong>',
            'vendor' => 'Burton',
            'product_type' => 'Snowboard',
            'tags' => 'Barnes & Noble, John\'s Fav, "Big Air"',
        ];

        $response = $shopify->products()->create($data);

        $this->assertSame(1071559582, $response->id);
    }

    protected function getGuzzleMockHandlerProfile()
    {
        $responseJson = <<<EOT
            {
              "product": {
                "id": 1071559582,
                "title": "Burton Custom Freestyle 151",
                "body_html": "<strong>Good snowboard!</strong>",
                "vendor": "Burton",
                "product_type": "Snowboard",
                "created_at": "2018-01-10T12:55:08-05:00",
                "handle": "burton-custom-freestyle-151",
                "updated_at": "2018-01-10T12:55:08-05:00",
                "published_at": "2018-01-10T12:55:08-05:00",
                "template_suffix": null,
                "published_scope": "global",
                "tags": "\"Big Air\", Barnes & Noble, John's Fav",
                "variants": [
                  {
                    "id": 1070325028,
                    "product_id": 1071559582,
                    "title": "Default Title",
                    "price": "0.00",
                    "sku": "",
                    "position": 1,
                    "inventory_policy": "deny",
                    "compare_at_price": null,
                    "fulfillment_service": "manual",
                    "inventory_management": null,
                    "option1": "Default Title",
                    "option2": null,
                    "option3": null,
                    "created_at": "2018-01-10T12:55:08-05:00",
                    "updated_at": "2018-01-10T12:55:08-05:00",
                    "taxable": true,
                    "barcode": null,
                    "grams": 0,
                    "image_id": null,
                    "inventory_quantity": 1,
                    "weight": 0.0,
                    "weight_unit": "lb",
                    "inventory_item_id": 1070325030,
                    "old_inventory_quantity": 1,
                    "requires_shipping": true
                  }
                ],
                "options": [
                  {
                    "id": 1022828620,
                    "product_id": 1071559582,
                    "name": "Title",
                    "position": 1,
                    "values": [
                      "Default Title"
                    ]
                  }
                ],
                "images": [
                ],
                "image": null
              }
            }
EOT;

        $mock = new MockHandler([
            new Response(201, [], $responseJson),
        ]);

        return new GuzzleMockHandlerProfile(HandlerStack::create($mock));
    }
}
