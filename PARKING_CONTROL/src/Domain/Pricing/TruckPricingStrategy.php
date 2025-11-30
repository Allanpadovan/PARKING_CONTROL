<?php

namespace App\Domain\Pricing;

class TruckPricingStrategy extends BasePricingStrategy
{
    public function __construct()
    {
        parent::__construct(10.00);
    }
}