<?php

namespace Signifly\Shopify\Test;

use Signifly\Shopify\Profiles\CredentialsProfile;
use Signifly\Shopify\Resources\ShopResource;
use Signifly\Shopify\Shopify;
use Signifly\Shopify\Support\Cursor;

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

    /**
     * @test
     * @group integration
     */
    public function it_paginates_results()
    {
        $shopify = new Shopify(
            new CredentialsProfile(
                env('SHOPIFY_API_KEY'),
                env('SHOPIFY_PASSWORD'),
                env('SHOPIFY_DOMAIN'),
                '2019-10'
            )
        );

        $count = $shopify->products()->count();
        $cursor = $shopify->products()->paginate(['limit' => 250]);

        $results = collect();

        while ($cursor->valid()) {
            $results = $results->merge($cursor->current());

            $cursor->next();
        }

        $this->assertInstanceOf(Cursor::class, $cursor);
        $this->assertEquals($count, $results->count());
    }
}
