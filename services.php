<?php
include 'db connection.php';

// Ensure the service_id is passed in the URL
$service_id = $_GET['service_id'] ?? null;

if ($service_id) {
    // Fetch service details from the database
    $service_query = "SELECT * FROM services WHERE service_id = ?";
    $service_stmt = $conn->prepare($service_query);
    $service_stmt->bind_param("i", $service_id);
    $service_stmt->execute();
    $service_result = $service_stmt->get_result();

    // Check if the service exists
    if ($service_result->num_rows > 0) {
        $service = $service_result->fetch_assoc();
    } else {
        echo 'Service not found.';
        exit; // Exit if no service found
    }

    // Fetch providers offering this service
    $providers_query = "
        SELECT sp.provider_id, sp.username AS provider_name 
        FROM service_providers sp
        JOIN services s ON sp.provider_id = s.provider_id
        WHERE s.service_id = ?";
    $providers_stmt = $conn->prepare($providers_query);
    $providers_stmt->bind_param("i", $service_id);
    $providers_stmt->execute();
    $providers_result = $providers_stmt->get_result();
} else {
    echo 'Invalid service ID.';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title><?php echo htmlspecialchars($service['service_name']); ?> - Service Details</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($service['service_name']); ?></h1>
    <p><?php echo htmlspecialchars($service['description']); ?></p>

    <h2>Available Providers</h2>
    <?php if ($providers_result->num_rows > 0): ?>
        <ul>
            <?php while ($provider = $providers_result->fetch_assoc()): ?>
                <li>
                    <a href="provider_details.php?provider_id=<?php echo $provider['provider_id']; ?>">
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
// Close statements and connection
$service_stmt->close();
$providers_stmt->close();
$conn->close();
?>






