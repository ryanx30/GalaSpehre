<?php
session_start();
include '../config.php';

// Handle user deletion
if (isset($_GET['delete'])) {
    $user_id = (int)$_GET['delete'];

    // Check if the user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Delete the user
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param('i', $user_id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "User  deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete user: " . $stmt->error;
        }
    } else {
        $_SESSION['error'] = "User  not found.";
    }
    $stmt->close();

    // Redirect back to view users
    header('Location: view-registrant.php');
    exit();
}
?>