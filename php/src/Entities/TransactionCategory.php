<?php

namespace App\Entities;

class TransactionCategory
{
    private string $name;
    private float $expense;

    public function __construct(string $name, float $expense)
    {
        $this->name = $name;
        $this->expense = $expense;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getExpense(): float
    {
        return $this->expense;
    }

    public function setExpense(float $expense): void
    {
        $this->expense = $expense;
    }
}