<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Query to get room booking analytics
$analyticsQuery = "
    SELECT 
        r.room_id,
        r.room_name, 
        r.location, 
        r.capacity, 
        r.equipment, 
        r.image,
        COUNT(b.booking_id) as total_bookings,
        (SELECT COUNT(*) FROM bookings WHERE room_id = r.room_id AND status = 'Confirmed') as confirmed_bookings
    FROM 
        rooms r
    LEFT JOIN 
        bookings b ON r.room_id = b.room_id
    GROUP BY 
        r.room_id, r.room_name, r.location, r.capacity, r.equipment, r.image
    ORDER BY 
        total_bookings DESC
";

$result = $conn->query($analyticsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking Analytics - BookingSpace</title>
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

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 10px 20px;
            border-bottom: 2px solid #8B0000;
        }

        .navbar .navbar-title {
            display: flex;
            align-items: center;
        }

        .navbar .navbar-title img {
            max-height: 30px;
            margin-right: 10px;
        }

        .navbar .navbar-title p {
            font-weight: bold;
            font-size: 20px;
            color: rgb(114, 4, 4);
            margin: 0;
        }

        .navbar .navbar-links {
            display: flex;
            align-items: center;
        }

        .navbar .navbar-links a {
            color: rgb(119, 4, 4);
            text-decoration: none;
            margin-right: 20px;
            font-size: 14px;
        }

        .navbar .navbar-links a:hover {
            text-decoration: underline;
        }

        .analytics-container {
            max-width: 1200px;
            margin: 20px auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .analytics-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s;
            cursor: pointer;
        }

        .analytics-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .analytics-card .card-details {
            padding: 15px;
        }

        .analytics-card h3 {
            font-size: 1.2em;
            color: #8B0000;
            margin-bottom: 10px;
        }

        .analytics-card p {
            font-size: 0.9em;
            color: #333;
            margin: 5px 0;
        }

        .analytics-card:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-title">
            <img src="UTM-LOGO-FULL.png" alt="UTM Logo">
            <img src="Mjiit RoomMaster logo.png" alt="MJIIT Logo">
            <p>BookingSpace Analytics</p>
        </div>
        <div class="navbar-links">
            <a href="admin_dashboard.php">Dashboard</a>
            
            <a href="rooms_admin.php">Rooms</a>
            <a href="adminusermanagement.php">Users</a>
            <a href="analytics.php">Analytics</a>
        </div>
    </div>

    <!-- Analytics Container -->
    <div class="analytics-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <a href="room_analytics_details.php?room_id=<?php echo $row['room_id']; ?>" class="analytics-card-link" style="text-decoration: none;">
                    <div class="analytics-card">
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Image of <?php echo htmlspecialchars($row['room_name']); ?>">
                        <div class="card-details">
                            <h3><?php echo htmlspecialchars($row['room_name']); ?></h3>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                            <p><strong>Total Bookings:</strong> <?php echo $row['total_bookings']; ?></p>
                            <p><strong>Confirmed Bookings:</strong> <?php echo $row['confirmed_bookings']; ?></p>
                        </div>
                    </div>
                </a>
                <?php
            }
        } else {
            echo "<p>No room booking data available.</p>";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>