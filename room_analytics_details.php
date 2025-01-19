<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get room ID from URL parameter
$room_id = isset($_GET['room_id']) ? (int)$_GET['room_id'] : 0;

// Fetch room details
$roomQuery = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
$roomQuery->bind_param("i", $room_id);
$roomQuery->execute();
$roomResult = $roomQuery->get_result();
$roomDetails = $roomResult->fetch_assoc();

// Most booked time slots
$timeSlotQuery = "
    SELECT 
        HOUR(start_time) as hour, 
        COUNT(*) as booking_count 
    FROM 
        bookings 
    WHERE 
        room_id = ? 
    GROUP BY 
        HOUR(start_time) 
    ORDER BY 
        booking_count DESC 
    LIMIT 3
";
$timeStmnt = $conn->prepare($timeSlotQuery);
$timeStmnt->bind_param("i", $room_id);
$timeStmnt->execute();
$timeResult = $timeStmnt->get_result();

// Booking history
$bookingHistoryQuery = "
    SELECT 
        booking_date, 
        start_time, 
        end_time, 
        status 
    FROM 
        bookings 
    WHERE 
        room_id = ? 
    ORDER BY 
        booking_date DESC 
    LIMIT 10
";
$historyStmnt = $conn->prepare($bookingHistoryQuery);
$historyStmnt->bind_param("i", $room_id);
$historyStmnt->execute();
$historyResult = $historyStmnt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($roomDetails['room_name']); ?> Analytics - BookingSpace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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

        .room-details-container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
        }

        .room-image {
            width: 40%;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }

        .room-info {
            width: 60%;
        }

        .analytics-section {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
        }

        .table {
            background-color: white;
        }
    </style>
</head>
<body>
    <div class="room-details-container">
        <img src="<?php echo htmlspecialchars($roomDetails['image']); ?>" alt="Room Image" class="room-image">
        
        <div class="room-info">
            <h2><?php echo htmlspecialchars($roomDetails['room_name']); ?> Analytics</h2>
            
            <div class="analytics-section">
                <h4>Room Details</h4>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($roomDetails['location']); ?></p>
                <p><strong>Capacity:</strong> <?php echo htmlspecialchars($roomDetails['capacity']); ?> People</p>
                <p><strong>Equipment:</strong> <?php echo htmlspecialchars($roomDetails['equipment']); ?></p>
            </div>

            <div class="analytics-section">
                <h4>Most Popular Time Slots</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Hour</th>
                            <th>Number of Bookings</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        while ($timeSlot = $timeResult->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $timeSlot['hour'] . ":00 - " . ($timeSlot['hour'] + 1) . ":00</td>";
                            echo "<td>" . $timeSlot['booking_count'] . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="analytics-section">
                <h4>Recent Booking History</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        while ($booking = $historyResult->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($booking['booking_date']) . "</td>";
                            echo "<td>" . htmlspecialchars($booking['start_time']) . "</td>";
                            echo "<td>" . htmlspecialchars($booking['end_time']) . "</td>";
                            echo "<td>" . htmlspecialchars($booking['status']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>