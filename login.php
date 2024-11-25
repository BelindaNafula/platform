<?php
session_start();
include 'db connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    // Validate input
    if (empty($username) || empty($password) || empty($user_type)) {
        $error_message = "Please fill out all fields.";
    } else {
        // Determine table based on user type
        if ($user_type == 'user') {
            $sql = "SELECT * FROM users WHERE username = ?";
        } elseif ($user_type == 'service_provider') {
            $sql = "SELECT * FROM service_providers WHERE username = ?";
        } else {
            $error_message = "Invalid user type.";
        }

        // Prepare and execute query
        if (isset($sql)) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['username'] = $username;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_type'] = $user_type;

                    // Redirect to respective dashboard
                    if ($user_type == 'user') {
                        header('Location: users_dashboard.php');
                    } elseif ($user_type == 'service_provider') {
                        header('Location: providers_dashboard.php');
                    }
                    exit();
                } else {
                    $error_message = "Incorrect password.";
                }
            } else {
                $error_message = "No account found with that username.";
            }

            $stmt->close(); // Close statement
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="register.php">Sign Up</a></li>
        </ul>
    </nav>

    <h1>Login</h1>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="user_type">Login as:</label>
        <select id="user_type" name="user_type" required>
            <option value="user">User</option>
            <option value="service_provider">Service Provider</option>
        </select>

        <?php if (isset($error_message)) { echo "<p style='color:red;'>$error_message</p>"; } ?>

        <button type="submit">Login</button>
        <p>Don't have an account? <a href="register.php">Sign Up</a></p>
    </form>
</body>
</html>
