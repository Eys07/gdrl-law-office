<?php
// config.php - Database configuration file
$db_host = 'localhost';
$db_user = 'root';        // Change to your database username
$db_password = '';        // Change to your database password
$db_name = 'law_firm_db';

// Create database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8");
?>