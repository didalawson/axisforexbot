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
$myReferralId = $_SESSION['my_referral_id'] ?? strtolower($firstName . $lastName . $userId);
$referralLink = "https://axisbot.com/register.php?ref=" . urlencode($myReferralId);

// Check if user_balance table exists
$balanceTableExists = $conn->query("SHOW TABLES LIKE 'user_balance'")->num_rows > 0;

// Create balance table if it doesn't exist
if (!$balanceTableExists) {
    $createBalanceTable = "CREATE TABLE IF NOT EXISTS user_balance (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        balance DECIMAL(10,2) NOT NULL DEFAULT 0,
        active_deposit DECIMAL(10,2) NOT NULL DEFAULT 0,
        profit DECIMAL(10,2) NOT NULL DEFAULT 0,
        referral_bonus DECIMAL(10,2) NOT NULL DEFAULT 0,
        last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY (user_id)
    )";
    $conn->query($createBalanceTable);

    // Insert initial zero balance
    $insertBalance = "INSERT INTO user_balance (user_id, balance, active_deposit, profit, referral_bonus) 
                     VALUES ($userId, 0.00, 0.00, 0.00, 0.00)";
    $conn->query($insertBalance);
}

// Get user's current balance
$balance = 0;
$activeDeposit = 0;
$profit = 0;
$referralBonus = 0;

$stmt = $conn->prepare("SELECT * FROM user_balance WHERE user_id = ?");
if ($stmt) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $balance = $row['balance'];
        $activeDeposit = $row['active_deposit'];
        $profit = $row['profit'];
        $referralBonus = $row['referral_bonus'];
    } else {
        // Create balance record if not exists - with zero balances
        $insertStmt = $conn->prepare("INSERT INTO user_balance (user_id, balance, active_deposit, profit, referral_bonus) 
                                     VALUES (?, 0.00, 0.00, 0.00, 0.00)");
        if ($insertStmt) {
            $insertStmt->bind_param("i", $userId);
            $insertStmt->execute();
            // All values remain at zero
        }
    }
}
?>