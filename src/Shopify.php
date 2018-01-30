<?php

namespace Signifly\Shopify;

use Exception;
use GuzzleHttp\Client;
use Signifly\Shopify\Actions\ActionFactory;
use Signifly\Shopify\Profiles\ProfileContract;

class Shopify
{
    use MakesHttpRequests;

    /**
     * The Guzzle HTTP Client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Create a new Shopify instance.
     *
     * @param  \GuzzleHttp\Client $client
     * @return void
     */
    public function __construct(ProfileContract $profile)
    {
        $this->swap($profile);
    }

    /**
     * Swap the Guzzle HTTP Client instance.
     *
     * @param  ProfileContract $profile
     * @return self
     */
    public function swap(ProfileContract $profile) : self
    {
        $this->client = $profile->getClient();

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
