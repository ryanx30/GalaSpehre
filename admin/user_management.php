<?php
session_start();
include '../config.php';  

// Handle user deletion
if (isset($_GET['delete'])) {
    $user_id = (int)$_GET['delete'];

    // Check if the user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Delete the user
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param('i', $user_id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "User deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete user: " . $stmt->error;
        }
    } else {
        $_SESSION['error'] = "User not found.";
    }
    $stmt->close();

    // Redirect back to the same page to refresh the user list
    header('Location: user_management.php');
    exit();
}

// Fetch all users with their registered events, ensuring events are unique
$query = "
    SELECT u.id AS user_id, u.name, u.email, u.created_at, 
           (SELECT GROUP_CONCAT(DISTINCT e.name SEPARATOR ', ') 
            FROM registration r 
            LEFT JOIN events e ON r.event_id = e.id 
            WHERE r.user_id = u.id) AS registered_events
    FROM users u
    GROUP BY u.id
";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #1a1a1a;
            background-image: linear-gradient(rgba(0, 255, 0, 0.05) 1px, transparent 1px),
                            linear-gradient(90deg, rgba(0, 255, 0, 0.05) 1px, transparent 1px);
            background-size: 30px 30px;
            color: #fff;
            padding: 2rem;
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            color: #00ff00;
            margin-bottom: 2rem;
            font-size: 2.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        h2 {
            color: #00ff00;
            margin: 2rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #00ff00;
            animation: fadeIn 0.5s ease-out;
        }

        form {
            background: #2a2a2a;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
            animation: slideUp 0.5s ease-out;
        }

        label {
            display: block;
            margin-top: 1rem;
            color: #fff;
            font-weight: 500;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="time"],
        input[type="file"],
        textarea,
        select {
            width: 100%;
            padding: 0.8rem;
            margin-top: 0.5rem;
            background: #333;
            border: 1px solid #00ff00;
            border-radius: 5px;
            color: #fff;
            transition: all 0.3s ease;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.3);
            background: #404040;
        }

        button {
            background: #00ff00;
            color: #000;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 1.5rem;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        button:hover {
            background: #00cc00;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
            transform: translateY(-2px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #2a2a2a;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.1);
            margin-top: 2rem;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #333;
        }

        th {
            background: #00ff00;
            color: #000;
            font-weight: 600;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background: #333;
        }

        tr:hover {
            background: #404040;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .success {
            background: #00ff00;
            color: #000;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
            animation: slideIn 0.5s ease-out;
        }

        .error {
            background: #ff0000;
            color: #fff;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
            animation: slideIn 0.5s ease-out;
        }

        .status-open {
            color: #00ff00;
            font-weight: 500;
        }

        .status-closed {
            color: #ff0000;
            font-weight: 500;
        }

        .status-canceled {
            color: #ff6b6b;
            font-weight: 500;
        }

        .delete-btn {
            background: #ff0000;
            color: #fff;
        }

        .delete-btn:hover {
            background: #cc0000;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.5);
        }

        .edit-btn {
            background: #00ff00;
        }

        .edit-btn:hover {
            background: #00cc00;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 5px #00ff00, 0 0 10px #00ff00, 0 0 15px #00ff00;
            }
            to {
                text-shadow: 0 0 10px #00ff00, 0 0 20px #00ff00, 0 0 30px #00ff00;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <h1>User Management</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <p class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Registered At</th>
                <th>Registered Events</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $user['user_id']; ?></td>
                <td><?php echo htmlspecialchars($user['name'] ?? 'Unknown'); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo $user['created_at']; ?></td>
                <td><?php echo htmlspecialchars($user['registered_events'] ?? 'No events'); ?></td>
                <td>
                    <a href="user_management.php?delete=<?php echo $user['user_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">No users found.</td>
        </tr>
    <?php endif; ?>
</tbody>

    </table>

    <button type="button" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
</body>
</html>
