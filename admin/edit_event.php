<?php
session_start();
include '../config.php';  


// Check if event_id is set
if (!isset($_POST['event_id'])) {
    $_SESSION['error'] = "Event ID is missing.";
    header('Location: event_management.php');
    exit();
}

$event_id = (int)$_POST['event_id'];

// Fetch event details from the database
$stmt = $conn->prepare("SELECT * FROM events WHERE id=?");
$stmt->bind_param('i', $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Event not found.";
    header('Location: event_management.php');
    exit();
}

$event = $result->fetch_assoc();
$stmt->close();

// Handle form submission for updating the event
if (isset($_POST['edit_event'])) {
    // Check if all required fields are set
    if (isset($_POST['event_name'], $_POST['description'], $_POST['date'], $_POST['time'], $_POST['location'], $_POST['max_participants'], $_POST['status'], $_POST['total_guests_attending'])) {
        $name = htmlspecialchars($_POST['event_name']);
        $description = htmlspecialchars($_POST['description']);
        $date = $_POST['date'];
        $time = $_POST['time'];
        $location = htmlspecialchars($_POST['location']);
        $max_participants = (int)$_POST['max_participants'];
        $status = $_POST['status'];
        $total_guests_attending = (int)$_POST['total_guests_attending'];

        // Get image URL from form, if not provided, keep the old one
        $image_url = $_POST['image_url'];
        if (empty($image_url)) {
            $image_url = $event['image']; // Keep the old image if no new URL is provided
        }

        // Validate the image URL
        if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
            $_SESSION['error'] = "Invalid image URL.";
        } else {
            // Update the event in the database, now with total_guests_attending
            $stmt = $conn->prepare("UPDATE events SET name=?, description=?, date=?, time=?, location=?, max_participants=?, status=?, image=?, total_guests_attending=? WHERE id=?");
            $stmt->bind_param('sssssissii', $name, $description, $date, $time, $location, $max_participants, $status, $image_url, $total_guests_attending, $event_id);
        }

        // Execute the statement and check for success
        if ($stmt->execute()) {
            $_SESSION['success'] = "Event updated successfully.";
            header('Location: event_management.php');
            exit();
        } else {
            $_SESSION['error'] = "Failed to update event: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Event</title>
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

        /* Custom file input styling */
        input[type="file"] {
            background: #333;
            padding: 0.5rem;
        }

        input[type="file"]::-webkit-file-upload-button {
            background: #00ff00;
            color: #000;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        input[type="file"]::-webkit-file-upload-button:hover {
            background: #00cc00;
        }
    </style>
</head>
<body>
    <h1>Edit Event</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <p class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <form method="POST" action="edit_event.php" enctype="multipart/form-data">
        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
        
        <label for="event_name">Event Name:</label>
        <input type="text" name="event_name" value="<?php echo htmlspecialchars($event['name']); ?>" required>
        
        <label for="description">Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea>
        
        <label for="date">Date:</label>
        <input type="date" name="date" value="<?php echo $event['date']; ?>" required>
        
        <label for="time">Time:</label>
        <input type="time" name="time" value="<?php echo $event['time']; ?>" required>
        
        <label for="location">Location:</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>
        
        <label for="max_participants">Max Participants:</label>
        <input type="number" name="max_participants" value="<?php echo $event['max_participants']; ?>" required>

        <label for="total_guests_attending">Total Guests Attending:</label>
        <input type="number" name="total_guests_attending" value="<?php echo $event['total_guests_attending']; ?>" required>
        
        <label for="status">Status:</label>
        <select name="status" required>
            <option value="open" <?php echo $event['status'] === 'open' ? 'selected' : ''; ?>>Open</option>
            <option value="closed" <?php echo $event['status'] === 'closed' ? 'selected' : ''; ?>>Closed</option>
            <option value="canceled" <?php echo $event['status'] === 'canceled' ? 'selected' : ''; ?>>Canceled</option>
        </select>
        
        <label for="image_url">Event Image URL:</label>
        <input type="text" name="image_url" id="image_url" value="<?= htmlspecialchars($event['image']); ?>" required>
        
        <button type="submit" name="edit_event">Update Event</button>
        <button type="button" onclick="window.location.href='event_management.php'">Back to Event Management</button>

    </form>
</body>
</html>
