<?php

namespace Signifly\Shopify\Profiles;

use GuzzleHttp\Client;

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
     * The Shopify shop handle.
     *
     * @var string
     */
    protected $handle;

    /**
     * Set the credentials on the profile instance.
     *
     * @param string $apiKey
     * @param string $password
     * @param string $handle
     */
    public function __construct($apiKey, $password, $handle)
    {
        $this->apiKey = $apiKey;
        $this->password = $password;
        $this->handle = $handle;
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
        ]);
    }

    /**
     * Returns the shopify api url.
     *
     * @return string
     */
    protected function getShopifyUrl() : string
    {
        return "https://{$this->apiKey}:{$this->password}@{$this->handle}.myshopify.com/admin/";
    }
}
