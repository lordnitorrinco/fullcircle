<?php
namespace App\Controller;

use App\Repository\TransactionRepository;

/**
 * Class TransactionController
 *
 * This controller handles the analysis of transactions to calculate balance,
 * category expenses, and recent transactions.
 *
 * @package App\Controller
 */
class TransactionController {
    /**
     * @var TransactionRepository The repository to fetch transactions.
     */
    private TransactionRepository $repository;

    /**
     * Constructor for TransactionController.
     *
     * @param TransactionRepository $repository The repository to fetch transactions.
     */
    public function __construct(TransactionRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * Analyzes transactions to calculate balance, category expenses, and recent transactions.
     *
     * @return array An associative array containing:
     *               - 'balance': The total balance of all transactions.
     *               - 'maxExpenseCategory': The category with the highest expense.
     *               - 'recentTransactions': An array of transactions from the last three days.
     */
    public function analyzeTransactions(): array {
        $transactions = $this->repository->getTransactions();
        $balance = 0;
        $categoryExpenses = [];
        $recentTransactions = [];

        $today = date('Y-m-d');
        $threeDaysAgo = date('Y-m-d', strtotime('-3 days'));

        foreach ($transactions as $transaction) {
            // Update balance
            $balance += $transaction->getAmount();
            
            // Track expenses by category
            if ($transaction->getAmount() < 0) {
                if (!isset($categoryExpenses[$transaction->getCategory()])) {
                    $categoryExpenses[$transaction->getCategory()] = 0;
                }
                $categoryExpenses[$transaction->getCategory()] += abs($transaction->getAmount());
            }
            
            // Collect recent transactions within the last three days
            $transactionDate = $transaction->getDate();
            if ($transactionDate >= $threeDaysAgo && $transactionDate <= $today) {
                $recentTransactions[] = $transaction;
            }
        }

        // Determine the category with the maximum expense
        $maxExpenseCategory = null;
        $maxExpense = PHP_INT_MIN;
        foreach ($categoryExpenses as $category => $expense) {
            if ($expense > $maxExpense) {
                $maxExpense = $expense;
                $maxExpenseCategory = $category;
            }
        }

        return [
            'balance' => $balance,
            'maxExpenseCategory' => $maxExpenseCategory,
            'recentTransactions' => $recentTransactions
        ];
    }
}