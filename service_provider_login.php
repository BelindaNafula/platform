<?php
session_start();
include('db connection.php'); // Include database connection

// Check if the user is already logged in
if (isset($_SESSION['username']) && $_SESSION['user_type'] === 'service_provider') {
    // Redirect directly to the providers dashboard
    header('Location: providers_dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if the service provider exists in the 'users' table and is of type 'service_provider'
    $sql = "SELECT * FROM users WHERE username = ? AND user_type = 'service_provider'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = 'service_provider';

            // Redirect to the service provider dashboard
            header('Location: providers_dashboard.php');
            exit();
        } else {
            $error_message = "Incorrect password!";
        }
    } else {
        $error_message = "No service provider found with that username!";
    }

    $stmt->close();
}
?>




