<?php
namespace App\Repository;

use App\Entity\Transaction;

/**
 * Class TransactionRepository
 *
 * This repository provides a method to fetch a list of transactions.
 *
 * @package App\Repository
 */
class TransactionRepository {
    /**
     * Fetches a list of transactions.
     *
     * @return Transaction[] An array of Transaction objects.
     */
    public function getTransactions(): array {
        return [
            new Transaction(1, -50, '2025-02-19', 'groceries'),
            new Transaction(2, -20, '2025-02-18', 'fun'),
            new Transaction(3, 100, '2025-02-17', 'salary'),
            new Transaction(4, -10, '2025-02-20', 'groceries'),
        ];
    }
}