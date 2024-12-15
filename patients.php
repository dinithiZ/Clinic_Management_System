<?php
include 'db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Doctor')) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM patients";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Patients</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="appointments.php">Appointments</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>DOB</th>
                <th>Phone</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["patientID"]; ?></td>
                    <td><?php echo $row["name"]; ?></td>
                    <td><?php echo $row["DOB"]; ?></td>
                    <td><?php echo $row["phone_number"]; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>
</body>
</html>
