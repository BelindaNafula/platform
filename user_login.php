<?php
session_start(); // Start the session

// Include database connection (assuming you've already connected to your database)
include('db connection.php'); 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if the user exists
    $sql = "SELECT * FROM users WHERE username = ?"; // Assuming your users table is named 'users'
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Check if password matches
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['id']; // Store user ID for later use

            // Redirect to user dashboard
            header('Location: users_dashboard.php');
            exit();
        } else {
            $error_message = "Incorrect password!";
        }
    } else {
        $error_message = "No user found with that username!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Signup</a></li>
        </ul>
    </nav>
</header>

<h1>User Login</h1>

<main>
    <form action="user_login.php" method="POST">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <?php if (isset($error_message)) { echo "<p style='color:red;'>$error_message</p>"; } ?>

        <button type="submit">Login</button>
    </form>
</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Community Service Sharing Platform. All rights reserved.</p>
</footer>

</body>
</html>
