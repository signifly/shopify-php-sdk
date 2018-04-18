<?php

namespace Signifly\Shopify\Profiles;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

class CredentialsProfile implements ProfileContract
{
    /**
     * The API key for Shopify private app.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * The API password for Shopify private app.
     *
     * @var string
     */
    protected $password;

    /**
     * The Shopify shop domain.
     *
     * @var string
     */
    protected $domain;

    /**
     * The HandlerStack for the Guzzle Client.
     *
     * @var \GuzzleHttp\HandlerStack
     */
    protected $handlerStack;

    /**
     * Set the credentials on the profile instance.
     *
     * @param string $apiKey
     * @param string $password
     * @param string $domain
     */
    public function __construct($apiKey, $password, $domain, $handlerStack = null)
    {
        $this->apiKey = $apiKey;
        $this->password = $password;
        $this->domain = $domain;
        $this->handlerStack = $handlerStack ?: HandlerStack::create();
    }

    /**
     * Get the Guzzle HTTP Client instance.
     *
     * @return \GuzzleHttp\Client
     */
    public function getClient() : Client
    {
        return new Client([
            'base_uri' => $this->getShopifyUrl(),
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'handler' => $this->handlerStack,
        ]);
    }

    /**
     * Returns the shopify api url.
     *
     * @return string
     */
    protected function getShopifyUrl() : string
    {
        if (! $this->domain) {
            return '';
        }

        return "https://{$this->apiKey}:{$this->password}@{$this->domain}/admin/";
    }

    /**
     * Returns the handlerStack.
     *
     * @return \GuzzleHttp\HandlerStack
     */
    public function getHandlerStack()
    {
        return $this->handlerStack;
    }

    /**
     * Sets the handlerStack.
     *
     * @param \GuzzleHttp\HandlerStack $handlerStack
     */
    public function setHandlerStack(HandlerStack $handlerStack)
    {
        $this->handlerStack = $handlerStack;

        return $this;
    }
}
