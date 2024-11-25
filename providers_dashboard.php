<?php
session_start();

// Check if the user is logged in and is a service provider
if (!isset($_SESSION['username']) || $_SESSION['user_type'] !== 'service_provider') {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

include 'db connection.php'; // Include database connection

// Fetch service provider's data (example: services they provide)
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM services WHERE provider_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$services = [];
while ($row = $result->fetch_assoc()) {
    $services[] = $row;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Service Provider Dashboard</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

    <h2>Your Services</h2>
    <?php if (count($services) > 0): ?>
        <table border="1">
            <tr>
                <th>Service ID</th>
                <th>Service Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Category</th>
            </tr>
            <?php foreach ($services as $service): ?>
                <tr>
                    <td><?php echo htmlspecialchars($service['id']); ?></td>
                    <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                    <td><?php echo htmlspecialchars($service['description']); ?></td>
                    <td><?php echo htmlspecialchars($service['price']); ?></td>
                    <td><?php echo htmlspecialchars($service['category']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>You have not added any services yet.</p>
    <?php endif; ?>

    <h3>Actions</h3>
    <ul>
        <li><a href="add_service.php">Add New Service</a></li>
        <li><a href="edit_profile.php">Edit Profile</a></li>
    </ul>
</body>
</html>


