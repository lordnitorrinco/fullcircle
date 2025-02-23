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