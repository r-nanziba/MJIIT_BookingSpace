<?php
include('db_connect.php');

// Add User Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_BCRYPT);
    $role = $conn->real_escape_string($_POST['role']);
    $type_id = $conn->real_escape_string($_POST['type_id']); // New field for type_id

    $sql = "INSERT INTO users (username, email, password, role, created_at, type_id) VALUES (?, ?, ?, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $username, $email, $password, $role, $type_id);

    if ($stmt->execute()) {
        header("Location: adminusermanagement.php?success=User added successfully!");
        exit();
    } else {
        $error = "Error adding user: " . $stmt->error;
    }
}

// Delete User Logic
if (isset($_GET['delete'])) {
    $user_id = intval($_GET['delete']);

    // First, delete associated bookings
    $sql = "DELETE FROM bookings WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        // Then, delete the user
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            header("Location: adminusermanagement.php?success=User deleted successfully!");
            exit();
        } else {
            $error = "Error deleting user: " . $stmt->error;
        }
    } else {
        $error = "Error deleting bookings: " . $stmt->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin User Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        /* Custom Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', Arial, sans-serif;
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
        .container {
            max-width: 1100px;
            margin: 20px auto;
        }
        .alert {
            margin-top: 20px;
        }
        .btn-primary {
            background-color: #5c6bc0;
            border-color: #5c6bc0;
        }
        .btn-danger {
            background-color: #d32f2f;
            border-color: #d32f2f;
        }
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
    </style>
</head>
<body>
    <nav>
        <div class="navbar">
            <div class="navbar-title">
                <img src="UTM-LOGO-FULL.png" alt="UTM Logo">
                <img src="Mjiit RoomMaster logo.png" alt="MJIIT Logo">
                <p>BookingSpace - Admin</p>
            </div>
            <div class="navbar-links">
                <a href="admin_dashboard.php">Dashboard</a>
                <a href="rooms_admin.php">Rooms</a>
                <a href="adminusermanagement.php"><b>Users</b></a>
                <a href="admin_analytics.php">Analytics</a>
            </div>
            <div class="dropdown">
            <i class="fa-solid fa-right-from-bracket"></i>
            <div class="dropdown-content">
                <a href="login.php">Logout</a>
            </div>
        </div>
        </div>
    </nav>
    <div class="container">
        <h1 class="text-center">Admin User Management</h1>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Add User Button -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">+Add User</button>

        <!-- User Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>User Type</th> <!-- New column for User Type -->
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT users.user_id, users.username, users.email, users.role, user_types.type_name, users.created_at
                        FROM users
                        LEFT JOIN user_types ON users.type_id = user_types.type_id
                        UNION
                        SELECT guests.guest_id AS user_id, guests.guestname AS username, guests.email, NULL AS role, user_types.type_name, guests.created_at
                        FROM guests
                        LEFT JOIN user_types ON guests.type_id = user_types.type_id
                        ORDER BY created_at DESC;";
                $result = $conn->query($sql);

                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['role']) ?></td>
                            <td><?= htmlspecialchars($row['type_name']) ?></td> <!-- Display user type -->
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <td>
                                <a href="adminusermanagement.php?delete=<?= $row['user_id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile;
                else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="student">Student</option>
                                <option value="teacher">Teacher</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="type_id" class="form-label">User Type</label>
                            <select class="form-control" id="type_id" name="type_id" required>
                                <option value="1">User</option>
                                <option value="2">Guest</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add_user">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
