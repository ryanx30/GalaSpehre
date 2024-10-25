<?php
include '../config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Prepare SQL query
    $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Bind the results
    $stmt->bind_result($id, $username, $hashed_password);
    $stmt->fetch();

    // Verify if the user exists and password matches
    if ($id && password_verify($password, $hashed_password)) {
        // Regenerate session ID for security
        session_regenerate_id();

        // Set session variables
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password";
    }

    // Close the statement
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - GalaSphere</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style_login.css">
</head>
<body>

    <div class="login-container">   
        <form action="" method="POST">
        <h2>Admin Login</h2>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <div class="form-group">
            <input type="email" id="email" name="email" required placeholder="">
                <label>Email</label>
                
            </div>
            <div class="form-group">
            <input type="password" id="password" name="password" required placeholder="">
                <label>Password</label>
                
            </div>
            <button type="submit" class="submit-btn">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                Login
            </button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>