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
            return $carry + $order->getAmount();
        }, 0.0);

        return $total;
    }
}