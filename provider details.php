<?php
include 'db connection.php';

$provider_id = $_GET['provider_id'];

// Fetch provider details
$provider_query = "SELECT p.provider_name, p.contact_info, p.description, s.name AS service_name 
                   FROM providers p
                   JOIN services s ON p.service_id = s.service_id
                   WHERE p.provider_id = ?";
$provider_stmt = $conn->prepare($provider_query);
$provider_stmt->bind_param("i", $provider_id);
$provider_stmt->execute();
$provider = $provider_stmt->get_result()->fetch_assoc();

if (!$provider) {
    echo "Provider not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title><?php echo htmlspecialchars($provider['provider_name']); ?> - Provider Details</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($provider['provider_name']); ?></h1>
    <p><strong>Service:</strong> <?php echo htmlspecialchars($provider['service_name']); ?></p>
    <p><strong>Contact Information:</strong> <?php echo htmlspecialchars($provider['contact_info']); ?></p>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($provider['description']); ?></p>

    <a href="service_details.php?service_id=<?php echo $provider['service_id']; ?>">Back to Service Details</a>
</body>
</html>

<?php
$provider_stmt->close();
$conn->close();
?>
