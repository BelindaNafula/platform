<?php
// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from the database
include 'db connection.php';

$username = $_SESSION['username'];
$query = $conn->prepare("SELECT * FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();
$query->close();

// Fetch available services from the database
$services_query = $conn->query("SELECT * FROM services");
$services = [];
while ($row = $services_query->fetch_assoc()) {
    $services[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>User Dashboard</title>
    <style>
        /* Existing Styles */
        body {
            margin-left: 100px;
            font-family: Arial, sans-serif;
            background-color: #2ecc71;
            color: white;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Poor Richard', cursive;
        }
        nav { padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1000; }
        nav .links { display: flex; gap: 15px; margin-left: 15px; margin-right: 100px; }
        nav a { color: white; text-decoration: none; font-weight: bold; }
        nav a:hover { text-decoration: underline; background-color: #02180b; }
        .sidebar { position: fixed; top: 0; left: 0; width: 150px; height: 100%; background-color: #16a085; padding-top: 20px; display: flex; flex-direction: column; align-items: center; z-index: 500; }
        .sidebar a { color: white; text-decoration: none; font-size: 18px; margin: 15px 0; width: 100%; text-align: center; padding: 10px; transition: background-color 0.3s ease; }
        .sidebar a:hover { background-color: #1abc9c; border-radius: 5px; }
        .main-content { margin-left: 200px; padding: 20px; flex: 1; padding-top: 50px; }
        .user-profile { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
        .user-profile img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid white; }
        .user-profile h2 { margin: 0; }
        footer { text-align: center; padding: 10px; background-color: #27ae60; margin-top: auto; }

        /* Dropdown Style */
        select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            background-color: white;
            color: #333;
        }

        button {
            background-color: #27ae60;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color: #1abc9c;
        }

        .service-providers-list {
            margin-top: 20px;
            background-color: #ffffff;
            color: #333;
            padding: 10px;
            border-radius: 5px;
        }

        .service-providers-list ul {
            list-style-type: none;
            padding: 0;
        }

        .service-providers-list ul li {
            padding: 5px 0;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <span>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</span>
        <div class="links">
            <a href="index.php">Home</a>
            <a href="services.php">Services</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="index.php"><i class="fa fa-home"></i> Home</a>
        <a href="services.php"><i class="fa fa-cogs"></i> View Services</a>
        <a href="#" id="manageProfile"><i class="fa fa-user"></i> Manage Profile</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="user-profile">
            <img src="<?php echo isset($user['profile_photo']) ? 'uploads/' . htmlspecialchars($user['profile_photo']) : 'default.jpg'; ?>" alt="Profile Picture">
            <h2><?php echo htmlspecialchars($user['username']); ?></h2>
        </div>
        <h1>Dashboard</h1>
        <p>Welcome to your personalized dashboard. Use the menu to navigate.</p>

        <!-- View Services Section -->
        <h2>Select a Service</h2>
        <form method="POST" action="">
            <select name="service_id" id="service_id">
                <option value="">Select a Service</option>
                <?php foreach ($services as $service) { ?>
                    <option value="<?php echo $service['service_id']; ?>"><?php echo htmlspecialchars($service['service_name']); ?></option>
                <?php } ?>
            </select>
            <button type="submit" name="view_providers">View Providers</button>
        </form>

        <?php
        if (isset($_POST['view_providers'])) {
            $service_id = $_POST['service_id'];
            if (!empty($service_id)) {
                // Fetch providers for the selected service
                $providers_query = $conn->prepare("SELECT * FROM service_providers WHERE service_id = ?");
                $providers_query->bind_param("i", $service_id); // Using "i" for integers
                $providers_query->execute();
                $providers_result = $providers_query->get_result();
                if ($providers_result->num_rows > 0) {
                    echo '<div class="service-providers-list">';
                    echo '<h3>Registered Providers</h3>';
                    echo '<ul>';
                    while ($provider = $providers_result->fetch_assoc()) {
                        echo '<li>' . htmlspecialchars($provider['provider_name']) . '</li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                } else {
                    echo '<p>No providers found for this service.</p>';
                }
                $providers_query->close();
            } else {
                echo '<p>Please select a service.</p>';
            }
        }
        ?>
    </div>

    <!-- Footer -->
    <footer>
        &copy; <?php echo date("Y"); ?> Community Service Sharing Platform. All Rights Reserved.
    </footer>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>










