-- Use the rooms_db database
USE products_db;

DELIMITER //

CREATE PROCEDURE GetTop5BestSellingProducts()
BEGIN
    SELECT 
        p.id, -- Product ID
        p.name, -- Product name
        SUM(oi.quantity) AS total_quantity -- Total quantity sold for each product
    FROM 
        order_items oi -- order_items table
    JOIN 
        products p ON oi.product_id = p.id -- Join the order_items table with the products table using product_id
    GROUP BY 
        p.id, p.name -- Group the results by product ID and name
    ORDER BY 
        total_quantity DESC -- Order the results by total quantity sold in descending order
    LIMIT 5; -- Limit the results to the top 5 products
END //

DELIMITER ;