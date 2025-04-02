<?php
error_reporting(E_ALL);
// Enable error reporting for debugging
require_once __DIR__ . "/../../constants.php";


ini_set('display_errors', 1);



try {
    // Create connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Create database if not exists
    $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if (!$conn->query($sql)) {
        throw new Exception("Error creating database: " . $conn->error);
    }

    // Select the database
    if (!$conn->select_db(DB_NAME)) {
        throw new Exception("Error selecting database: " . $conn->error);
    }

    // Set charset to utf8mb4
    if (!$conn->set_charset("utf8mb4")) {
        throw new Exception("Error setting charset: " . $conn->error);
    }

    // Enable error reporting for database operations
//    $conn->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;

} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}
?> 