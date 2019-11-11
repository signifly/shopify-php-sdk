<?php

namespace Signifly\Shopify\Test;

use Signifly\Shopify\Profiles\CredentialsProfile;
use Signifly\Shopify\Resources\ShopResource;
use Signifly\Shopify\Shopify;

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
                env('SHOPIFY_DOMAIN'),
                '2019-10'
            )
        );

        $shopResource = $shopify->shop();

        $this->assertInstanceOf(ShopResource::class, $shopResource);
    }
}
