<?php

namespace App\Entities;

class Order
{
    private string $status;
    private float $amount;

    public function __construct(string $status, float $amount)
    {
        $this->status = $status;
        $this->amount = $amount;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}