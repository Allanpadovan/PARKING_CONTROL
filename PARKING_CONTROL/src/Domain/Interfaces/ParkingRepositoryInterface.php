<?php

namespace App\Domain\Interfaces;

use App\Domain\Entity\ParkingRecord;

interface ParkingRepositoryInterface
{
    public function save(ParkingRecord $record): void;
    public function findActiveByPlate(string $plate): ?ParkingRecord;
    public function getFinancialReport(): array;
}