<?php

namespace App\Controllers;

use App\Services\Transactions\CalculateBalance;
use App\Services\Transactions\FindMaxExpenseCategory;
use App\Services\Transactions\FindRecentTransactions;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

class TransactionController
{
    private CalculateBalance $calculateBalance;
    private FindMaxExpenseCategory $findMaxExpenseCategory;
    private FindRecentTransactions $findRecentTransactions;

    public function __construct()
    {
        $this->calculateBalance = new CalculateBalance();
        $this->findMaxExpenseCategory = new FindMaxExpenseCategory();
        $this->findRecentTransactions = new FindRecentTransactions();
    }

    public function analyzeTransactions(array $transactions): void
    {
        $output = new ConsoleOutput();
        // Output the transactions in a table format
        $table = new Table($output);
        $table->setHeaders(['Transaction ID', 'Amount', 'Date', 'Category']);

        foreach ($transactions as $transaction) {
            $table->addRow([
            $transaction->getId(),
            $transaction->getAmount(),
            $transaction->getDate(),
            $transaction->getCategory()
            ]);
        }

        $table->render();

        // Analyze transactions and output the result
        $balance = $this->calculateBalance->execute($transactions);
        $maxExpenseCategory = $this->findMaxExpenseCategory->execute($transactions);
        $recentTransactions = $this->findRecentTransactions->execute($transactions);

        // Output the balance and max expense category in a table format
        $summaryTable = new Table($output);
        $summaryTable->setHeaders(['Total balance']);
        $summaryTable->addRow([$balance]);
        $summaryTable->render();
  
        // Output the balance and max expense category in a table format
        $summaryTable = new Table($output);
        $summaryTable->setHeaders(['Category with highest expense', 'Expense']);
        $summaryTable->addRow([$maxExpenseCategory->getName(), $maxExpenseCategory->getExpense()]);
        $summaryTable->render();

        // Output recent transactions in a table format
        $recentTable = new Table($output);
        $recentTable->setHeaders(['Recent transaction ID', 'Amount', 'Date', 'Category']);

        foreach ($recentTransactions as $transaction) {
            $recentTable->addRow([
            $transaction->getId(),
            $transaction->getAmount(),
            $transaction->getDate(),
            $transaction->getCategory()
            ]);
        }

        $recentTable->render();
    }
}