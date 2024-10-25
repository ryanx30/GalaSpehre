<?php
session_start();
include('../config.php');
include('../header.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "User ID is missing!";
    exit();
}

$user_id = $_GET['id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Update user details in database
    $query = "UPDATE users SET username='$username', email='$email' WHERE id=$user_id";
    if (mysqli_query($conn, $query)) {
        echo "User updated successfully!";
        header("Location: manage_users.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<h2>Edit User</h2>
<form method="POST">
    <input type="text" name="username" required value="<?= $user['username']; ?>">
    <input type="email" name="email" required value="<?= $user['email']; ?>">
    <button type="submit">Update User</button>
</form>

<?php include('../../includes/footer.php'); ?>
