<?php
include '../config.php';
session_start();

// Cek jika pengguna bukan admin
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Hapus pengguna berdasarkan ID
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $query = "DELETE FROM users WHERE id = $user_id";
    if (mysqli_query($conn, $query)) {
        header("Location: manage_users.php");
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
} else {
    echo "User ID is missing!";
}
?>