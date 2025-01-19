<?php
session_start();
include 'config.php'; // Include your database connection setup

// Check if the room details are passed via URL parameters
if (isset($_GET['room'], $_GET['location'], $_GET['capacity'], $_GET['equipment'], $_GET['pricing'], $_GET['image'])) {
    // Get the room details from the URL
    $roomName = urldecode($_GET['room']);
    $location = urldecode($_GET['location']);
    $capacity = urldecode($_GET['capacity']);
    $equipment = urldecode($_GET['equipment']);
    $pricing = urldecode($_GET['pricing']);
    $image = urldecode($_GET['image']);
} else {
    // If room details are not passed, redirect to the homepage or an error page
    header("Location: home.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Room - MJIIT RoomMaster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Your existing styles */
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('bg website.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }

        .navbar {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.9);
            color: rgb(114, 4, 4);
            padding: 8px 20px;
            justify-content: space-between;
            width: 100%;
            border-bottom: 2px solid #8B0000;
            z-index: 10;
        }

        .navbar-title {
            display: flex;
            align-items: center;
        }

        .navbar-title img {
            max-height: 30px;
            margin-right: 10px;
        }

        .navbar-title p {
            font-weight: bold;
            font-size: 20px;
            margin: 0;
        }

        .navbar-links {
            display: flex;
            align-items: center;
        }

        .navbar-links a {
            color: rgb(119, 4, 4);
            text-decoration: none;
            margin-right: 20px;
            font-size: 14px;
        }

        .navbar-links a:hover {
            color: #ddd;
        }

        .navbar-profile i {
            font-size: 24px;
        }

        .booking-container {
            margin-top: 50px;
            text-align: center;
            padding: 20px;
        }

        .room-details {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 60%;
            margin: 0 auto;
        }

        .room-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
        }

        .room-info {
            margin-top: 20px;
        }

        .room-info p {
            font-size: 1.2em;
            color: #333;
        }

        .form-inputs {
            margin-top: 30px;
            text-align: left;
        }

        .form-inputs label {
            font-weight: bold;
            font-size: 1.1em;
            margin-bottom: 5px;
            display: block;
        }

        .form-inputs input {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border-radius: 8px;
            border: 2px solid #800000;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        .book-now-button {
            padding: 10px 20px;
            font-size: 1.2em;
            background-color: #800000;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
        }

        .book-now-button:hover {
            background-color: #5f2a1e;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 8px;
            text-align: center;
        }

        .modal-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .modal-buttons a {
            padding: 10px 20px;
            background-color: #800000;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
        }

        .modal-buttons a:hover {
            background-color: #5f2a1e;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-title">
            <img src="UTM-LOGO-FULL.png" alt="UTM Logo">
            <img src="Mjiit RoomMaster logo.png" alt="MJIIT Logo">
            <p>BookingSpace</p>
        </div>
        <div class="navbar-links">
            <a href="home.php">Home</a>
            <a href="my_bookings.php">My Bookings</a>
            <a href="rooms.php">Rooms</a>
            <a href="analytics.php">Analytics</a>
            <a href="help.php">Help</a>
</div>
        
    </div>

    <!-- Booking Container -->
    <div class="booking-container">
        <div class="room-details">
            <img src="<?php echo $image; ?>" alt="Room Image" class="room-image">
            <div class="room-info">
                <p><strong>Room Name:</strong> <?php echo $roomName; ?></p>
                <p><strong>Location:</strong> <?php echo $location; ?></p>
                <p><strong>Capacity:</strong> <?php echo $capacity; ?> People</p>
                <p><strong>Equipment:</strong> <?php echo $equipment; ?></p>
                <p><strong>Pricing:</strong> <?php echo $pricing; ?> Per Hours</p>
            </div>

            <!-- Booking Form for Guests -->
            <form action="submit_booking.php" method="POST">
                <input type="hidden" name="room" value="<?php echo $roomName; ?>">
                <input type="hidden" name="location" value="<?php echo $location; ?>">
                <div class="form-inputs">
                    <label for="booking-date">Booking Date:</label>
                    <input type="date" id="booking-date" name="booking_date" required>

                    <label for="checkin-time">Check-In Time:</label>
                    <input type="time" id="checkin-time" name="checkin_time" required>

                    <label for="checkout-time">Check-Out Time:</label>
                    <input type="time" id="checkout-time" name="checkout_time" required>
                </div>

                <button type="submit" class="book-now-button">Proceed to Payment</button>
            </form>
        </div>
    </div>

    <script>
        // Payment logic can go here if needed
    </script>
</body>
</html>
