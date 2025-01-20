<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "mjiitroommasterdb";
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get counts for Total, Confirmed, and Pending bookings
$totalBookingsQuery = "SELECT COUNT(*) AS total FROM bookings";
$confirmedBookingsQuery = "SELECT COUNT(*) AS confirmed FROM bookings WHERE status = 'Confirmed'";
$pendingBookingsQuery = "SELECT COUNT(*) AS pending FROM bookings WHERE status = 'Pending'";

$totalResult = $conn->query($totalBookingsQuery);
$confirmedResult = $conn->query($confirmedBookingsQuery);
$pendingResult = $conn->query($pendingBookingsQuery);

$totalBookings = $totalResult->fetch_assoc()['total'];
$confirmedBookings = $confirmedResult->fetch_assoc()['confirmed'];
$pendingBookings = $pendingResult->fetch_assoc()['pending'];

// Get the recent bookings
$recentBookingsQuery = "SELECT b.booking_id, u.username, r.room_name, b.booking_date, b.start_time, b.end_time, b.status
                        FROM bookings b
                        JOIN users u ON b.user_id = u.user_id
                        JOIN rooms r ON b.room_id = r.room_id
                        ORDER BY b.booking_id DESC";
$recentBookingsResult = $conn->query($recentBookingsQuery);

// Approve or Reject Booking
if (isset($_GET['action']) && isset($_GET['booking_id'])) {
    $action = $_GET['action'];
    $booking_id = $_GET['booking_id'];
    if ($action == 'approve') {
        $updateQuery = "UPDATE bookings SET status = 'Confirmed' WHERE booking_id = $booking_id";
    } elseif ($action == 'reject') {
        $updateQuery = "UPDATE bookings SET status = 'Cancelled' WHERE booking_id = $booking_id";
    }
    $conn->query($updateQuery);
    header("Location: admin_dashboard.php"); // Refresh page after action
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MJIIT RoomMaster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
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
            margin-left: auto;
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
            cursor: pointer;
        }

        /* Updated dropdown styles */
        .dropdown {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }
        .dropdown-content.show {
            display: block;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dashboard-container {
            max-width: 1100px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .dashboard-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .dashboard-header h2 {
            color: #8B0000;
            font-weight: bold;
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .stat-card {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .stat-card h3 {
            font-size: 2em;
            margin-bottom: 10px;
            color: #8B0000;
        }
        .stat-card p {
            font-size: 1.2em;
            color: #333;
        }
        .table-container {
            margin-top: 30px;
        }
        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-container th, .table-container td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .table-container th {
            background-color: #8B0000;
            color: white;
        }
        .table-container tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Updated button styles */
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            margin: 0 5px;
            color: white;
            display: inline-block;
        }
        .btn-approve {
            background-color: #28a745;
        }
        .btn-approve:hover {
            background-color: #218838;
            color: white;
        }
        .btn-reject {
            background-color: #dc3545;
        }
        .btn-reject:hover {
            background-color: #c82333;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-title">
            <img src="UTM-LOGO-FULL.png" alt="UTM Logo">
            <img src="Mjiit RoomMaster logo.png" alt="MJIIT Logo">
            <p> BookingSpace - Admin</p>
        </div>
        <div class="navbar-links">
            <a href="admin_dashboard.php"><b>Dashboard</b></a>
            <a href="rooms_admin.php">Rooms</a>
            <a href="adminusermanagement.php">Users</a>
            <a href="admin_analytics.php">Analytics</a>
        </div>
        <div class="dropdown">
            <i class="fa-solid fa-right-from-bracket"></i>
            <div class="dropdown-content">
                <a href="login.php">Logout</a>
            </div>
        </div>
    </div>

    <!-- Dashboard Container -->
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h2>Welcome, Admin</h2>
            <p>Overview of room bookings and management</p>
        </div>

        <!-- Stats Overview -->
        <div class="stats-container">
            <div class="stat-card">
                <h3><?php echo $totalBookings; ?></h3>
                <p>Total Bookings</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $confirmedBookings; ?></h3>
                <p>Confirmed</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $pendingBookings; ?></h3>
                <p>Pending</p>
            </div>
        </div>

        <!-- Booking Table -->
        <div class="table-container">
            <h3>Recent Bookings</h3>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>User</th>
                        <th>Room</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $recentBookingsResult->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row['booking_id'] . "</td>
                                <td>" . $row['username'] . "</td>
                                <td>" . $row['room_name'] . "</td>
                                <td>" . $row['booking_date'] . "</td>
                                <td>" . $row['start_time'] . " - " . $row['end_time'] . "</td>
                                <td>" . $row['status'] . "</td>
                                <td>
                                    <a href='?action=approve&booking_id=" . $row['booking_id'] . "' class='btn btn-approve'>Approve</a>
                                    <a href='?action=reject&booking_id=" . $row['booking_id'] . "' class='btn btn-reject'>Reject</a>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Toggle dropdown on click
        document.querySelector('.dropdown').addEventListener('click', function(e) {
            document.querySelector('.dropdown-content').classList.toggle('show');
            e.stopPropagation();
        });

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            if (!e.target.matches('.fa-right-from-bracket')) {
                const dropdown = document.querySelector('.dropdown-content');
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        });
    </script>

</body>
</html>