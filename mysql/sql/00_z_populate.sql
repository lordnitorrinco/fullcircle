-- Use the rooms_db database
USE rooms_db;

-- Insert test data into users table
INSERT INTO users (name, email, password) VALUES
('John Doe', 'john.doe@example.com', 'password123'),
('Jane Smith', 'jane.smith@example.com', 'password456'),
('Alice Johnson', 'alice.johnson@example.com', 'password789');

-- Insert test data into rooms table
INSERT INTO rooms (number, type) VALUES
('101', 'Single'),
('102', 'Double'),
('103', 'Suite');

-- Insert test data into bookings table
INSERT INTO bookings (user_id, room_id, start_date, end_date, status) VALUES
(1, 1, CURDATE() - INTERVAL 5 DAY, CURDATE() + INTERVAL 5 DAY, 'confirmed'), -- Current booking
(2, 2, CURDATE() - INTERVAL 15 DAY, CURDATE() - INTERVAL 10 DAY, 'cancelled'), -- Past booking
(3, 3, CURDATE() + INTERVAL 10 DAY, CURDATE() + INTERVAL 20 DAY, 'pending'); -- Future booking