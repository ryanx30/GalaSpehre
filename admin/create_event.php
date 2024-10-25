<?php
session_start();
include '../config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = $_POST['event_name'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $max_participants = $_POST['max_participants'];
    $price = $_POST['price'];
    $status = $_POST['status']; 
    $image_url = $_POST['event_image']; // ambil URL dari input

    // Insert event details into the database
    $stmt = $conn->prepare("INSERT INTO events (name, description, date, time, location, max_participants, price, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt && $stmt->execute([$event_name, $description, $date, $time, $location, $max_participants, $price, $image_url, $status])) {
        $_SESSION['success'] = "Event created successfully.";
    } else {
        $_SESSION['error'] = "Failed to create event: " . $stmt->error;
    }
    $stmt->close();

    header('Location: event_management.php');
    exit();
}
?>
