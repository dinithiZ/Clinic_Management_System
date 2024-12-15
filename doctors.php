<?php
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM doctors";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Doctors</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="patients.php">Patients</a>
            <a href="appointments.php">Appointments</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Specialty</th>
                <th>Phone</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["doctorID"]; ?></td>
                    <td><?php echo $row["name"]; ?></td>
                    <td><?php echo $row["specialty"]; ?></td>
                    <td><?php echo $row["phone_number"]; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>
</body>
</html>
