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

// Check if withdrawals table exists
$tableExists = $conn->query("SHOW TABLES LIKE 'withdrawals'")->num_rows > 0;

// Create the table if it doesn't exist
if (!$tableExists) {
    $createTable = "CREATE TABLE IF NOT EXISTS withdrawals (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        wallet_address VARCHAR(255) NOT NULL,
        currency VARCHAR(50) NOT NULL,
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->query($createTable);
}

// Handle withdrawal request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount'] ?? 0);
    $currency = $_POST['currency'] ?? 'USDT';
    $walletAddress = $_POST['wallet_address'] ?? '';

    $error = null;
    $success = null;

    // Validate input
    if ($amount <= 0) {
        $error = "Please enter a valid amount.";
    } elseif (empty($walletAddress)) {
        $error = "Please enter your wallet address.";
    } elseif ($balance <= 0) {
        $error = "You don't have any funds to withdraw.";
    } elseif ($amount > $balance) {
        $error = "Withdrawal amount exceeds your available balance of $" . number_format($balance, 2);
    } else {
        try {
            // Start transaction
            $conn->begin_transaction();

            // Insert withdrawal request
            $stmt = $conn->prepare("INSERT INTO withdrawals (user_id, amount, wallet_address, currency) VALUES (?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("idss", $userId, $amount, $walletAddress, $currency);

                if ($stmt->execute()) {
                    // Update user balance (reduce by withdrawal amount)
                    $updateStmt = $conn->prepare("UPDATE user_balance SET balance = balance - ? WHERE user_id = ?");
                    if ($updateStmt) {
                        $updateStmt->bind_param("di", $amount, $userId);
                        if ($updateStmt->execute()) {
                            // Commit transaction
                            $conn->commit();
                            $success = "Withdrawal request submitted successfully.";

                            // Update balance variable for display
                            $balance -= $amount;
                        } else {
                            throw new Exception("Error updating balance.");
                        }
                    } else {
                        throw new Exception("Error preparing balance update statement.");
                    }
                } else {
                    throw new Exception("Error executing withdrawal statement.");
                }
            } else {
                throw new Exception("Error preparing withdrawal statement.");
            }
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $error = "Error submitting withdrawal request: " . $e->getMessage();
        }
    }
}

// Fetch user's withdrawal history
$withdrawals = [];
if ($tableExists) {
    $stmt = $conn->prepare("SELECT * FROM withdrawals WHERE user_id = ? ORDER BY created_at DESC");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $withdrawals = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>