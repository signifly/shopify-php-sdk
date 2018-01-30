<?php

namespace Signifly\Shopify\Test;

use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Signifly\Shopify\Profiles\GuzzleMockHandlerProfile;

abstract class TestCase extends BaseTestCase
{
    protected $mockHistory = null;

    protected function getFixture(string $filename)
    {
        return file_get_contents(__DIR__ . '//fixtures//' . $filename);
    }

    protected function makeGuzzleResponse(int $status = 200, array $headers = [],
        string $body = null)
    {
        return new Response($status, $headers, $body);
    }

    protected function makeGuzzleMockHandlerProfile(array $queue = null)
    {
        $this->mockHistory = [];
        $history = Middleware::history($this->mockHistory);

        $stack = HandlerStack::create(new MockHandler($queue));
        $stack->push($history);

        return new GuzzleMockHandlerProfile($stack);
    }
}
