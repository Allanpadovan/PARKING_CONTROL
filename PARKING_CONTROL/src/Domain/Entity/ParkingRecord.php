<?php

namespace App\Domain\Entity;

use App\Domain\Interfaces\PricingStrategyInterface;

class ParkingRecord
{
    private Vehicle $vehicle;
    private \DateTime $entryTime;
    private ?\DateTime $exitTime = null;
    private ?float $amount = null;

    public function __construct(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
        $this->entryTime = new \DateTime();
    }

    public function getVehicle(): Vehicle { return $this->vehicle; }
    public function getEntryTime(): \DateTime { return $this->entryTime; }
    public function getExitTime(): ?\DateTime { return $this->exitTime; }
    public function getAmount(): ?float { return $this->amount; }

    public function setExitTime(\DateTime $exitTime): void
    {
        $this->exitTime = $exitTime;
    }

    public function calculateAmount(PricingStrategyInterface $strategy): void
    {
        $hours = $this->getHoursParked();
        $this->amount = $strategy->calculate($hours);
    }

    public function getHoursParked(): int
    {
        if (!$this->exitTime) return 0;
        $diff = $this->exitTime->getTimestamp() - $this->entryTime->getTimestamp();
        $hours = ceil($diff / 3600);
        return max(1, (int)$hours); // mínimo 1 hora
    }

    public function isActive(): bool
    {
        return $this->exitTime === null;
    }
}