<?php

namespace Signifly\Shopify;

trait VerifiesWebhooks
{
    public function verifyWebhook(string $signature, string $data, string $secret) : bool
    {
        $computedSignature = base64_encode(hash_hmac('sha256', $data, $secret, true));

        return hash_equals($signature, $computedSignature);
    }
}
