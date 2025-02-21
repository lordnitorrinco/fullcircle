SELECT room_number, room_type
FROM rooms
WHERE id NOT IN (
    SELECT room_id
    FROM bookings
    WHERE '2025-01-10' BETWEEN start_date AND end_date
);