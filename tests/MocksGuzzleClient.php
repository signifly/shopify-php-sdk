<?php

namespace Signifly\Shopify\Test;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Signifly\Shopify\Test\Profiles\GuzzleMockHandlerProfile;

trait MocksGuzzleClient
{
    protected $mockHistory = null;

    protected function makeGuzzleResponse(
        int $status = 200,
        array $headers = [],
        string $body = null
    ) {
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

    protected function getLatestMockHistoryRequest()
    {
        return array_last($this->mockHistory)['request'];
    }

    protected function getLatestMockHistoryPath()
    {
        return $this->getLatestMockHistoryRequest()->getUri()->getPath();
    }
}
