<?php
// Database connection
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'axisbot_db');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get table information
$query = "DESCRIBE users";
$result = $conn->query($query);

if ($result) {
    echo "<h2>Users Table Structure:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['Field']}</td>";
        echo "<td>{$row['Type']}</td>";
        echo "<td>{$row['Null']}</td>";
        echo "<td>{$row['Key']}</td>";
        echo "<td>{$row['Default']}</td>";
        echo "<td>{$row['Extra']}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "Error: " . $conn->error;
}

// Check if all required columns exist
$requiredColumns = [
    'first_name',
    'last_name',
    'email',
    'password',
    'email_verified',
    'verification_token',
    'referral_id'
];

echo "<h2>Required Columns Status:</h2>";
echo "<ul>";

foreach ($requiredColumns as $column) {
    $query = "SHOW COLUMNS FROM users LIKE '$column'";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        echo "<li style='color:green'>Column '$column' exists ✓</li>";
    } else {
        echo "<li style='color:red'>Column '$column' is missing ✗</li>";
    }
}

echo "</ul>";

// Close connection
$conn->close();
?> 