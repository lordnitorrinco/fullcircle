<?php

use Symfony\Component\Console\Output\ConsoleOutput;

function createDirectory($path) {
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
        echo "Directory created: $path" . PHP_EOL;
    }
}

function createFile($path, $content) {
    file_put_contents($path, $content);
    echo "File created: $path" . PHP_EOL;
}

// Crear la estructura de directorios
createDirectory('src/Commons');
createDirectory('src/Controllers');
createDirectory('src/Entities');
createDirectory('src/Services/Arrays');
createDirectory('src/Services/Orders');
createDirectory('src/Services/Transactions');
createDirectory('src/Cli');
createDirectory('src/Cli/Commands');
createDirectory('bin');

// Crear el archivo GetArrayInput.php en src/Commons
$getArrayInputContent = <<<'EOL'
<?php

namespace App\Commons;

use Symfony\Component\Console\Output\ConsoleOutput;

class GetArrayInput
{
    /**
     * Prompts the user to enter elements for the array.
     *
     * This function prompts the user to enter elements for the array one by one,
     * giving the option to stop entering elements after at least two elements have been entered.
     *
     * @return array The input array of numbers.
     */
    public function getArrayFromUser(): array
    {
        $arr = [];
        $output = new ConsoleOutput();
        while (true) {
            $output->writeln("Enter a number (or type 'done' to finish): ");
            $input = trim(fgets(STDIN));
            if (strtolower($input) === 'done' && count($arr) >= 2) {
                break;
            } elseif (is_numeric($input)) {
                $arr[] = (int)$input;
            } else {
                $output->writeln("<error>Invalid input. Please enter a number.</error>");
            }
        }
        return $arr;
    }
}
EOL;

createFile('src/Commons/GetArrayInput.php', $getArrayInputContent);

// Crear el archivo GetOrdersInput.php en src/Commons
$getOrdersInputContent = <<<'EOL'
<?php

namespace App\Commons;

use Symfony\Component\Console\Output\ConsoleOutput;

class GetOrdersInput
{
    /**
     * Prompts the user to enter orders.
     *
     * This function prompts the user to enter orders one by one,
     * giving the option to stop entering orders after at least one order has been entered.
     *
     * @return array The input array of orders.
     */
    public function getOrdersFromUser(): array
    {
        $orders = [];
        $output = new ConsoleOutput();
        while (true) {
            $output->writeln("Enter order ID: ");
            $id = trim(fgets(STDIN));
            $output->writeln("Enter order status: ");
            $status = trim(fgets(STDIN));
            $output->writeln("Enter order total: ");
            $total = trim(fgets(STDIN));

            if (is_numeric($id) && is_numeric($total)) {
                $orders[] = ['id' => (int)$id, 'status' => $status, 'total' => (float)$total];
            } else {
                $output->writeln("<error>Invalid input. Please enter valid numbers for ID and total.</error>");
            }

            $output->writeln("Do you want to add another order? (yes/no): ");
            $continue = trim(fgets(STDIN));
            if (strtolower($continue) !== 'yes' && count($orders) >= 1) {
                break;
            }
        }
        return $orders;
    }
}
EOL;

createFile('src/Commons/GetOrdersInput.php', $getOrdersInputContent);

// Crear el archivo GetTransactionsInput.php en src/Commons
$getTransactionsInputContent = <<<'EOL'
<?php

namespace App\Commons;

use Symfony\Component\Console\Output\ConsoleOutput;

class GetTransactionsInput
{
    /**
     * Prompts the user to enter transactions.
     *
     * This function prompts the user to enter transactions one by one,
     * giving the option to stop entering transactions after at least one transaction has been entered.
     *
     * @return array The input array of transactions.
     */
    public function getTransactionsFromUser(): array
    {
        $transactions = [];
        $output = new ConsoleOutput();
        while (true) {
            $output->writeln("Enter transaction ID: ");
            $id = trim(fgets(STDIN));
            $output->writeln("Enter transaction amount: ");
            $amount = trim(fgets(STDIN));
            $output->writeln("Enter transaction date (YYYY-MM-DD): ");
            $date = trim(fgets(STDIN));
            $output->writeln("Enter transaction category: ");
            $category = trim(fgets(STDIN));

            if (is_numeric($id) && is_numeric($amount) && strtotime($date)) {
                $transactions[] = ['id' => (int)$id, 'amount' => (float)$amount, 'date' => $date, 'category' => $category];
            } else {
                $output->writeln("<error>Invalid input. Please enter valid numbers for ID and amount, and a valid date.</error>");
            }

            $output->writeln("Do you want to add another transaction? (yes/no): ");
            $continue = trim(fgets(STDIN));
            if (strtolower($continue) !== 'yes' && count($transactions) >= 1) {
                break;
            }
        }
        return $transactions;
    }
}
EOL;

createFile('src/Commons/GetTransactionsInput.php', $getTransactionsInputContent);

// Crear el archivo Order.php en src/Entities
$orderContent = <<<'EOL'
<?php

namespace App\Entities;

class Order
{
    private int $id;
    private string $status;
    private float $total;

    public function __construct(int $id, string $status, float $total)
    {
        $this->id = $id;
        $this->status = $status;
        $this->total = $total;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}
EOL;

createFile('src/Entities/Order.php', $orderContent);

// Crear el archivo Transaction.php en src/Entities
$transactionContent = <<<'EOL'
<?php

namespace App\Entities;

class Transaction
{
    private int $id;
    private float $amount;
    private string $date;
    private string $category;

    public function __construct(int $id, float $amount, string $date, string $category)
    {
        $this->id = $id;
        $this->amount = $amount;
        $this->date = $date;
        $this->category = $category;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getCategory(): string
    {
        return $this->category;
    }
}
EOL;

createFile('src/Entities/Transaction.php', $transactionContent);


// Crear el archivo TransactionCategory.php en src/Entities
$transactionCategoryContent = <<<'EOL'
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
EOL;

createFile('src/Entities/TransactionCategory.php', $transactionCategoryContent);

// Crear el archivo FindSecondLargestArray.php en src/Services/Arrays
$findSecondLargestArrayContent = <<<'EOL'
<?php
namespace App\Services\Arrays;

class FindSecondLargestArray
{
    private FindNthLargestArray $findNthLargestService;

    public function __construct()
    {
        $this->findNthLargestService = new FindNthLargestArray();
    }

    public function findSecondLargestArray(array $arr): ?int
    {
        return $this->findNthLargestService->execute($arr, 2);
    }
}
EOL;

createFile('src/Services/Arrays/FindSecondLargestArray.php', $findSecondLargestArrayContent);

// Crear el archivo FindNthLargestArray.php en src/Services/Arrays
$findNthLargestArrayContent = <<<'EOL'
<?php

namespace App\Services\Arrays;

class FindNthLargestArray
{
    /**
     * Finds the nth largest number in an array.
     *
     * @param array $arr The input array of numbers.
     * @param int $n The position of the largest number to find.
     * @return int|null The nth largest number in the array, or null if the array has fewer than n elements.
     */
    public function execute(array $arr, int $n): ?int
    {
        /**
         * The following code snippet is an alternative implementation of the function using the built-in PHP functions rsort() and count():
         * 
         *  if (count($arr) < $n) return null;
         *  rsort($arr);
         *  return $arr[$n - 1];
         * 
         */

        // Count the number of elements in the array
        $length = 0;
        foreach ($arr as $value) {
            $length++;
        }

        if ($length < $n) return null;

        // Find the nth largest number using a selection algorithm
        for ($i = 0; $i < $n; $i++) {
            $maxIndex = $i;
            for ($j = $i + 1; $j < $length; $j++) {
                if ($arr[$j] > $arr[$maxIndex]) {
                    $maxIndex = $j;
                }
            }
            // Swap the found maximum element with the element at index i
            $temp = $arr[$i];
            $arr[$i] = $arr[$maxIndex];
            $arr[$maxIndex] = $temp;
        }

        return $arr[$n - 1];
    }
}

EOL;

createFile('src/Services/Arrays/FindNthLargestArray.php', $findNthLargestArrayContent);

// Crear el archivo OrderProcessor.php en src/Services/Orders
$orderProcessorContent = <<<'EOL'
<?php

namespace App\Services\Orders;

use App\Entities\Order;

class OrderProcessor
{
    /**
     * Calculates the total amount for completed orders.
     *
     * @param Order[] $orders The input array of orders.
     * @return float The total amount for completed orders.
     */
    public function calculateTotal(array $orders): float
    {
        $completedOrders = array_filter($orders, function(Order $order) {
            return $order->getStatus() === 'completed';
        });

        $total = array_reduce($completedOrders, function($carry, Order $order) {
            return $carry + $order->getTotal();
        }, 0.0);

        return $total;
    }
}
EOL;

createFile('src/Services/Orders/OrderProcessor.php', $orderProcessorContent);

// Crear el archivo CalculateTotal.php en src/Services/Orders
$calculateTotalContent = <<<'EOL'
<?php

namespace App\Services\Orders;

use App\Entities\Order;

class CalculateTotal
{
    /**
     * Calculates the total amount for completed orders.
     *
     * @param Order[] $orders The input array of orders.
     * @return float The total amount for completed orders.
     */
    public function execute(array $orders): float
    {
        $completedOrders = array_filter($orders, function(Order $order) {
            return $order->getStatus() === 'completed';
        });

        $total = array_reduce($completedOrders, function($carry, Order $order) {
            return $carry + $order->getTotal();
        }, 0.0);

        return $total;
    }
}
EOL;

createFile('src/Services/Orders/CalculateTotal.php', $calculateTotalContent);

// Crear el archivo TransactionProcessor.php en src/Services/Transactions
$transactionProcessorContent = <<<'EOL'
<?php

namespace App\Services\Transactions;

use App\Entities\Transaction;

class TransactionProcessor
{
    /**
     * Analyzes transactions to calculate balance, category expenses, and recent transactions.
     *
     * @param Transaction[] $transactions The input array of transactions.
     * @return array An associative array containing:
     *               - 'balance': The total balance of all transactions.
     *               - 'maxExpenseCategory': The category with the highest expense.
     *               - 'recentTransactions': An array of transactions from the last three days.
     */
    public function analyzeTransactions(array $transactions): array
    {
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
EOL;

createFile('src/Services/Transactions/TransactionProcessor.php', $transactionProcessorContent);

// Crear el archivo CalculateBalance.php en src/Services/Transactions
$calculateBalanceContent = <<<'EOL'
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
EOL;

createFile('src/Services/Transactions/CalculateBalance.php', $calculateBalanceContent);

// Crear el archivo FindMaxExpenseCategory.php en src/Services/Transactions
$findMaxExpenseCategoryContent = <<<'EOL'
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
            return new TransactionCategory(0, $maxExpenseCategory, $maxExpense);
        }

        return null;
    }
}
EOL;

createFile('src/Services/Transactions/FindMaxExpenseCategory.php', $findMaxExpenseCategoryContent);

// Crear el archivo FindRecentTransactions.php en src/Services/Transactions
$findRecentTransactionsContent = <<<'EOL'
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
EOL;

createFile('src/Services/Transactions/FindRecentTransactions.php', $findRecentTransactionsContent);

// Crear el archivo ArrayProcessorController.php en src/Controllers
$arrayProcessorControllerContent = <<<'EOL'
<?php

namespace App\Controllers;

use App\Services\Arrays\FindSecondLargestArray;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

class ArrayProcessorController
{
    private FindSecondLargestArray $secondLargestArrayService;

    public function __construct()
    {
        $this->secondLargestArrayService = new FindSecondLargestArray();
    }

    public function findSecondLargestArray(array $array): void
    {
        $output = new ConsoleOutput();
        // Output the array
        $table = new Table($output);
        $table->setHeaders(['Elements'])
              ->addRow([implode(', ', $array)]);
        $table->render();
        // Call the findSecondLargest function and output the result
        $result = $this->secondLargestArrayService->findSecondLargestArray($array);
        
        // Output the result in a table
        $table = new Table($output);
        $table->setHeaders(['Second Largest Element'])
              ->addRow([$result !== null ? $result : 'None']);
        $table->render();
    }
}
EOL;

createFile('src/Controllers/ArrayProcessorController.php', $arrayProcessorControllerContent);

// Crear el archivo OrderProcessorController.php en src/Controllers
$orderProcessorControllerContent = <<<'EOL'
<?php

namespace App\Controllers;

use App\Services\Orders\OrderProcessor;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

class OrderProcessorController
{
    private OrderProcessor $orderProcessor;

    public function __construct()
    {
        $this->orderProcessor = new OrderProcessor();
    }

    public function calculateTotal(array $orders): void
    {
        $output = new ConsoleOutput();
        // Output the orders
        $table = new Table($output);
        $table->setHeaders(['Order ID', 'Status', 'Total']);
        
        foreach ($orders as $order) {
            $table->addRow([$order->getId(), $order->getStatus(), $order->getTotal()]);
        }
        
        $table->render();

        // Call the calculateTotal function and output the result
        $total = $this->orderProcessor->calculateTotal($orders);

        // Output the summary table
        $summaryTable = new Table($output);
        $summaryTable->setHeaders(['Summary', 'Value']);
        $summaryTable->addRow(['Number of orders', count($orders)]);
        $summaryTable->addRow(['Total amount for completed orders', $total]);
        $summaryTable->render();
    }
}
EOL;

createFile('src/Controllers/OrderProcessorController.php', $orderProcessorControllerContent);

// Crear el archivo TransactionController.php en src/Controllers
$transactionControllerContent = <<<'EOL'
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
EOL;

createFile('src/Controllers/TransactionController.php', $transactionControllerContent);

// Crear el archivo Cli.php en src/Cli
$CliContent = <<<'EOL'
<?php

namespace App\Cli;

use App\Commons\GetArrayInput;
use App\Commons\GetOrdersInput;
use App\Commons\GetTransactionsInput;
use App\Cli\Commands\Question1Command;
use App\Cli\Commands\Question2Command;
use App\Cli\Commands\Question3Command;
use Symfony\Component\Console\Output\ConsoleOutput;

class Cli
{
    private GetArrayInput $getArrayInput;
    private GetOrdersInput $getOrdersInput;
    private GetTransactionsInput $getTransactionsInput;

    public function __construct()
    {
        $this->getArrayInput = new GetArrayInput();
        $this->getOrdersInput = new GetOrdersInput();
        $this->getTransactionsInput = new GetTransactionsInput();
    }

    public function run(array $argv): void
    {
        $output = new ConsoleOutput();
        if (in_array('-h', $argv) || in_array('--help', $argv)) {
            $this->showHelp();
            return;
        }

        if (count($argv) < 2) {
            $output->writeln("<error>Invalid usage. Use -h or --help for instructions.</error>");
            return;
        }

        $command = $argv[1];

        // Add --random to argv if not present
        if (!in_array('--random', $argv)) {
            $argv[] = '--random';
        }

        switch ($command) {
            case 'question1':
                $command = new Question1Command($this->getArrayInput);
                $command->execute($argv);
                break;

            case 'question2':
                $command = new Question2Command($this->getOrdersInput);
                $command->execute($argv);
                break;

            case 'question3':
                $command = new Question3Command($this->getTransactionsInput);
                $command->execute($argv);
                break;

            case 'all':
                $this->executeAllCommands($argv);
                break;

            default:
                $output->writeln("<error>Invalid command. Use -h or --help for instructions.</error>");
                break;
        }
    }

    private function executeAllCommands(array $argv): void
    {
        $output = new ConsoleOutput();
        $output->writeln("<info>Executing all commands with random data...</info>");

        $output->section()->writeln("<info>Executing Question 1...</info>");
        $question1Command = new Question1Command($this->getArrayInput);
        $question1Command->execute($argv);
        $output->section()->writeln("<info>Question 1 executed successfully.</info>");

        $output->section()->writeln("<info>Executing Question 2...</info>");
        $question2Command = new Question2Command($this->getOrdersInput);
        $question2Command->execute($argv);
        $output->section()->writeln("<info>Question 2 executed successfully.</info>");

        $output->section()->writeln("<info>Executing Question 3...</info>");
        $question3Command = new Question3Command($this->getTransactionsInput);
        $question3Command->execute($argv);
        $output->section()->writeln("<info>Question 3 executed successfully.</info>");

        $output->writeln("<info>All commands executed successfully.</info>");
    }

    private function showHelp(): void
    {
        $output = new ConsoleOutput();
        $output->writeln("Usage: php bin/main.php [command] [options]");
        $output->writeln("Commands:");
        $output->writeln("  question1          Execute question 1");
        $output->writeln("  question2          Execute question 2");
        $output->writeln("  question3          Execute question 3");
        $output->writeln("  all                Execute all questions with random data");
        $output->writeln("Options:");
        $output->writeln("  -h, --help         Show this help message");
        $output->writeln("  --manual           Enter data manually");
        $output->writeln("  --random           Use random data [default]");
    }
}
EOL;

createFile('src/Cli/Cli.php', $CliContent);

// Crear el archivo Question1Command.php en src/Cli/Commands
$question1CommandContent = <<<'EOL'
<?php

namespace App\Cli\Commands;

use App\Controllers\ArrayProcessorController;
use App\Commons\GetArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class Question1Command
{
    private GetArrayInput $getArrayInput;

    public function __construct(GetArrayInput $getArrayInput)
    {
        $this->getArrayInput = $getArrayInput;
    }

    public function execute(array $argv): void
    {
        $output = new ConsoleOutput();
        $array = [];

        if (in_array('--manual', $argv)) {
            $array = $this->getArrayInput->getArrayFromUser();
        } elseif (in_array('--random', $argv)) {
            $array = array_map(function() {
                return rand(1, 100);
            }, range(1, rand(2, 20)));
        } else {
            $output->writeln("<error>Invalid usage for question1. Use -h or --help for instructions.</error>");
            return;
        }

        $controller = new ArrayProcessorController();
        $controller->findSecondLargestArray($array);
    }
}
EOL;

createFile('src/Cli/Commands/Question1Command.php', $question1CommandContent);

// Crear el archivo Question2Command.php en src/Cli/Commands
$question2CommandContent = <<<'EOL'
<?php

namespace App\Cli\Commands;

use App\Controllers\OrderProcessorController;
use App\Commons\GetOrdersInput;
use App\Entities\Order;
use Symfony\Component\Console\Output\ConsoleOutput;

class Question2Command
{
    private GetOrdersInput $getOrdersInput;

    public function __construct(GetOrdersInput $getOrdersInput)
    {
        $this->getOrdersInput = $getOrdersInput;
    }

    public function execute(array $argv): void
    {
        $output = new ConsoleOutput();
        $orders = [];

        if (in_array('--manual', $argv)) {
            $ordersData = $this->getOrdersInput->getOrdersFromUser();
            $orders = array_map(function($orderData) {
                return new Order($orderData['id'], $orderData['status'], $orderData['total']);
            }, $ordersData);
        } elseif (in_array('--random', $argv)) {
            $orders = [];
            $statuses = ['completed', 'pending', 'cancelled'];
            $numOrders = rand(2, 15);

            for ($i = 1; $i <= $numOrders; $i++) {
                $orders[] = new Order(
                    $i,
                    $statuses[array_rand($statuses)],
                    rand(50, 500)
                );
            }
        } else {
            $output->writeln("<error>Invalid usage for question2. Use -h or --help for instructions.</error>");
            return;
        }

        $controller = new OrderProcessorController();
        $controller->calculateTotal($orders);
    }
}
EOL;

createFile('src/Cli/Commands/Question2Command.php', $question2CommandContent);

// Crear el archivo Question3Command.php en src/Cli/Commands
$question3CommandContent = <<<'EOL'
<?php

namespace App\Cli\Commands;

use App\Controllers\TransactionController;
use App\Commons\GetTransactionsInput;
use App\Entities\Transaction;
use Symfony\Component\Console\Output\ConsoleOutput;

class Question3Command
{
    private GetTransactionsInput $getTransactionsInput;

    public function __construct(GetTransactionsInput $getTransactionsInput)
    {
        $this->getTransactionsInput = $getTransactionsInput;
    }

    public function execute(array $argv): void
    {
        $output = new ConsoleOutput();
        $transactions = [];

        if (in_array('--manual', $argv)) {
            $transactionsData = $this->getTransactionsInput->getTransactionsFromUser();
            $transactions = array_map(function($transactionData) {
                return new Transaction($transactionData['id'], $transactionData['amount'], $transactionData['date'], $transactionData['category']);
            }, $transactionsData);
        } elseif (in_array('--random', $argv)) {
            $categories = [
            'groceries' => -1,
            'fun' => -1,
            'salary' => 10,
            'transport' => -1,
            'entertainment' => -1,
            'bonus' => 3,
            'utilities' => -1,
            'dining' => -1,
            'freelance' => 2,
            'shopping' => -1,
            'coffee' => -1,
            'investment' => 4,
            'rent' => -1,
            'subscriptions' => -1,
            ];

            $transactions = [];
            $numTransactions = rand(5, 15);

            for ($i = 1; $i <= $numTransactions; $i++) {
                $category = array_rand($categories);
                $multiplier = $categories[$category];
                $amount = rand(1, 200) * $multiplier;
                if ($i <= 3) {
                    $date = date('Y-m-d', strtotime("-" . (3 - $i) . " days"));
                } else {
                    $date = date('Y-m-d', strtotime("-" . rand(0, 27) . " days"));
                }
                $transactions[] = new Transaction($i, $amount, $date, $category);
            }
        } else {
            $output->writeln("<error>Invalid usage for question3. Use -h or --help for instructions.</error>");
            return;
        }

        $controller = new TransactionController();
        $controller->analyzeTransactions($transactions);
    }
}
EOL;

createFile('src/Cli/Commands/Question3Command.php', $question3CommandContent);

// Crear el archivo main.php en bin
$mainContent = <<<'EOL'
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Cli\Cli;

// Ejecutar el script principal
$Cli = new Cli();
$Cli->run($argv);
EOL;

createFile('bin/main.php', $mainContent);

// Crear el archivo composer.json
$composerContent = <<<'EOL'
{
    "require": {
        "symfony/console": "^5.0"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    },
    "require-dev": {
        "phpunit/phpunit": "^9.5"
    }
}
EOL;

createFile('composer.json', $composerContent);

// Crear el archivo Dockerfile
$dockerfileContent = <<<'EOL'
FROM php:8.4-fpm-alpine

# Install necessary extensions
RUN docker-php-ext-install pdo pdo_mysql

# Allow super user - set this if you use Composer as a super user at all times like in docker containers
ENV COMPOSER_ALLOW_SUPERUSER=1

# Obtain composer using multi-stage build
COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files and install dependencies
COPY composer.* ./
RUN composer install

# Copy application files to the working directory
COPY . .

# Run composer dump-autoload --optimize
RUN composer dump-autoload --optimize

# Create the fullcircle script
RUN printf '#!/bin/sh\nphp /app/bin/main.php "$@"\n' > /usr/local/bin/fullcircle

# Make the script executable
RUN chmod +x /usr/local/bin/fullcircle
EOL;

createFile('Dockerfile', $dockerfileContent);

// Crear el archivo docker-compose.yml
$dockerComposeContent = <<<'EOL'
services:
  php:
    container_name: php_container
    build:
      dockerfile: Dockerfile
EOL;

createFile('docker-compose.yml', $dockerComposeContent);

echo "Project structure created successfully.\n";