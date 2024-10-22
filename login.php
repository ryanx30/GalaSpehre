<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form dengan sanitasi input
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $is_admin = isset($_POST['is_admin']) ? true : false;

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

        // Redirect sesuai role user dan status admin
        if ($role == 'admin') {
            if ($is_admin) {
                header("Location: admin/dashboard.php");
                exit();
            } else {
                $error = "Please select the 'Admin Login' checkbox to login as admin.";
            }
        } else {
            header("Location: user/view_events.php");
            exit();
        }
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
        <form action="" method="POST">
            <h2>Login</h2>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
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
