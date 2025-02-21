<?php
require __DIR__ . '/vendor/autoload.php';

use App\Repository\TransactionRepository;
use App\Controller\TransactionController;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

// Instantiate the repository and controller
$repository = new TransactionRepository();
$controller = new TransactionController($repository);

// Analyze transactions and get the summary
$summary = $controller->analyzeTransactions();

// Create a console output
$output = new ConsoleOutput();

// Display the summary in a table
$output->writeln("<info>Summary:</info>");
$summaryTable = new Table($output);
$summaryTable
    ->setHeaders(['Metric', 'Value'])
    ->setRows([
        ['Balance', $summary['balance']],
        ['Category with highest expense', $summary['maxExpenseCategory']],
    ]);
$summaryTable->render();

// Display recent transactions in a table
$output->writeln("\n<info>Recent transactions:</info>");
$transactionsTable = new Table($output);
$transactionsTable
    ->setHeaders(['Amount', 'Date', 'Category'])
    ->setRows(array_map(function($transaction) {
        return [
            $transaction->getAmount(),
            $transaction->getDate(),
            $transaction->getCategory()
        ];
    }, $summary['recentTransactions']));
$transactionsTable->render();