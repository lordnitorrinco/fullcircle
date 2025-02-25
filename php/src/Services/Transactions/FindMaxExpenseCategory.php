<?php
namespace App\Services\Transactions;

use App\Entities\Transaction;
use App\Entities\TransactionCategory;

class FindMaxExpenseCategory
{
    /**
     * Finds the category with the highest expense.
     *
     * @param Transaction[] $transactions The input array of transactions.
     * @return TransactionCategory|null The category with the highest expense, or null if there are no expenses.
     */
    public function execute(array $transactions): ?TransactionCategory
    {
        $categoryExpenses = [];
        foreach ($transactions as $transaction) {
            if ($transaction->getAmount() < 0) {
                if (!isset($categoryExpenses[$transaction->getCategory()])) {
                    $categoryExpenses[$transaction->getCategory()] = 0;
                }
                $categoryExpenses[$transaction->getCategory()] += abs($transaction->getAmount());
            }
        }

        $maxExpenseCategory = null;
        $maxExpense = PHP_INT_MIN;
        foreach ($categoryExpenses as $category => $expense) {
            if ($expense > $maxExpense) {
                $maxExpense = $expense;
                $maxExpenseCategory = $category;
            }
        }

        if ($maxExpenseCategory !== null) {
            return new TransactionCategory($maxExpenseCategory, $maxExpense);
        }

        return null;
    }
}