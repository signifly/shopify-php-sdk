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
        $this->apiKey = $apiKey;

        $this->apiSecret = $apiSecret;

        $this->handle = $handle;

        $this->client = $client ?: new Client([
            'base_uri' => $this->getShopifyUrl(),
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ];
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
     * Transform the items of the collection to the given class.
     *
     * @param  array $collection
     * @param  string $class
     * @param  array $extraData
     * @return array
     */
    protected function transformCollection(array $collection, string $class) : array
    {
        return array_map(function ($attributes) use ($class) {
            return new $class($attributes, $this);
        }, $collection);
    }

    /**
     * @param  string $name
     * @param  array $arguments
     * @return \Signifly\Shopify\Actions\Action
     */
    public function __call($name, ...$arguments)
    {
        try {
            return (new ActionFactory($name))->make();
        } catch (Exception $e) {
            //
        }

        throw new Exception('Method does not exist');
    }
}
