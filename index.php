<?php
session_start(); // Start the session

// Check if the user is logged in
$is_logged_in = isset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Homepage - Service Platform</title>
    <style>
        
    </style>
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
<h1>Welcome to the Community Service Sharing Platform</h1>
<main>
    <section>
        <p>Welcome to our service platform where you can find various services offered by our trusted service providers. Whether you need a plumber, a tutor, or any other service, we have you covered!</p>
    </section>

    <section>
        <h2>Get Started</h2>
        <p>If you are a service provider, <a href="register.php">register here</a> to offer your services. If you are looking for services, <a href="services.php">explore our services</a>!</p>
    </section>

    <section>
        <h2>Featured Services</h2>
        <p>Check back later for featured services and special offers!</p>
    </section>
</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Community Service Sharing Platform. All rights reserved.</p>
</footer>
</body>
</html>





