<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #1a1a1a;
            background-image: linear-gradient(rgba(0, 255, 0, 0.05) 1px, transparent 1px),
                            linear-gradient(90deg, rgba(0, 255, 0, 0.05) 1px, transparent 1px);
            background-size: 30px 30px;
            color: #fff;
            padding: 2rem;
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            color: #00ff00;
            margin-bottom: 2rem;
            font-size: 2.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        h2 {
            color: #00ff00;
            margin: 2rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #00ff00;
            animation: fadeIn 0.5s ease-out;
        }

        form {
            background: #2a2a2a;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
            animation: slideUp 0.5s ease-out;
        }

        label {
            display: block;
            margin-top: 1rem;
            color: #fff;
            font-weight: 500;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 0.6rem;
            margin-top: 0.5rem;
            background: #333;
            border: 1px solid #00ff00;
            border-radius: 5px;
            color: #fff;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.3);
            background: #404040;
        }

        button {
            background: #00ff00;
            color: #000;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 1rem;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        button:hover {
            background: #00cc00;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
            transform: translateY(-2px);
        }

        .container {
            text-align: center;
        }

        .back-btn {
            display: block;
            margin: 2rem auto 0;
            background: #00ff00;
            color: #000;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .back-btn:hover {
            background: #00cc00;
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.5);
            transform: translateY(-2px);
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Settings</h1>

        <?php if (isset($_SESSION['success'])): ?>
            <p class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <h2>Update Password</h2>
        <form method="POST">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" required>
            <button type="submit" name="update_password">Update Password</button>
        </form>

        <h2>Add New Admin</h2>
        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit" name="add_admin">Add Admin</button>
        </form>

        <button class="back-btn" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
    </div>
</body>
</html>
