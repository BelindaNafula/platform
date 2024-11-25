<?php
include 'db connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize input
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    // Basic validation: Check if fields are empty
    if (empty($username) || empty($password) || empty($user_type)) {
        echo "Please fill out all fields.";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Determine where to save data based on user type
        if ($user_type == 'user') {
            $stmt = $conn->prepare("INSERT INTO users (username, password, user_type) VALUES (?, ?, ?)");
        } elseif ($user_type == 'service_provider') {
            $stmt = $conn->prepare("INSERT INTO service_providers (username, password) VALUES (?, ?)");
        } else {
            echo "Invalid user type!";
            exit();
        }

        if ($user_type == 'user') {
            $stmt->bind_param("sss", $username, $hashed_password, $user_type);
        } else {
            $stmt->bind_param("ss", $username, $hashed_password);
        }

        if ($stmt->execute()) {
            // Redirect to login page after successful registration
            echo "Registration successful!";
            header("Location: login.php"); // Redirect to login page
            exit(); // Ensure the script stops execution after redirect
        } else {
            // Show error if the registration failed
            echo "Error: " . $stmt->error;
        }

        $stmt->close(); // Close the prepared statement
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Sign Up</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Sign Up</a></li>
        </ul>
    </nav>

    <h1>Sign Up</h1>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <label for="user_type">Register as:</label>
        <select id="user_type" name="user_type" required>
            <option value="user">User</option>
            <option value="service_provider">Service Provider</option>
        </select>
        
        <button type="submit">Sign Up</button>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </form>
</body>
</html>

