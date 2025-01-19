<?php
session_start();

// Include database connection
include('db_connect.php'); 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Get user information from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT username, email FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);  // Bind the user_id parameter
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

// Get user's room bookings
$booking_query = "SELECT b.booking_id, r.room_name, b.booking_date, b.start_time, b.end_time, b.status 
                  FROM bookings b
                  JOIN rooms r ON b.room_id = r.room_id
                  WHERE b.user_id = ? 
                  ORDER BY b.booking_date DESC, b.start_time DESC";
$booking_stmt = $conn->prepare($booking_query);
$booking_stmt->bind_param("i", $user_id);  // Bind the user_id parameter
$booking_stmt->execute();
$booking_result = $booking_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background: linear-gradient(145deg, #9e2a2f, #d14d57);
            color: white;
            padding: 7px 0;
            text-align: center;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.15);
        }

        header h1 {
            font-size: 40px;
            margin-bottom: 10px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        header p {
            font-size: 18px;
            margin: 5px 0;
            font-weight: 300;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            overflow: hidden;
        }

        .back-button {
            display: inline-block;
            margin: 20px 0;
            padding: 15px 25px;
            background-color: #9e2a2f;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-radius: 50px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background-color: #7f1d23;
            transform: translateY(-2px);
        }

        .content {
            padding: 30px;
        }

        .content h2 {
            font-size: 32px;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fafafa;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .user-info p {
            font-size: 18px;
            margin: 5px 0;
            color: #555;
        }

        .table-container {
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th, table td {
            padding: 18px 25px;
            text-align: left;
            border-bottom: 2px solid #ddd;
        }

        table th {
            background-color: #9e2a2f;
            color: white;
            font-size: 18px;
            font-weight: bold;
        }

        table td {
            font-size: 16px;
            color: #666;
            font-weight: 400;
        }

        table tr:hover {
            background-color: #f9f9f9;
        }

        .status-confirmed {
            color: #4CAF50;
            font-weight: bold;
        }

        .status-pending {
            color: #FF9800;
            font-weight: bold;
        }

        .status-rejected {
            color: #F44336;
            font-weight: bold;
        }

        footer {
            background-color: #9e2a2f;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<header>
    <h1>User Profile</h1>
    <p>Welcome back, <?php echo htmlspecialchars($user['username']); ?></p>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
</header>

<div class="container">
    <a href="home.php" class="back-button">← Back to Home</a>
    <div class="content">
        <h2>Your Room Bookings</h2>
        <div class="user-info">
            <div>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            </div>
        </div>

        <div class="table-container">
            <?php if ($booking_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Room Name</th>
                            <th>Booking Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($booking = $booking_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['room_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                                <td><?php echo htmlspecialchars($booking['start_time']); ?></td>
                                <td><?php echo htmlspecialchars($booking['end_time']); ?></td>
                                <td>
                                    <span class="<?php echo htmlspecialchars($booking['status']) === 'Confirmed' ? 'status-confirmed' : (htmlspecialchars($booking['status']) === 'Pending' ? 'status-pending' : 'status-rejected'); ?>">
                                        <?php echo htmlspecialchars($booking['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No bookings found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<footer>
    &copy; <?php echo date("Y"); ?> RoomMaster. All Rights Reserved.
</footer>

</body>
</html>

<?php
// Close the database connection
$stmt->close();
$booking_stmt->close();
$conn->close();
?>
