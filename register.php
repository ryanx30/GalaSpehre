<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form dengan filter HTML untuk keamanan
    $name = htmlspecialchars($_POST['fullname']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi: apakah password dan konfirmasi password cocok?
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
    } else {
        // Hashing password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert ke database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            header("Location: login.php");
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - GalaSphere</title>
    <link rel="stylesheet" href="assets/css/style_regis.css">
</head>

<body>
    <div class="register-container">
        <form action="" method="POST"> <!-- Tidak perlu action register_process.php -->
            <h2>Sign Up</h2>
            <div class="form-group">
                <input type="text" id="fullname" name="fullname" required placeholder="">
                <label for="fullname">Full Name</label>
            </div>
            <div class="form-group">
                <input type="email" id="email" name="email" required placeholder="">
                <label for="email">Email</label>
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" required placeholder="">
                <label for="password">Password</label>
            </div>
            <div class="form-group">
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="">
                <label for="confirm_password">Confirm Password</label>
            </div>
            <button type="submit" class="submit-btn">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                Sign Up
            </button>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>

</html>