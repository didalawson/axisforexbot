<?php
require_once __DIR__ . '/../config/database.php';

// Check if admin exists
$stmt = $conn->prepare("SELECT id, username FROM admins WHERE username = ?");
$username = 'administrator';
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Admin doesn't exist, create new one
    $password = password_hash('email@admin', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    
    if ($stmt->execute()) {
        echo "New admin account created successfully!<br>";
        echo "Username: administrator<br>";
        echo "Password: email@admin<br>";
    } else {
        echo "Error creating admin account: " . $conn->error;
    }
} else {
    // Admin exists, update password
    $password = password_hash('email@admin', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $password, $username);
    
    if ($stmt->execute()) {
        echo "Admin password updated successfully!<br>";
        echo "Username: administrator<br>";
        echo "New Password: email@admin<br>";
    } else {
        echo "Error updating admin password: " . $conn->error;
    }
}

// Display all admin accounts
echo "<br>All admin accounts:<br>";
$result = $conn->query("SELECT id, username FROM admins");
while ($row = $result->fetch_assoc()) {
    echo "ID: " . $row['id'] . ", Username: " . $row['username'] . "<br>";
}

$conn->close();
?> 