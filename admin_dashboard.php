<?php
// Include the database connection file
include 'db connection.php';

// If form is submitted
if (isset($_POST['submit'])) {
    $service_name = $_POST['service_name'];
    $description = $_POST['service_description']; // Change the name to description

    // Insert the service into the database
    $sql = "INSERT INTO services (service_name, description) VALUES ('$service_name', '$description')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Service added successfully');</script>";
    } else {
        echo "<script>alert('Error adding service');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add your custom styles here */
        .popup-form {
            display: none; /* Initially hidden */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(44, 100, 82, 0.9);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            font-family: "Poor Richard", serif;
            font-size: 18px;
            z-index: 1000;
        }

        .popup-form input, .popup-form textarea, .popup-form button {
            width: 100%;
            margin: 10px 0;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: "Poor Richard", serif;
        }

        .popup-form button {
            background-color: #04180c;
            color: white;
            font-weight: bold;
            padding: 8px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .popup-form button:hover {
            background-color: #02331f;
        }

        /* Background overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 999;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <a href="#">Home</a>
        <a href="#" id="add-service-btn">Add Services</a>
        <a href="#">Manage Users</a>
        <a href="#">Settings</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-container">
        <h1>Dashboard Overview</h1>
        <p>This is where you can manage the platform. Select an option from the sidebar to get started.</p>
    </div>

    <!-- Popup Form -->
    <div class="overlay" id="overlay"></div> <!-- Overlay background -->
    <div class="popup-form" id="popup-form">
        <h2>Add New Service</h2>
        <!-- Add Service Form -->
        <form method="POST" action="admin_dashboard.php">
            <label for="service_name">Service Name</label>
            <input type="text" name="service_name" id="service_name" required>

            <label for="service_description">Service Description</label>
            <textarea name="service_description" id="service_description" required></textarea>

            <button type="submit" name="submit">Add Service</button>
        </form>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Your Company. All Rights Reserved.</p>
    </footer>

    <script>
        // Get references to the elements
        const addServiceBtn = document.getElementById("add-service-btn");
        const popupForm = document.getElementById("popup-form");
        const overlay = document.getElementById("overlay");

        // When the "Add Services" button is clicked, show the form and overlay
        addServiceBtn.addEventListener("click", function() {
            popupForm.style.display = "block";
            overlay.style.display = "block";
        });

        // Close the form and overlay when clicking on the overlay
        overlay.addEventListener("click", function() {
            popupForm.style.display = "none";
            overlay.style.display = "none";
        });
    </script>
</body>
</html>





