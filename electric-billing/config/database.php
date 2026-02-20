<?php
// Database configuration
$servername = "localhost";
$username = "root";  // Change this if needed
$password = "";  // Set your database password (if any)
$dbname = "angeles_electric_db";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // If there's a connection error, display a message
    die("Connection failed: " . $conn->connect_error);
}

// Start session for user authentication
session_start();
?>
