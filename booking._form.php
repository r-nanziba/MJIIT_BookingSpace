<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking</title>
    <script>
        // Function to show a popup
        function showPopup(message) {
            alert(message);
        }
    </script>
</head>
<body>
    <h1>Room Booking Form</h1>
    <form action="submit_booking.php" method="POST">
        <label for="room">Room:</label>
        <input type="text" id="room" name="room" required><br>

        <label for="booking_date">Date:</label>
        <input type="date" id="booking_date" name="booking_date" required><br>

        <label for="checkin_time">Check-in Time:</label>
        <input type="time" id="checkin_time" name="checkin_time" required><br>

        <label for="checkout_time">Check-out Time:</label>
        <input type="time" id="checkout_time" name="checkout_time" required><br>

        <button type="submit">Submit Booking</button>
    </form>

    <?php
    // Check for error in the query string and show a popup
    if (isset($_GET['error'])) {
        if ($_GET['error'] == 'overlap') {
            echo "<script>showPopup('The room is already booked for the selected time.');</script>";
        } elseif ($_GET['error'] == 'invalid_room') {
            echo "<script>showPopup('Invalid room selected.');</script>";
        }
    }

    // Check if booking_error session exists, and show popup if true
    if (isset($_SESSION['booking_error'])) {
        echo "<script>showPopup('" . $_SESSION['booking_error'] . "');</script>";
        unset($_SESSION['booking_error']); // Clear the error message after showing it
    }
    ?>
</body>
</html>
