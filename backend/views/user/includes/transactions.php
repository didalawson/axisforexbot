<?php
session_start();
require_once '../../config/database.php';
require_once '../../controllers/AuthController.php';

$auth = new AuthController($conn);

if (!$auth->isLoggedIn()) {
    header("Location: ".BASE_URL."/login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$firstName = $_SESSION['first_name'] ?? '';
$lastName = $_SESSION['last_name'] ?? '';
$email = $_SESSION['email'] ?? '';
$username = $firstName;

// Fetch user's transactions
$stmt = $conn->prepare("SELECT * FROM investments WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$transactions = $result->fetch_all(MYSQLI_ASSOC);
?>