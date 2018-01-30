<?php

namespace Signifly\Shopify\Test;

use Signifly\Shopify\Shopify;

class ExampleTest extends TestCase
{
    /**
     * @test
     * @group integration
     */
    public function it_can_reach_the_shopify_api()
    {
        $shopify = new Shopify(env('SHOPIFY_API_KEY'), env('SHOPIFY_API_SECRET'), env('SHOPIFY_HANDLE'));

        $response = $shopify->products()->count();

        $this->assertArrayHasKey('count', $response);
    }
}
