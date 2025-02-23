<?php

namespace App\Services\Transactions;

use App\Entities\Transaction;

class FindRecentTransactions
{
    /**
     * Finds transactions from the last three days.
     *
     * @param Transaction[] $transactions The input array of transactions.
     * @return Transaction[] An array of transactions from the last three days.
     */
    public function execute(array $transactions): array
    {
        $recentTransactions = [];
        $today = date('Y-m-d');
        $threeDaysAgo = date('Y-m-d', strtotime('-3 days'));

        foreach ($transactions as $transaction) {
            $transactionDate = $transaction->getDate();
            if ($transactionDate >= $threeDaysAgo && $transactionDate <= $today) {
                $recentTransactions[] = $transaction;
            }
        }

        return $recentTransactions;
    }
}