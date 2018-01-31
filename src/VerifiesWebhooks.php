<?php

namespace Signifly\Shopify;

trait VerifiesWebhooks
{
    public function verifyWebhook(string $hmacHeader, string $data, string $secret) : bool
    {
        $calculatedHmac = base64_encode(hash_hmac('sha256', $data, $secret, true));

        return hash_equals($hmacHeader, $calculatedHmac);
    }
}
