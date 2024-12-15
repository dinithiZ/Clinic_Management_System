<?php
include 'db.php';

// Check if the user is logged in and is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Get the appointment details
$appointmentID = $_GET['id'];
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctorID = $_POST['doctorID'];
    $appointment_date = $_POST['appointment_date'];
    $reason_for_visit = $_POST['reason_for_visit'];

    // Update the appointment details
    $stmt = $conn->prepare("UPDATE appointments SET doctorID = ?, appointment_date = ?, reason_for_visit = ? WHERE appointmentID = ?");
    $stmt->bind_param("issi", $doctorID, $appointment_date, $reason_for_visit, $appointmentID);

    if ($stmt->execute()) {
        $success = "Appointment successfully updated!";
    } else {
        $error = "Failed to update appointment. Please try again.";
    }

    $stmt->close();
}

// Fetch the current appointment details and list of doctors
$appointment_sql = "SELECT * FROM appointments WHERE appointmentID = $appointmentID";
$appointment_result = $conn->query($appointment_sql);
$appointment = $appointment_result->fetch_assoc();

$doctors_sql = "SELECT doctorID, name, specialty FROM doctors";
$doctors_result = $conn->query($doctors_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Appointment</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Update Appointment</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="appointments.php">View Appointments</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="doctorID">Select Doctor:</label>
            <select name="doctorID" required>
                <?php while ($doctor = $doctors_result->fetch_assoc()): ?>
                    <option value="<?php echo $doctor['doctorID']; ?>" <?php if ($doctor['doctorID'] == $appointment['doctorID']) echo 'selected'; ?>>
                        <?php echo $doctor['name'] . " (" . $doctor['specialty'] . ")"; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="appointment_date">Appointment Date:</label>
            <input type="datetime-local" name="appointment_date" value="<?php echo date('Y-m-d\TH:i', strtotime($appointment['appointment_date'])); ?>" required>

            <label for="reason_for_visit">Reason for Visit:</label>
            <textarea name="reason_for_visit" required><?php echo $appointment['reason_for_visit']; ?></textarea>

            <button type="submit">Update Appointment</button>
        </form>
    </main>
</body>
</html>
