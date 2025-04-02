<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
require_once __DIR__ . '/../config/database.php';

/**
 * Check if admin is logged in
 */
function isAdminLoggedIn() {
    if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
        return false;
    }
    
    // Check session expiry (8 hours)
    if (!isset($_SESSION['last_activity']) || 
        (time() - $_SESSION['last_activity']) > (8 * 3600)) {
        return false;
    }
    
    return true;
}

/**
 * Get admin details from database
 */
function getAdminDetails($conn, $adminId) {
    try {
        $stmt = $conn->prepare("SELECT id, username, email FROM admins WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $adminId);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        $stmt->close();
        return $admin;
    } catch (Exception $e) {
        error_log("Error fetching admin details: " . $e->getMessage());
        return null;
    }
}

// If not logged in, redirect to login page
if (!isAdminLoggedIn()) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Get admin details
$adminId = $_SESSION['admin_id'];
$admin = getAdminDetails($conn, $adminId);

if (!$admin) {
    session_destroy();
    header("Location: login.php?error=invalid_session");
    exit();
}

// Make admin details available for use in pages
$adminUsername = htmlspecialchars($admin['username']);
$adminEmail = htmlspecialchars($admin['email']);
?> 