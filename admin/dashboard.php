<?php
include '../config.php';
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
}

$query = "SELECT * FROM events";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Max Participants</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($event = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($event['name']); ?></td>
                    <td><?php echo htmlspecialchars($event['date']); ?></td>
                    <td><?php echo htmlspecialchars($event['max_participants']); ?></td>
                    <td>
                        <a href="manage_event.php?id=<?php echo $event['id']; ?>">Edit</a> | 
                        <a href="delete_event.php?id=<?php echo $event['id']; ?>">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
