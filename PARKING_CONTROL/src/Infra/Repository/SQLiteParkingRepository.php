<?php

namespace App\Infra\Repository;

use App\Domain\Entity\{ParkingRecord, Vehicle};
use App\Domain\Interfaces\ParkingRepositoryInterface;
use App\Infra\Database\Connection;

class SQLiteParkingRepository implements ParkingRepositoryInterface
{
    public function save(ParkingRecord $record): void
    {
        $pdo = Connection::get();

        if ($record->isActive()) {
            $stmt = $pdo->prepare("INSERT INTO records (plate, type, entry_time) VALUES (?, ?, ?)");
            $stmt->execute([
                $record->getVehicle()->getPlate(),
                $record->getVehicle()->getType(),
                $record->getEntryTime()->format('Y-m-d H:i:s')
            ]);
        } else {
            $stmt = $pdo->prepare("UPDATE records SET exit_time = ?, amount = ? WHERE plate = ? AND exit_time IS NULL");
            $stmt->execute([
                $record->getExitTime()->format('Y-m-d H:i:s'),
                $record->getAmount(),
                $record->getVehicle()->getPlate()
            ]);
        }
    }

    public function findActiveByPlate(string $plate): ?ParkingRecord
    {
        $pdo = Connection::get();
        $stmt = $pdo->prepare("SELECT * FROM records WHERE plate = ? AND exit_time IS NULL");
        $stmt->execute([$plate]);
        $data = $stmt->fetch();

        if (!$data) return null;

        $vehicle = new Vehicle($data['plate'], $data['type']);
        $record = new ParkingRecord($vehicle);
        $ref = new \ReflectionClass($record);
        $entryProp = $ref->getProperty('entryTime');
        $entryProp->setAccessible(true);
        $entryProp->setValue($record, new \DateTime($data['entry_time']));
        return $record;
    }

    public function getFinancialReport(): array
    {
        $pdo = Connection::get();
        $report = [
            'total' => 0.0,
            'carro' => ['count' => 0, 'revenue' => 0.0],
            'moto' => ['count' => 0, 'revenue' => 0.0],
            'caminhao' => ['count' => 0, 'revenue' => 0.0]
        ];

        $stmt = $pdo->query("SELECT type, amount FROM records WHERE amount IS NOT NULL");
        while ($row = $stmt->fetch()) {
            $type = $row['type'];
            $amount = (float)$row['amount'];
            if (isset($report[$type])) {
                $report[$type]['count']++;
                $report[$type]['revenue'] += $amount;
                $report['total'] += $amount;
            }
        }
        return $report;
    }
}