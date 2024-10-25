<?php
session_start();
include '../config.php';   

$events = $conn->query("SELECT * FROM events ORDER BY date ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Management</title>
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
            text-align: center;
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

        input[type="file"] {
            background: #333;
            padding: 0.5rem;
        }
    </style>
</head>
<body>
    <h1>Event Management</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <p class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <h2>Create New Event</h2>
    <form method="POST" enctype="multipart/form-data" action="create_event.php">
        <label for="event_name">Event Name:</label>
        <input type="text" name="event_name" required>
        
        <label for="description">Description:</label>
        <textarea name="description" required></textarea>
        
        <label for="date">Date:</label>
        <input type="date" name="date" required>
        
        <label for="time">Time:</label>
        <input type="time" name="time" required>
        
        <label for="location">Location:</label>
        <input type="text" name="location" required>
        
        <label for="max_participants">Max Participants:</label>
        <input type="number" name="max_participants" required>
        
        <label for="price">Price:</label>
        <input type="number" name="price" step="0.01" required>

        <label for="status">Status:</label>
        <select name="status" required>
            <option value="open">Open</option>
            <option value="closed">Closed</option>
            <option value="canceled">Canceled</option>
        </select>
        
        <label for="event_image">Event Image URL:</label>
        <input type="text" name="event_image" required placeholder="Enter image URL here">

        <button type="submit" name="create_event">Create Event</button>
        <button type="button" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
    </form>

    <h2>Existing Events</h2>
    <input type="text" id="searchInput" onkeyup="searchEvents()" placeholder="Search for events...">

    <table id="eventsTable">
        <tr>
            <th>Event Name</th>
            <th>Description</th>
            <th>Date</th>
            <th>Time</th>
            <th>Location</th>
            <th>Max Participants</th>
            <th>Total Guest</th> <!-- Ini diperbaiki -->
            <th>Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($event = $events->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($event['name']); ?></td>
                <td><?php echo htmlspecialchars($event['description']); ?></td>
                <td><?php echo htmlspecialchars($event['date']); ?></td>
                <td><?php echo htmlspecialchars($event['time']); ?></td>
                <td><?php echo htmlspecialchars($event['location']); ?></td>
                <td><?php echo htmlspecialchars($event['max_participants']); ?></td>
                <td><?php echo isset($event['total_guests_attending']) ? htmlspecialchars($event['total_guests_attending']) : 0; ?></td>
                <td><?php echo 'Rp ' . number_format($event['price'], 0, ',', '.'); ?></td>
                <td><?php echo htmlspecialchars($event['status']); ?></td>
                <td>
                    <form method="POST" action="edit_event.php">
                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                        <button type="submit" name="edit_event">Edit</button>
                    </form>
                    <form method="POST" action="delete_event.php" onsubmit="return confirmDelete();">
                        <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                        <button type="submit" name="delete_event" class="delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <script>
        function searchEvents() {
            let input = document.getElementById('searchInput');
            let filter = input.value.toLowerCase();
            let table = document.getElementById('eventsTable');
            let tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        if (td[j].innerHTML.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = found ? '' : 'none';
            }
        }

        function confirmDelete() {
            return confirm("Are you sure you want to delete this event?");
        }
    </script>
</body>
</html>