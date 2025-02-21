-- Use the rooms_db database
USE rooms_db;

DELIMITER //

CREATE PROCEDURE GetAvailableRooms(IN check_date DATE)
BEGIN
    SELECT number, type
    FROM rooms
    WHERE id NOT IN (
        SELECT id
        FROM bookings
        WHERE check_date BETWEEN start_date AND end_date
    );
END //

DELIMITER ;