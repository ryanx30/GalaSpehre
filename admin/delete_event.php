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
if (isset($_POST['delete_event'])) {
    $event_id = (int)$_POST['event_id'];
    $stmt = $conn->prepare("DELETE FROM events WHERE id=?");
    $stmt->bind_param('i', $event_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Event deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete event: " . $stmt->error;
    }
    $stmt->close();

    header('Location: event_management.php');
    exit();
}
?>