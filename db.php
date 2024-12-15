<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinic_management_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start(); // Start a session for user login management
?>
