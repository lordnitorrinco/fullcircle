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