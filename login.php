<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form dengan filter HTML
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Mempersiapkan query untuk cek user berdasarkan email
    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $name, $hashed_password, $role);
    $stmt->fetch();

    // Jika user ditemukan, cek password
    if ($id && password_verify($password, $hashed_password)) {
        // Regenerasi session ID untuk keamanan
        session_regenerate_id();

        // Set session variabel
        $_SESSION['user_id'] = $id;
        $_SESSION['name'] = $name;
        $_SESSION['role'] = $role;

        // Redirect sesuai role user
        if ($role == 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: user/view_events.php");
        }
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
    <link rel="stylesheet" href="assets/css/style_login_regist.css">
</head>
<body>
    <div class="login-container">
        <form action="" method="POST"> <!-- Tidak perlu mengarah ke login_process.php -->
            <h2>Login</h2>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p> <!-- Tampilkan error jika ada -->
            <?php endif; ?>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
            <p>Don't have an account? <a href="register.php">Sign up</a></p>
        </form>
    </div>
</body>
</html>
