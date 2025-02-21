USE products_db;

-- Insert sample data into products table
INSERT INTO products (name, price, stock) VALUES
('Product A', 10.99, 100),
('Product B', 15.49, 200),
('Product C', 7.99, 150),
('Product D', 25.00, 50);
-- Insert sample data into orders table
INSERT INTO orders (user_id, created_at) VALUES
(1, NOW() - INTERVAL 1 DAY),
(2, NOW() - INTERVAL 2 DAY),
(1, NOW() - INTERVAL 3 DAY),
(3, NOW() - INTERVAL 4 DAY),
(2, NOW() - INTERVAL 5 DAY),
(1, NOW() - INTERVAL 6 DAY),
(3, NOW() - INTERVAL 7 DAY),
(2, NOW() - INTERVAL 8 DAY),
(1, NOW() - INTERVAL 9 DAY),
(3, NOW() - INTERVAL 10 DAY);
INSERT INTO order_items (order_id, product_id, quantity) VALUES
(1, 1, 2),
(1, 3, 1),
(2, 2, 5),
(3, 4, 3),
(3, 1, 1),
(4, 2, 2),
(5, 3, 4),
(6, 1, 3),
(7, 4, 1),
(8, 2, 2),
(9, 3, 1),
(10, 4, 3);
