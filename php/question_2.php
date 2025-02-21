<?php
/**
 * This script processes a list of orders and calculates the total amount for completed orders.
 * 
 * The script performs the following steps:
 * 1. Defines an array of orders, each with an 'id', 'status', and 'total'.
 * 2. Filters the orders to include only those with a status of 'completed'.
 * 3. Calculates the total amount of the completed orders.
 * 4. Outputs the total amount.
 * 
 * Variables:
 * - $orders: An array of associative arrays, each representing an order.
 * - $completedOrders: An array of orders that have a status of 'completed'.
 * - $total: The total amount of all completed orders.
 */

// Define an array of orders
$orders = [
    ['id' => 1, 'status' => 'completed', 'total' => 150],
    ['id' => 2, 'status' => 'pending', 'total' => 200],
    ['id' => 3, 'status' => 'completed', 'total' => 100],
];

// Filter the orders to include only those with a status of 'completed'
$completedOrders = array_filter($orders, function($order) {
    return $order['status'] === 'completed';
});

// Calculate the total amount of the completed orders
$total = array_reduce($completedOrders, function($carry, $order) {
    return $carry + $order['total'];
}, 0);

// Output the total amount
echo "Total amount for completed orders: $total" . PHP_EOL;