<?php

namespace Signifly\Shopify\Test;

use Signifly\Shopify\Shopify;

class ProductsTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_create_a_product()
    {
        $profile = $this->makeGuzzleMockHandlerProfile([
            $this->makeGuzzleResponse(201, [], $this->getFixture('products.json')),
        ]);
        $shopify = new Shopify($profile);

        $data = [
            'title' => 'Burton Custom Freestyle 151',
            'body_html' => '<strong>Good snowboard!</strong>',
            'vendor' => 'Burton',
            'product_type' => 'Snowboard',
            'tags' => 'Barnes & Noble, John\'s Fav, "Big Air"',
        ];

        $response = $shopify->products()->create($data);

        $this->assertSame(1071559582, $response->id);
        $this->assertCount(1, $this->mockHistory);
        $this->assertSame('products.json', $this->mockHistory[0]['request']->getUri()->getPath());
    }
}
