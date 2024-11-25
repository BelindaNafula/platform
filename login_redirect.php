<?php
// login_redirect.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected user type
    $userType = $_POST['usertype'];

    // Redirect based on the user type
    if ($userType == 'user') {
        header('Location: user_login.php'); // Redirect to user login page
        exit();
    } elseif ($userType == 'service_provider') {
        header('Location: service_provider_login.php'); // Redirect to service provider login page
        exit();
    }
} else {
    // If the form wasn't submitted, redirect to home
    header('Location: index.php');
    exit();
}
?>
