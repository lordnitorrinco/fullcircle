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
            $output->writeln("Enter order status: ");
            $status = trim(fgets(STDIN));
            $output->writeln("Enter order amount: ");
            $amount = trim(fgets(STDIN));

            if (is_numeric($amount)) {
                $orders[] = ['status' => $status, 'amount' => (float)$amount];
            } else {
                $output->writeln("<error>Invalid input. Please enter valid number for amount.</error>");
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