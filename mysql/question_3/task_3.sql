-- Query to calculate the total revenue generated for each day in the last 30 days

-- Select the order creation date and calculate the total revenue
SELECT 
    DATE(o.created_at) AS date, -- Convert the datetime to date only
    SUM(p.price * oi.quantity) AS total_revenue -- Calculate the total revenue by summing the product price multiplied by the quantity
FROM 
    orders o -- Orders table
JOIN 
    order_items oi ON o.id = oi.order_id -- Join the orders table with the order_items table
JOIN 
    products p ON oi.product_id = p.id -- Join the order_items table with the products table
WHERE 
    o.created_at >= CURDATE() - INTERVAL 30 DAY -- Filter the orders created in the last 30 days
GROUP BY 
    DATE(o.created_at) -- Group the results by date
ORDER BY 
    date; -- Order the results by date