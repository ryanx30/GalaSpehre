<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form dengan filter HTML
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Mempersiapkan query untuk cek user berdasarkan email
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $name, $hashed_password);
    $stmt->fetch();

    // Jika user ditemukan, cek password
    if ($id && password_verify($password, $hashed_password)) {
        // Regenerasi session ID untuk keamanan
        session_regenerate_id();

        // Set session variabel
        $_SESSION['user_id'] = $id;
        $_SESSION['name'] = $name;

        // Redirect ke halaman pengguna
        header("Location: user/view_events.php");
        exit();
    } else {
        // Jika email atau password salah
        $error = "Invalid email or password";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GalaSphere</title>
    <link rel="stylesheet" href="assets/css/style_login.css">
</head>

<body>

    <div class="login-container">
        <form action="" method="POST">
            <h2>Login</h2>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <div class="form-group">
                <input type="email" id="email" name="email" required placeholder="">
                <label for="email">Email</label>
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" required placeholder="">
                <label for="password">Password</label>
            </div>
            <button type="submit" class="submit-btn">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                Login
            </button>
            <p>Don't have an account? <a href="register.php">Sign up</a></p>
        </form>
    </div>
</body>

</html>