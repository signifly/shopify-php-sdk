<?php

namespace Signifly\Shopify\Test\Unit\Resources;

use Signifly\Shopify\Resources\ProductResource;
use Signifly\Shopify\Resources\VariantResource;
use Signifly\Shopify\Shopify;
use Signifly\Shopify\Test\TestCase;

class ProductResourceTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_create_a_product_variant()
    {
        $profile = $this->makeGuzzleMockHandlerProfile([
            $this->makeGuzzleResponse(201, [], $this->getFixture('variant.json')),
        ]);
        $shopify = new Shopify($profile);
        $product = new ProductResource(['id' => 1234], $shopify);

        $data = [
            'option1' => 'Yellow',
            'price' => '1.00',
        ];

        $response = $product->variants()->create($data);

        $this->assertInstanceOf(VariantResource::class, $response);
        $this->assertSame(1070325021, $response->id);
        $this->assertCount(1, $this->mockHistory);
        $this->assertSame('products/1234/variants.json', $this->getLatestMockHistoryPath());
    }
}
