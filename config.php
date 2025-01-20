<?php
// Database configuration
$servername = "localhost";
$username = "root";
$dbpassword = "";  // MySQL password
$dbname = "mjiitroommasterdb";

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $dbpassword, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure no output or trailing spaces are sent by this file
?>
