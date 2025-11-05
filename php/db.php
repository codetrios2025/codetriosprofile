<?php
// Database connection
$servername = "localhost";
$username = "codetrios_user"; // change if needed
$password = "##codetrios@2025##"; // change if needed
$dbname = "codetrios_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
