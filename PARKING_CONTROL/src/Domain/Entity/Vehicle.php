<?php

namespace App\Domain\Entity;

class Vehicle
{
    private string $plate;
    private string $type;

    public function __construct(string $plate, string $type)
    {
        $this->plate = strtoupper($plate);
        $this->type = $type;
    }

    public function getPlate(): string
    {
        return $this->plate;
    }

    public function getType(): string
    {
        return $this->type;
    }
}