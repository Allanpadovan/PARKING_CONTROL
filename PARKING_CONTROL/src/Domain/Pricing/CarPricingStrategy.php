<?php

namespace App\Domain\Pricing;

class CarPricingStrategy extends BasePricingStrategy
{
    public function __construct()
    {
        parent::__construct(5.00);
    }
}