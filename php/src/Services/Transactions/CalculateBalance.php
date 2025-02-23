<?php

namespace App\Services\Transactions;

use App\Entities\Transaction;

class CalculateBalance
{
    /**
     * Calculates the total balance of all transactions.
     *
     * @param Transaction[] $transactions The input array of transactions.
     * @return float The total balance of all transactions.
     */
    public function execute(array $transactions): float
    {
        $balance = 0;
        foreach ($transactions as $transaction) {
            $balance += $transaction->getAmount();
        }
        return $balance;
    }
}