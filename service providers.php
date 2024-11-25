<?php
include 'db connection.php'; // Include the database connection file

$service_id = $_GET['service_id'] ?? null;
if ($service_id) {
    $query = "SELECT * FROM service_providers WHERE service_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Service Providers</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="login.php">Login</a> / <a href="register.php">Sign Up</a></li>
        </ul>
    </nav>
    <h1>Service Providers</h1>
    <div class="provider-list">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="provider-item">
                    <h2><?php echo htmlspecialchars($row['provider_name']); ?></h2>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No providers found for this service.</p>
        <?php endif; ?>
    </div>
</body>
</html>
