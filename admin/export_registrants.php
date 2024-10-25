<?php
session_start();
include '../config.php';  

// Fetch registrants data from the 'registration' table
$query = "SELECT users.name, users.email, events.name AS event_name, registration.registration_date
          FROM registration
          JOIN users ON registration.user_id = users.id
          JOIN events ON registration.event_id = events.id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}

// Set headers for CSV export
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=registrants.csv');

// Create a file pointer to output the CSV
$output = fopen('php://output', 'w');

// Output column headings
fputcsv($output, array('Username', 'Email', 'Event Name', 'Registration Date'));

// Fetch and output each row from the database
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

// Close the file pointer
fclose($output);
exit();
?>
