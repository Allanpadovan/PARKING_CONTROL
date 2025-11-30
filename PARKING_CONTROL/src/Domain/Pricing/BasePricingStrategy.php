<?php

namespace App\Domain\Pricing;

use App\Domain\Interfaces\PricingStrategyInterface;

abstract class BasePricingStrategy implements PricingStrategyInterface
{
    protected float $rate;

    public function __construct(float $rate)
    {
        $this->rate = $rate;
    }

    public function calculate(int $hours): float
    {
        return $hours * $this->rate;
    }
}