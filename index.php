<?php
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1>Patient Management Systems</h1>
        <nav>
            <a href="index.php">Home</a>
            <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Doctor'): ?>
                <a href="patients.php">Patients</a>
            <?php endif; ?>
            <?php if ($_SESSION['role'] === 'Admin'): ?>
                <a href="doctors.php">Doctor</a>
            <?php endif; ?>
            <a href="appointments.php">View Appointments</a>
            <?php if ($_SESSION['role'] === 'Patient'): ?>
                <a href="make_appointment.php">Make Appointment</a>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
        </nav>

    </header>
    <main>
        <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
        <p>Role: <?php echo $_SESSION['role']; ?></p>
    </main>
</body>

</html>