<?php

namespace Signifly\Shopify\Test;

use Signifly\Shopify\Shopify;

class ExampleTest extends TestCase
{
        /** @test */
        function it_can_reach_the_signifly_api()
        {
            $client = new Shopify(env('SHOPIFY_API_KEY'), env('SHOPIFY_API_SECRET'), env('SHOPIFY_HANDLE'));

            $response = $client->products()->count();

            $this->assertArrayHasKey('count', $response);
        }
}
