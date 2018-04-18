<?php

namespace Signifly\Shopify\RateLimit;

class DefaultRateLimitCalculator implements RateLimitCalculatorContract
{
    /**
     * The buffer from the max calls limit.
     *
     * @var int
     */
    protected $buffer;

    /**
     * The request cycle.
     *
     * @var float
     */
    protected $cycle;

    /**
     * The processes that can run in parallel.
     *
     * @var int
     */
    protected $processes;

    /**
     * Create a RateLimitCalculator instance.
     *
     * @param int $buffer
     * @param float $cycle
     * @param int $processes
     */
    public function __construct(int $buffer, float $cycle, int $processes)
    {
        $this->buffer = $buffer;
        $this->cycle = $cycle;
        $this->processes = $processes;
    }

    /**
     * Calculate the time in seconds between each request.
     *
     * @param  int    $callsMade
     * @param  int    $callsLimit
     * @return float|int
     */
    public function calculate(int $callsMade, int $callsLimit)
    {
        $callPercentage = floatval($callsMade / $callsLimit);
        $limitPercentage = floatval(($callsLimit - $this->processes - $this->buffer) / $callsLimit);
        return ($callPercentage > $limitPercentage ? ($this->processes * $this->cycle) : $this->cycle);
    }
}
