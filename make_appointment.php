<?php
include 'db.php';

// Check if the user is logged in and is a Patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
    header("Location: login.php");
    exit();
}

// Fetch list of doctors to display in the dropdown
$doctors_sql = "SELECT doctorID, name, specialty FROM doctors";
$doctors_result = $conn->query($doctors_sql);

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctorID = $_POST['doctorID'];
    $appointment_date = $_POST['appointment_date'];
    $reason_for_visit = $_POST['reason_for_visit'];
    $user_id = $_SESSION['user_id'];

    // Get the patientID of the logged-in user
    $patient_sql = "SELECT patientID FROM patients WHERE userID = $user_id";
    $patient_result = $conn->query($patient_sql);
    
    if ($patient_result->num_rows == 1) {
        $patient = $patient_result->fetch_assoc();
        $patientID = $patient['patientID'];

        // Insert appointment into the database
        $stmt = $conn->prepare("INSERT INTO appointments (patientID, doctorID, appointment_date, reason_for_visit, status) VALUES (?, ?, ?, ?, 'Scheduled')");
        $stmt->bind_param("iiss", $patientID, $doctorID, $appointment_date, $reason_for_visit);

        if ($stmt->execute()) {
            $success = "Appointment successfully scheduled!";
        } else {
            $error = "Failed to schedule appointment. Please try again.";
        }
        
        $stmt->close();
    } else {
        $error = "Unable to find patient information.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Appointment</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Make Appointment</h1>
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

        <form method="POST" action="make_appointment.php">
            <label for="doctorID">Select Doctor:</label>
            <select name="doctorID" required>
                <option value="">Choose a doctor</option>
                <?php while ($doctor = $doctors_result->fetch_assoc()): ?>
                    <option value="<?php echo $doctor['doctorID']; ?>">
                        <?php echo $doctor['name'] . " (" . $doctor['specialty'] . ")"; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="appointment_date">Appointment Date:</label>
            <input type="datetime-local" name="appointment_date" required>

            <label for="reason_for_visit">Reason for Visit:</label>
            <textarea name="reason_for_visit" required></textarea>

            <button type="submit">Schedule Appointment</button>
        </form>
    </main>
</body>
</html>
