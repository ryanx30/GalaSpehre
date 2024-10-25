<?php
include '../config.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="./admin/assets/css/admin.css"> 
    <style>
        body {
            margin: 0;
            font-family: 'Nunito', sans-serif;
            background-color: gray;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #1a1a1a;
            background-image: linear-gradient(rgba(0, 255, 0, 0.05) 1px, transparent 1px),
                            linear-gradient(90deg, rgba(0, 255, 0, 0.05) 1px, transparent 1px);
            background-size: 30px 30px;
            color: #fff;
            padding: 2rem;
            min-height: 100vh;
        }

        .container {
            text-align: center;
            width: 100%;
            max-width: 900px; 
            padding: 20px;
        }

        .header {
            font-size: 48px;
            color: white;
            margin-bottom: 20px;
        }

        .browser-mockup {
            border-radius: 10px;
            background: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .browser-mockup .browser-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            background: #f5f5f5;
        }

        .browser-mockup .browser-bar .buttons {
            display: flex;
            gap: 5px;
        }

        .browser-mockup .browser-bar .buttons div {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .browser-mockup .browser-bar .buttons .close {
            background: #ff5f56;
        }

        .browser-mockup .browser-bar .buttons .minimize {
            background: #ffbd2e;
        }

        .browser-mockup .browser-bar .buttons .maximize {
            background: #27c93f;
        }

        .browser-mockup .browser-bar .tabs {
            display: flex;
            gap: 20px;
        }

        .browser-mockup .browser-bar .tabs a {
            text-decoration: none;
            color: black;
            font-weight: bold;
        }

        .browser-mockup .browser-bar .tabs .active {
            background: #00bfa5;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .content {
            background: #1e2a38;
            padding: 40px;
            color: white;
        }

        .content h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .content p {
            font-size: 16px;
            margin-bottom: 30px;
        }

        .cards {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap; /* Allow cards to wrap on smaller screens */
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            width: 200px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card img {
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .card p {
            color: black;
            font-size: 16px;
            margin: 10px 0;
        }

        .card .admin-link {
            color: #00bfa5;
            font-size: 14px;
            text-decoration: none;
            justify-content: space-between;
        }

        .button-container {
            display: flex;
            justify-content: center; 
            gap: 100px;
            margin-top: 20px; 
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .button:hover {
            background: #009e92;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Admin Dashboard
        </div>
        <div class="browser-mockup">
            <div class="browser-bar">
                <div class="buttons">
                    <div class="close"></div>
                    <div class="minimize"></div>
                    <div class="maximize"></div>
                </div>
                <div class="tabs">
                    <a class="active" href="">Dashboard</a>
                    <a class="active" href="../admin/settings.php">Settings</a>
                    <a class="active" href="../logout.php">Back</a>
                </div>
            </div>
            <div class="content">
                <h1>Welcome to the Admin Dashboard</h1>
                <p>Manage your events and users from here.</p>
                <div class="cards">
    <div class="card">
        <img alt="Create Event" height="50" src="https://storage.googleapis.com/a1aa/image/CDiMQXfTk1VSOy6blstgfH2NzEOB76xl9FyTlIQMgKJBpvoTA.jpg" width="50"/>
        <p>Event Management</p>
        <a class="admin-link" href="event_management.php">ADMIN</a>
    </div>
    <div class="card">
        <img alt="Manage Users" height="50" src="https://storage.googleapis.com/a1aa/image/ixwTZGkPf9UkHCfLzF0gK1DGZWVBFK8VRehOb9iscPdfjeFdC.jpg" width="50"/>
        <p>Manage Users</p>
        <a class="admin-link" href="user_management.php">ADMIN</a>
    </div>
    <div class="card">
    <img alt="Export Registrants" height="50" src="https://www.advancedmd.com/wp-content/uploads/2023/01/advancedmd-lifestyle-vectorManLaptop-756x1024.png" width="50">
        <p>Export Registrants</p>
        <a class="admin-link" href="export_registrants.php">EXPORT</a>
    </div>
</div>
</body>
</html>