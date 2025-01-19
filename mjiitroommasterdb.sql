-- Use or create the MJIIT RoomMaster Database
CREATE DATABASE IF NOT EXISTS mjiitroommasterdb;
USE mjiitroommasterdb;

-- Table for storing user information
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,  -- Consider storing hashed passwords
    email VARCHAR(255) NOT NULL UNIQUE,
    role ENUM('student', 'teacher') NOT NULL DEFAULT 'student'
);


CREATE TABLE `guests` (
  `guest_id` int(11) NOT NULL,
  `guestname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table for storing room information
CREATE TABLE IF NOT EXISTS rooms (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    room_name VARCHAR(255) NOT NULL,
    capacity INT NOT NULL,
    equipment TEXT,  -- List of equipment can be stored as a comma-separated string
    status ENUM('Available', 'Booked', 'Maintenance') NOT NULL DEFAULT 'Available'
);

-- Table for storing bookings
CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status ENUM('Confirmed', 'Cancelled', 'Completed') NOT NULL DEFAULT 'Confirmed',
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (room_id) REFERENCES rooms(room_id)
);

-- Table for storing admin information
CREATE TABLE IF NOT EXISTS admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,  -- Store hashed passwords
    email VARCHAR(255) NOT NULL UNIQUE
);



-- Sample users (passwords should be hashed using a secure method like bcrypt in a real application)
INSERT INTO users (username, password, email) VALUES
('john_doe', 'password123', 'john.doe@example.com'),
('jane_doe', 'password123', 'jane.doe@example.com');

-- Sample rooms
INSERT INTO rooms (room_name, capacity, equipment, status) VALUES
('Bilik Kuliah 10', 30, 'Projector, Whiteboard', 'Available'),
('Seminar Room', 100, 'Video Conferencing, Sound System', 'Booked'),
('Bilik Kuliah 02', 30, 'Projector', 'Available');

-- Sample bookings
INSERT INTO bookings (user_id, room_id, booking_date, start_time, end_time, status) VALUES
(1, 1, CURDATE(), '09:00:00', '11:00:00', 'Confirmed'),
(2, 3, CURDATE(), '12:00:00', '14:00:00', 'Confirmed');
