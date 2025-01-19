<?php
session_start();
include 'config.php'; // Include your database connection setup

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $room = $_POST['room'];
    $date = $_POST['booking_date'];
    $checkin_time = $_POST['checkin_time'];
    $checkout_time = $_POST['checkout_time'];

    // Convert times to 24-hour format for comparison
    $opening_time = "08:00";
    $closing_time = "20:00";

    if ($checkin_time < $opening_time || $checkout_time > $closing_time) {
        $_SESSION['popup_message'] = "Booking is only allowed between 8:00 AM and 8:00 PM.";
        $_SESSION['popup_type'] = "error";
        header("Location: my_bookings.php");
        exit;
    }

    // Get room ID based on room name
    $sql_room = "SELECT room_id FROM rooms WHERE room_name = ?";
    $stmt_room = $conn->prepare($sql_room);
    $stmt_room->bind_param("s", $room);
    $stmt_room->execute();
    $stmt_room->bind_result($room_id);
    $stmt_room->fetch();
    $stmt_room->close();

    if ($room_id) {
        // Check for overlapping bookings
        $sql_check = "SELECT COUNT(*) FROM bookings 
                      WHERE room_id = ? 
                      AND booking_date = ? 
                      AND (
                          (start_time < ? AND end_time > ?) OR 
                          (start_time < ? AND end_time > ?) OR 
                          (start_time >= ? AND end_time <= ?)
                      )";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("isssssss", $room_id, $date, $checkout_time, $checkin_time, $checkin_time, $checkout_time, $checkin_time, $checkout_time);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($count > 0) {
            // Conflict exists
            $_SESSION['popup_message'] = "This room is already booked for the selected time.";
            $_SESSION['popup_type'] = "error";
            header("Location: my_bookings.php");
            exit;
        } else {
            // Insert booking into the database
            $sql = "INSERT INTO bookings (user_id, room_id, booking_date, start_time, end_time, status)
                    VALUES (?, ?, ?, ?, ?, 'Pending')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iisss", $user_id, $room_id, $date, $checkin_time, $checkout_time);

            if ($stmt->execute()) {
                $_SESSION['popup_message'] = "Booking request submitted successfully.";
                $_SESSION['popup_type'] = "success";
            } else {
                $_SESSION['popup_message'] = "Error occurred while submitting your booking.";
                $_SESSION['popup_type'] = "error";
            }

            $stmt->close();
            header("Location: my_bookings.php");
            exit;
        }
    } else {
        // Room not found
        $_SESSION['popup_message'] = "Room not found.";
        $_SESSION['popup_type'] = "error";
        header("Location: my_bookings.php");
        exit;
    }
} else {
    // Invalid request
    $_SESSION['popup_message'] = "Invalid request.";
    $_SESSION['popup_type'] = "error";
    header("Location: my_bookings.php");
    exit;
}

$conn->close();
?>
