<?php

namespace Signifly\Shopify;

use Exception;
use GuzzleHttp\Client;
use Signifly\Shopify\Actions\ActionFactory;

class Shopify
{
    use MakesHttpRequests;

    /**
     * API Key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * API Secret
     *
     * @var string
     */
    protected $apiSecret;

    /**
     * The Guzzle HTTP Client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Shopify shop handle.
     *
     * @var string
     */
    protected $handle;

    /**
     * Create a new Shopify instance.
     *
     * @param  \GuzzleHttp\Client $client
     * @return void
     */
    public function __construct(string $apiKey, string $apiSecret, string $handle, Client $client = null)
    {
        $this->setCredentials($apiKey, $apiSecret, $handle);

        $this->client = $client ?: new Client([
            'base_uri' => $this->getShopifyUrl(),
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    /**
     * Returns the shopify api url.
     *
     * @return string
     */
    protected function getShopifyUrl() : string
    {
        return "https://{$this->apiKey}:{$this->apiSecret}@{$this->handle}.myshopify.com/admin/";
    }

    /**
     * Set the credentials on the Shopify instance.
     *
     * @param string $apiKey
     * @param string $apiSecret
     * @param string $handle
     */
    public function setCredentials($apiKey, $apiSecret, $handle) : self
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->handle = $handle;

        return $this;
    }

    /**
     * @param  string $name
     * @param  array $arguments
     * @return \Signifly\Shopify\Actions\Action
     */
    public function __call($name, $arguments)
    {
        try {
            return (new ActionFactory($name, $this))->make();
        } catch (Exception $e) {
            //
        }

        throw new Exception("Method {$name} does not exist");
    }
}
