<?php
include '../config.php';
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID pengguna
$user_id = $_SESSION['user_id'];

// Ambil daftar event yang sudah didaftarkan
$query_registered = "SELECT r.event_id, e.name, e.date, e.time, e.location FROM registrations r 
                     JOIN events e ON r.event_id = e.id 
                     WHERE r.user_id = ?";
$stmt = $conn->prepare($query_registered);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result_registered = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Events</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2>Registered Events</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_registered && $result_registered->num_rows > 0): ?>
                    <?php while($registration = $result_registered->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($registration['name']); ?></td>
                        <td><?php echo htmlspecialchars($registration['date']); ?></td>
                        <td><?php echo htmlspecialchars($registration['time']); ?></td>
                        <td><?php echo htmlspecialchars($registration['location']); ?></td>
                        <td>
                            <form action="cancel_registration.php" method="post" onsubmit="return confirm('Are you sure you want to cancel this registration?');">
                                <input type="hidden" name="event_id" value="<?php echo $registration['event_id']; ?>">
                                <button type="submit" class="btn btn-danger">Cancel Registration</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No registered events found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="profile.php" class="btn btn-secondary">Back to Profile</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
