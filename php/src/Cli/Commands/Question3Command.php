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