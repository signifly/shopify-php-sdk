<?php

namespace Signifly\Shopify\RateLimit;

interface RateLimitCalculatorContract
{
    /**
     * Calculate the time in seconds between each request.
     *
     * @param  int    $callsMade
     * @param  int    $callsLimit
     * @return float|int
     */
    public function calculate(int $callsMade, int $callsLimit);
}
