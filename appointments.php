<?php
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Base query for appointments
$sql = "SELECT * FROM appointments";

// Restrict view based on user role
if ($_SESSION['role'] === 'Patient') {
    $user_id = $_SESSION['user_id'];
    $sql .= " WHERE patientID = (SELECT patientID FROM patients WHERE userID = $user_id)";
} elseif ($_SESSION['role'] === 'Doctor') {
    $user_id = $_SESSION['user_id'];
    $sql .= " WHERE doctorID = (SELECT doctorID FROM doctors WHERE userID = $user_id)";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Appointments</h1>
        <nav>
            <a href="index.php">Home</a>
            <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Doctor'): ?>
                <a href="patients.php">Patients</a>
            <?php endif; ?>
            <?php if ($_SESSION['role'] === 'Admin'): ?>
                <a href="doctors.php">Doctors</a>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <table>
            <tr>
                <th>Appointment ID</th>
                <th>Patient ID</th>
                <th>Doctor ID</th>
                <th>Date</th>
                <th>Reason for Visit</th>
                <th>Status</th>
                <?php if ($_SESSION['role'] === 'Admin'): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["appointmentID"]; ?></td>
                    <td><?php echo $row["patientID"]; ?></td>
                    <td><?php echo $row["doctorID"]; ?></td>
                    <td><?php echo $row["appointment_date"]; ?></td>
                    <td><?php echo $row["reason_for_visit"]; ?></td>
                    <td><?php echo $row["status"]; ?></td>
                    <?php if ($_SESSION['role'] === 'Admin'): ?>
                        <td>
                            <a href="update_appointment.php?id=<?php echo $row['appointmentID']; ?>">Edit</a> |
                            <a href="cancel_appointment.php?id=<?php echo $row['appointmentID']; ?>" onclick="return confirm('Are you sure you want to cancel this appointment?');">Cancel</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>
</body>
</html>
