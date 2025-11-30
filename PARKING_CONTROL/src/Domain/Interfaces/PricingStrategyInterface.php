<?php

namespace App\Domain\Interfaces;

interface PricingStrategyInterface
{
    public function calculate(int $hours): float;
}