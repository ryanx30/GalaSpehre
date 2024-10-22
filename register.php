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
        // Cek apakah email sudah terdaftar
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "Email already registered!";
        } else {
            // Hashing password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert ke database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            if ($stmt->execute()) {
                echo "Registration successful!";
                // Optionally, redirect to the login page or send a confirmation email
                header("Location: login.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
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
    <link rel="stylesheet" href="assets/css/style_login_regist.css">
</head>
<body>
    <div class="register-container">
        <form action="" method="POST">
            <h2>Sign Up</h2>
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn">Register</button>
            <p>Already have an account? <a href="login.php">Log in</a></p>
        </form>
    </div>
</body>
</html>
