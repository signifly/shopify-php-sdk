<?php

namespace Signifly\Shopify\Test;

use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use Signifly\Shopify\Profiles\GuzzleMockHandlerProfile;

trait MocksGuzzleClient
{
    protected $mockHistory = null;

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

    protected function clearMockHistory()
    {
        $this->mockHistory = [];
    }
}
