<?php
include 'db connection.php';

// Check if service_id is set in the URL and is a valid integer
if (isset($_GET['service_id']) && is_numeric($_GET['service_id'])) {
    $service_id = (int) $_GET['service_id'];  // Sanitize the service_id input
} else {
    // Redirect if no valid service_id is found
    header("Location: services.php");
    exit;
}

// Fetch service details
$service_query = "SELECT * FROM services WHERE service_id = ?";
$service_stmt = $conn->prepare($service_query);
$service_stmt->bind_param("i", $service_id);
$service_stmt->execute();
$service = $service_stmt->get_result()->fetch_assoc();

// Fetch providers for this service
$providers_query = "SELECT provider_id, provider_name FROM providers WHERE service_id = ?";
$providers_stmt = $conn->prepare($providers_query);
$providers_stmt->bind_param("i", $service_id);
$providers_stmt->execute();
$providers = $providers_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title><?php echo htmlspecialchars($service['name']); ?> - Service Details</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($service['name']); ?></h1>
    <p><?php echo htmlspecialchars($service['description']); ?></p>
    <?php if (!empty($service['price'])): ?>
        <p>Price: $<?php echo number_format($service['price'], 2); ?></p>
    <?php endif; ?>

    <h2>Available Providers</h2>
    <?php if ($providers->num_rows > 0): ?>
        <ul>
            <?php while ($provider = $providers->fetch_assoc()): ?>
                <li>
                    <a href="provider details.php?provider_id=<?php echo $provider['provider_id']; ?>">
                        <?php echo htmlspecialchars($provider['provider_name']); ?>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No providers registered for this service.</p>
    <?php endif; ?>

    <a href="register_provider.php?service_id=<?php echo $service_id; ?>">Register as a Service Provider</a>
</body>
</html>

<?php
$service_stmt->close();
$providers_stmt->close();
$conn->close();
?>

