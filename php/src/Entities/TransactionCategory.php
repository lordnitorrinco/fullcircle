<?php

namespace App\Entities;

class TransactionCategory
{
    private int $id;
    private string $name;
    private float $expense;

    public function __construct(int $id, string $name, float $expense)
    {
        $this->id = $id;
        $this->name = $name;
        $this->expense = $expense;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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