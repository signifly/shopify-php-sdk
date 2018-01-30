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
    function it_can_reach_the_shopify_api()
    {
        $profile = new CredentialsProfile(env('SHOPIFY_API_KEY'), env('SHOPIFY_API_SECRET'), env('SHOPIFY_HANDLE'));
        $shopify = new Shopify($profile);

        $response = $shopify->products()->count();

        $this->assertArrayHasKey('count', $response);
    }
}
