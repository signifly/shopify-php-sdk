<?php

namespace Signifly\Shopify\Test;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use MocksGuzzleClient;

    protected function getFixture(string $filename)
    {
        return file_get_contents(__DIR__ . '//fixtures//' . $filename);
    }
}
