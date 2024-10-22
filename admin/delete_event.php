<?php
include '../config.php';
session_start();

// Cek jika pengguna bukan admin
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Cek koneksi database
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Hapus event berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $deleteQuery = "DELETE FROM events WHERE id = $id";

    if ($conn->query($deleteQuery) === TRUE) {
        echo "Event berhasil dihapus!";
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>