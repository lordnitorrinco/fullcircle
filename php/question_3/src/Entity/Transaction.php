<?php
namespace App\Entity;

class Transaction {
    private int $id;
    private float $amount;
    private string $date;
    private string $category;

    public function __construct(int $id, float $amount, string $date, string $category) {
        $this->id = $id;
        $this->amount = $amount;
        $this->date = $date;
        $this->category = $category;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getAmount(): float {
        return $this->amount;
    }

    public function getDate(): string {
        return $this->date;
    }

    public function getCategory(): string {
        return $this->category;
    }
}