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
                return new Order($orderData['status'], $orderData['amount']);
            }, $ordersData);
        } elseif (in_array('--random', $argv)) {
            $orders = [];
            $statuses = ['completed', 'pending', 'cancelled'];
            $numOrders = rand(2, 15);

            for ($i = 1; $i <= $numOrders; $i++) {
                $orders[] = new Order(
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