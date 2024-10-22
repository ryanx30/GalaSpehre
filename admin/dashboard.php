<?php
include '../config.php';
session_start();

// Cek jika pengguna bukan admin
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Query untuk mengambil data event
$query_events = "SELECT id, name, date, price, max_participants, location FROM events"; // Added 'location' to the query
$result_events = $conn->query($query_events);

// Query untuk mengambil data pengguna
$query_users = "SELECT id, name, email, role FROM users";
$result_users = $conn->query($query_users);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Titan+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light py-3">
        <a class="navbar-brand" href="../index.php">GalaSphere</a>
    </nav>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <ul class="nav nav-pills mb-3">
            <li class="nav-item">
                <a class="nav-link active" href="../admin/manage_event.php" data-bs-toggle="tab">Manage Events</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../admin/manage_user.php" data-bs-toggle="tab">Manage Users</a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Manage Events -->
            <div class="tab-pane fade show active" id="events">
                <h2>Events</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Price</th> <!-- Added Price column -->
                            <th>Location</th> <!-- Added Location column -->
                            <th>Max Participants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_events && $result_events->num_rows > 0): ?>
                            <?php while($event = $result_events->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($event['name']); ?></td>
                                <td><?php echo htmlspecialchars($event['date']); ?></td>
                                <td><?php echo "Rp " . number_format($event['price'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($event['location']); ?></td>
                                <td><?php echo htmlspecialchars($event['max_participants']); ?></td>
                                <td>
                                    <a href="manage_event.php?id=<?php echo $event['id']; ?>">Edit</a> | 
                                    <a href="delete_event.php?id=<?php echo $event['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus event ini?')">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">Tidak ada event.</td> <!-- Adjusted colspan to match number of columns -->
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Manage Users -->
            <div class="tab-pane fade" id="users">
                <h2>Users</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_users && $result_users->num_rows > 0): ?>
                            <?php while($user = $result_users->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                <td>
                                    <a href="manage_user.php?id=<?php echo $user['id']; ?>">Edit</a> | 
                                    <a href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">Tidak ada pengguna.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
