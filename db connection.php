<?php
$servername = "localhost"; // or your server name
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "service_platform"; // the database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully"; // This will confirm the connection
}
?>
