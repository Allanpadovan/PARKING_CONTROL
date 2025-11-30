<?php

namespace App\Application;

use App\Domain\Entity\{ParkingRecord, Vehicle};
use App\Domain\Interfaces\{ParkingRepositoryInterface, PricingStrategyInterface};

class ParkingService
{
    private ParkingRepositoryInterface $repository;
    private array $strategies;

    public function __construct(ParkingRepositoryInterface $repository, array $strategies)
    {
        $this->repository = $repository;
        $this->strategies = $strategies;
    }

    public function registerEntry(string $plate, string $type): void
    {
        if (empty($plate) || empty($type)) {
            throw new \InvalidArgumentException("Placa e tipo são obrigatórios");
        }

        if ($this->repository->findActiveByPlate($plate)) {
            throw new \InvalidArgumentException("Veículo já está estacionado");
        }

        $vehicle = new Vehicle($plate, $type);
        $record = new ParkingRecord($vehicle);
        $this->repository->save($record);
    }

    public function registerExit(string $plate): float
    {
        $record = $this->repository->findActiveByPlate($plate);

        if (!$record) {
            throw new \InvalidArgumentException("Veículo não encontrado ou já saiu");
        }

        $type = $record->getVehicle()->getType();
        $strategy = $this->strategies[$type] ?? throw new \DomainException("Tarifa não configurada para $type");

        $record->setExitTime(new \DateTime());
        $record->calculateAmount($strategy);
        $this->repository->save($record);

        return $record->getAmount();
    }

    public function getFinancialReport(): array
    {
        return $this->repository->getFinancialReport();
    }
}