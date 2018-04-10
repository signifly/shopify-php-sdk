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
     * The Shopify shop domain.
     *
     * @var string
     */
    protected $domain;

    /**
     * Set the credentials on the profile instance.
     *
     * @param string $apiKey
     * @param string $password
     * @param string $domain
     */
    public function __construct($apiKey, $password, $domain)
    {
        $this->apiKey = $apiKey;
        $this->password = $password;
        $this->domain = $domain;
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
        if (! $this->domain) {
            return '';
        }

        return "https://{$this->apiKey}:{$this->password}@{$this->domain}/admin/";
    }
}
