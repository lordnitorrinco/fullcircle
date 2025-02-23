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