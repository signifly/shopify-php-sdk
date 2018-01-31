<?php

namespace Signifly\Shopify\Test;

use Signifly\Shopify\Shopify;
use Signifly\Shopify\Profiles\CredentialsProfile;

class ExampleTest extends TestCase
{
    /**
     * @test
     * @group integration
     */
    public function it_can_reach_the_shopify_api()
    {
        $shopify = new Shopify(
            new CredentialsProfile(
                env('SHOPIFY_API_KEY'),
                env('SHOPIFY_PASSWORD'),
                env('SHOPIFY_HANDLE')
            )
        );

        $response = $shopify->products()->count();

        $this->assertInternalType('integer', $response);
    }
}
