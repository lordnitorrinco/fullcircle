<?php

namespace App\Entities;

class Order
{
    private int $id;
    private string $status;
    private float $total;

    public function __construct(int $id, string $status, float $total)
    {
        $this->id = $id;
        $this->status = $status;
        $this->total = $total;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}