<?php

namespace App\Domain\Pricing;

class BikePricingStrategy extends BasePricingStrategy
{
    public function __construct()
    {
        parent::__construct(3.00);
    }
}