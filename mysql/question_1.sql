-- Create the users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Primary key with auto-increment
    name VARCHAR(255) NOT NULL, -- User's name, cannot be null
    email VARCHAR(255) UNIQUE NOT NULL, -- User's email, must be unique and cannot be null
    password VARCHAR(255) NOT NULL -- User's password, cannot be null
);

-- Create the rooms table
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Primary key with auto-increment
    number VARCHAR(50) NOT NULL, -- Room number, cannot be null
    type VARCHAR(50) NOT NULL -- Room type, cannot be null
);

-- Create the bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Primary key with auto-increment
    user_id INT NOT NULL, -- Foreign key referencing users table, cannot be null
    room_id INT NOT NULL, -- Foreign key referencing rooms table, cannot be null
    start_date DATE NOT NULL, -- Booking start date, cannot be null
    end_date DATE NOT NULL, -- Booking end date, cannot be null
    status ENUM('confirmed', 'cancelled', 'pending') DEFAULT 'pending', -- Booking status with default value 'pending'
    FOREIGN KEY (user_id) REFERENCES users(id), -- Foreign key constraint referencing users table
    FOREIGN KEY (room_id) REFERENCES rooms(id) -- Foreign key constraint referencing rooms table
);