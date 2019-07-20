<?php

namespace Signifly\Shopify\Test\Profiles;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Signifly\Shopify\Profiles\ProfileContract;

class GuzzleMockHandlerProfile implements ProfileContract
{
    /**
     * The Guzzle Handler Stack.
     *
     * @var \Guzzle\HandlerStack
     */
    protected $handlerStack;

    /**
     * Provide the handler stack for the profile instance.
     *
     * @param \Guzzle\HandlerStack $handlerStack
     */
    public function __construct(HandlerStack $handlerStack)
    {
        $this->handlerStack = $handlerStack;
    }

    /**
     * Get the Guzzle HTTP Client instance.
     *
     * @return \GuzzleHttp\Client
     */
    public function getClient(): Client
    {
        return new Client([
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'handler' => $this->handlerStack,
        ]);
    }
}
