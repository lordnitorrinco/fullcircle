<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Repository\TransactionRepository;
use App\Controller\TransactionController;

// Instantiate the repository and controller
$repository = new TransactionRepository();
$controller = new TransactionController($repository);

// Analyze transactions and get the summary
$summary = $controller->analyzeTransactions();

// Display the summary in a table
echo "<table border='1'>";
echo "<tr><th>Balance</th><td>" . $summary['balance'] . "</td></tr>";
echo "<tr><th>Category with highest expense</th><td>" . $summary['maxExpenseCategory'] . "</td></tr>";
echo "</table>";

// Display recent transactions in a table
echo "<h3>Recent transactions:</h3>";
echo "<table border='1'>";
echo "<tr><th>Amount</th><th>Date</th><th>Category</th></tr>";
foreach ($summary['recentTransactions'] as $transaction) {
    echo "<tr>";
    echo "<td>" . $transaction->getAmount() . "</td>";
    echo "<td>" . $transaction->getDate() . "</td>";
    echo "<td>" . $transaction->getCategory() . "</td>";
    echo "</tr>";
}
echo "</table>";