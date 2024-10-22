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
    $id = $_GET['id'];
    $deleteQuery = "DELETE FROM users WHERE id = $id";

    if ($conn->query($deleteQuery) === TRUE) {
        echo "Pengguna berhasil dihapus!";
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
