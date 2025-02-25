<?php

namespace App\Entities;

class Transaction
{
    private float $amount;
    private string $date;
    private string $category;

    public function __construct(float $amount, string $date, string $category)
    {
        $this->amount = $amount;
        $this->date = $date;
        $this->category = $category;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getCategory(): string
    {
        return $this->category;
    }
}