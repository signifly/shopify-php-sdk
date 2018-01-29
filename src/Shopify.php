<?php

namespace Signifly\Shopify;

use Exception;
use GuzzleHttp\Client;
use Signifly\Shopify\Actions\ActionFactory;

class Shopify
{
    use MakesHttpRequests;

    /**
     * The Guzzle HTTP Client instance.
     *
     * @var \GuzzleHttp\Client
     */
    public $client;

    /**
     * Create a new Shopify instance.
     *
     * @param  \GuzzleHttp\Client $client
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
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
