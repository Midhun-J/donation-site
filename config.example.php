<?php
// config.example.php - Rename to config.php and update with your database credentials
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "donation_site";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
