<?php
include 'db.php';

// Check if the user is logged in and is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Get the appointment ID from the URL
if (isset($_GET['id'])) {
    $appointmentID = $_GET['id'];

    // Update the appointment status to "Cancelled"
    $stmt = $conn->prepare("UPDATE appointments SET status = 'Cancelled' WHERE appointmentID = ?");
    $stmt->bind_param("i", $appointmentID);

    if ($stmt->execute()) {
        header("Location: appointments.php");
    } else {
        echo "Error: Could not cancel the appointment.";
    }

    $stmt->close();
} else {
    header("Location: appointments.php");
    exit();
}
?>
