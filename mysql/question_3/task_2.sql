-- Optimize the order_items table
ALTER TABLE order_items
ADD INDEX idx_order_id (order_id), -- Add index on order_id column to improve query performance
ADD INDEX idx_product_id (product_id), -- Add index on product_id column to improve query performance
ADD CONSTRAINT fk_order_items_order_id FOREIGN KEY (order_id) REFERENCES orders(id), -- Add foreign key constraint referencing orders table
ADD CONSTRAINT fk_order_items_product_id FOREIGN KEY (product_id) REFERENCES products(id); -- Add foreign key constraint referencing products table