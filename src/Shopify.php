<?php

namespace Signifly\Shopify;

use BadMethodCallException;
use Exception;
use GuzzleHttp\Client;
use Signifly\Shopify\Profiles\ProfileContract;
use Signifly\Shopify\Support\ActionFactory;
use Signifly\Shopify\Support\ResourceKey;

class Shopify
{
    use MakesHttpRequests;
    use PerformsActions;
    use VerifiesWebhooks;

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
    public function swap(ProfileContract $profile): self
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
            return ActionFactory::make(new ResourceKey($name), $this);
        } catch (Exception $e) {
        }

        throw new BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()',
            static::class,
            $name
        ));
    }
}
