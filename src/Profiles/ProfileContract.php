<?php

namespace Signifly\Shopify\Profiles;

use GuzzleHttp\Client;

interface ProfileContract
{
    public function getClient(): Client;
}
