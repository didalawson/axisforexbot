<?php

require_once __DIR__ . "/../constants.php";


// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected to MySQL server successfully.<br>";

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sql) === TRUE) {
    echo "Database created or already exists.<br>";
    $conn->select_db(DB_NAME);
} else {
    die("Error creating database: " . $conn->error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

// Create admins table
$sql = "CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Admins table created or already exists.<br>";
    
    // Check if default admin exists
    $checkAdmin = $conn->query("SELECT id FROM admins WHERE username = 'admin'");
    if ($checkAdmin->num_rows === 0) {
        // Create default admin (username: admin, password: admin123)
        $defaultUsername = 'admin';
        $defaultPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $defaultEmail = 'admin@axisbot.com';
        
        $insertAdmin = "INSERT INTO admins (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertAdmin);
        $stmt->bind_param("sss", $defaultUsername, $defaultPassword, $defaultEmail);
        $stmt->execute();
        echo "Default admin account created.<br>";
    }
} else {
    die("Error creating admins table: " . $conn->error);
}

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    status ENUM('active', 'suspended') DEFAULT 'active',
    email_verified BOOLEAN DEFAULT TRUE,
    verification_token VARCHAR(100),
    reset_token VARCHAR(100),
    reset_token_expires DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Users table created or already exists.<br>";
} else {
    die("Error creating users table: " . $conn->error);
}

// Create user_balance table
$sql = "CREATE TABLE IF NOT EXISTS user_balance (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    balance DECIMAL(15,2) DEFAULT 0,
    active_deposit DECIMAL(15,2) DEFAULT 0,
    profit DECIMAL(15,2) DEFAULT 0,
    bonus DECIMAL(15,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user (user_id)
)";

if ($conn->query($sql) === TRUE) {
    echo "User balance table created or already exists.<br>";
} else {
    die("Error creating user_balance table: " . $conn->error);
}

// Create investments table
$sql = "CREATE TABLE IF NOT EXISTS investments (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    plan VARCHAR(50) NOT NULL,
    status ENUM('pending', 'active', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Investments table created or already exists.<br>";
} else {
    die("Error creating investments table: " . $conn->error);
}

// Create deposits table
$sql = "CREATE TABLE IF NOT EXISTS deposits (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Deposits table created or already exists.<br>";
} else {
    die("Error creating deposits table: " . $conn->error);
}

// Create withdrawals table
$sql = "CREATE TABLE IF NOT EXISTS withdrawals (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Withdrawals table created or already exists.<br>";
} else {
    die("Error creating withdrawals table: " . $conn->error);
}

// Create login_history table
$sql = "CREATE TABLE IF NOT EXISTS login_history (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Login history table created or already exists.<br>";
} else {
    die("Error creating login_history table: " . $conn->error);
}

// Create contact_messages table
$sql = "CREATE TABLE IF NOT EXISTS contact_messages (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Contact messages table created or already exists.<br>";
} else {
    die("Error creating contact_messages table: " . $conn->error);
}

echo "Database setup completed successfully!";
$conn->close();
?> 