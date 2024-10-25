<?php
// Include the database connection
require '../config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php'); // Redirect to login if not logged in
    exit();
}

// Fetch user data from the database
$userId = $_SESSION['user_id'];

// Prepare and execute the query using MySQLi
$query = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$query->bind_param('i', $userId);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc(); // Fetching the result as an associative array

// Handle form submission for updating profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Prepare the update query
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateQuery = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
            $updateQuery->bind_param('sssi', $name, $email, $hashedPassword, $userId);
        } else {
            $updateQuery = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $updateQuery->bind_param('ssi', $name, $email, $userId);
        }
        $updateQuery->execute();

        // Redirect to profile page to see changes
        header('Location: profile.php');
        exit();
    }

    // Handle event cancellation
    if (isset($_POST['cancel_event'])) {
        $eventId = $_POST['event_id'];

        // Prepare the delete query to cancel the registration
        $cancelQuery = $conn->prepare("DELETE FROM registration WHERE user_id = ? AND event_id = ?");
        $cancelQuery->bind_param('ii', $userId, $eventId);
        $cancelQuery->execute();

        // Redirect to the same page to see changes
        header('Location: profile.php');
        exit();
    }
}

// Fetch registered events for the user, ensuring unique events are displayed
$eventsQuery = $conn->prepare("SELECT DISTINCT e.id AS event_id, e.name, e.date, e.location FROM events e INNER JOIN registration r ON e.id = r.event_id WHERE r.user_id = ?");
$eventsQuery->bind_param('i', $userId);
$eventsQuery->execute();
$registeredEvents = $eventsQuery->get_result()->fetch_all(MYSQLI_ASSOC); // Fetching all results as an associative array
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GalaSphere - User Profile</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/owl-carousel.css">
    <link rel="stylesheet" href="../assets/css/tooplate.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.css">
</head>

<body>
    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <a href="tickets.php" class="logo">Gala<em>Sphere</em></a>
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav" id="nav">
                            <li><a href="view_events.php">Home</a></li>
                            <li><a href="profile.php" class="active">Profile</a></li>
                            <li><a href="../logout.php">Logout</a></li>
                        </ul>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <div class="container mt-5">
        <h1>User Profile</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">New Password:</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Leave blank to keep current password">
                    <div class="input-group-append">
                        <span class="input-group-text" id="togglePassword" style="cursor: pointer;" onclick="togglePasswordVisibility()">
                            <i class="fa fa-eye" id="eyeIcon"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="main-dark-button">
                <button type="submit" name="update_profile" class="update-profile">Update Profile</button>
                <a href="view_events.php" class="update-profile">Back to Home</a>
            </div>
        </form>

        <h2 class="mt-5">Registered Events</h2>
        <br></br>
        <ul class="list-group">
            <?php if (!empty($registeredEvents)): ?>
                <?php foreach ($registeredEvents as $event): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars($event['name']); ?></strong><br>
                        Date: <?php echo htmlspecialchars($event['date']); ?><br>
                        Location: <?php echo htmlspecialchars($event['location']); ?><br>
                        <form method="POST" action="">
                            <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                            <button type="submit" name="cancel_event" class="btn btn-danger">Cancel Registration</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item">No registered events found.</li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- *** Footer *** -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="address">
                        <h4>Kelompok 8</h4>
                        <span>Universitas<br>Multimedia<br>Nusantara</span>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="hours"></div>
                </div>
                <div class="col-lg-4">
                    <div class="links">
                        <h4>About Us</h4>
                        <ul>
                            <li><a href="#">Imanuel Calvin</a></li>
                            <li><a href="#">Muhammad Rahadian</a></li>
                            <li><a href="#">Muhammad Naufal</a></li>
                            <li><a href="#">Muhammad Irsal</a></li>
                            <li><a href="#">Mikail Rizqy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="under-footer">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <p>Gading Serpong, Tangerang Selatan</p>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <p class="copyright">Copyright 2024 GalaSphere Company</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="sub-footer">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="logo"><span>Gala<em>Sphere</em></span></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="menu">
                                    <ul>
                                        <li><a href="view_events.php" class="active">Home</a></li>
                                        <li><a href="shows_events.php">Shows & Events</a></li>
                                        <li><a href="tickets.php">Tickets</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="social-links">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="../assets/js/jquery-2.1.0.min.js"></script>
    <script src="../assets/js/owl-carousel.js"></script>
    <script src="../assets/js/scrollreveal.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>