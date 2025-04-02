<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'axisbot_db');

echo "<h1>Database Connection Test</h1>";

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected to database successfully!<br>";

// Check users table
$result = $conn->query("SELECT COUNT(*) as count FROM users");
if ($result) {
    $row = $result->fetch_assoc();
    echo "Number of users in the database: " . $row['count'] . "<br>";
} else {
    echo "Error checking users table: " . $conn->error . "<br>";
}

// Check if tables exist
echo "<h2>Tables in Database:</h2>";
$result = $conn->query("SHOW TABLES");
if ($result) {
    echo "<ul>";
    while ($row = $result->fetch_row()) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
} else {
    echo "Error listing tables: " . $conn->error;
}

// Display session info
echo "<h2>Session Information:</h2>";
session_start();
echo "Session ID: " . session_id() . "<br>";
echo "Session Data:<br>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// PHP Info
echo "<h2>PHP Configuration:</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Session Save Path: " . session_save_path() . "<br>";
echo "Session Cookie Parameters:<br>";
echo "<pre>";
print_r(session_get_cookie_params());
echo "</pre>";
?> 