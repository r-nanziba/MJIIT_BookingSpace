<?php
// Start the session
session_start();

// Include the database configuration file
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You need to log in to cancel bookings.']);
    exit;
}

// Check if a booking ID is provided in the URL
if (isset($_GET['id'])) {
    // Sanitize and validate the booking ID
    $booking_id = intval($_GET['id']); // Ensure it is an integer
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID from the session

    // Prepare the SQL statement to update the booking status
    $sql = "UPDATE bookings SET status = 'Cancelled' WHERE booking_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);

    // Check if the statement was successfully prepared
    if ($stmt) {
        // Bind parameters to the statement
        $stmt->bind_param("ii", $booking_id, $user_id);

        // Execute the statement
        if ($stmt->execute()) {
            // Return success response as JSON
            echo json_encode(['status' => 'success', 'message' => 'Booking cancelled successfully.']);
        } else {
            // Return error response as JSON
            echo json_encode(['status' => 'error', 'message' => 'Error cancelling booking: ' . $stmt->error]);
        }
        // Close the statement
        $stmt->close();
    } else {
        // Return error response as JSON
        echo json_encode(['status' => 'error', 'message' => 'Error preparing the statement: ' . $conn->error]);
    }
} else {
    // Return error response if booking ID is missing
    echo json_encode(['status' => 'error', 'message' => 'Invalid request. Booking ID is missing.']);
}

// Close the database connection
$conn->close();
?>
