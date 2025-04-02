<?php
require_once 'admin_auth.php';
require_once '../config/database.php';

// Enable debugging for development
define('DEBUG_MODE', true);

// Process investment form submission via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify database connection
    if (!isset($conn) || $conn->connect_error) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database connection failed: ' . (DEBUG_MODE ? ($conn->connect_error ?? 'Connection not established') : 'Contact administrator')
        ]);
        exit;
    }
    
    // Validate input
    $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0;
    $plan = isset($_POST['plan']) ? $_POST['plan'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : 'pending';
    $profitRate = isset($_POST['profit_rate']) ? (float)$_POST['profit_rate'] : 0;
    $duration = isset($_POST['duration']) ? (int)$_POST['duration'] : 0;
    
    if ($userId <= 0 || $amount <= 0 || empty($plan) || $profitRate <= 0 || $duration <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'All fields are required with valid values'
        ]);
        exit;
    }
    
    // Verify user exists
    try {
        $userCheck = $conn->prepare("SELECT id FROM users WHERE id = ?");
        if (!$userCheck) {
            throw new Exception("Error preparing user check query: " . $conn->error);
        }
        
        $userCheck->bind_param("i", $userId);
        if (!$userCheck->execute()) {
            throw new Exception("Error executing user check query: " . $userCheck->error);
        }
        
        $userResult = $userCheck->get_result();
        
        if ($userResult->num_rows === 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'User not found'
            ]);
            exit;
        }
        
        // Calculate returns based on profit rate
        $returns = $amount + ($amount * ($profitRate / 100) * $duration / 30); // Simplified calculation
        
        // Start transaction
        if (!$conn->begin_transaction()) {
            throw new Exception("Failed to start transaction: " . $conn->error);
        }
        
        // First check if investments table exists
        $tableCheck = $conn->query("SHOW TABLES LIKE 'investments'");
        if (!$tableCheck) {
            throw new Exception("Error checking for investments table: " . $conn->error);
        }
        
        if ($tableCheck->num_rows === 0) {
            // Create the investments table if it doesn't exist
            $createTable = "CREATE TABLE investments (
                id INT PRIMARY KEY AUTO_INCREMENT,
                user_id INT NOT NULL,
                amount DECIMAL(15,2) DEFAULT 0,
                plan VARCHAR(100) NOT NULL,
                status ENUM('pending', 'active', 'completed', 'cancelled') DEFAULT 'pending',
                profit_rate DECIMAL(5,2) DEFAULT 0,
                duration INT DEFAULT 0,
                returns DECIMAL(15,2) DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                completed_at TIMESTAMP NULL,
                KEY idx_user_id (user_id)
            )";
            
            if (!$conn->query($createTable)) {
                throw new Exception("Error creating investments table: " . $conn->error);
            }
            
            error_log("Created investments table");
        }
        
        // Insert the investment
        $stmt = $conn->prepare("INSERT INTO investments (user_id, amount, plan, status, profit_rate, duration, returns) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Error preparing insert statement: " . $conn->error);
        }
        
        $stmt->bind_param("idssidi", $userId, $amount, $plan, $status, $profitRate, $duration, $returns);
        if (!$stmt->execute()) {
            throw new Exception("Error inserting investment: " . $stmt->error);
        }
        
        // If status is active, update user_balance table
        if ($status === 'active') {
            // Check if user_balance table exists
            $balanceTableCheck = $conn->query("SHOW TABLES LIKE 'user_balance'");
            if (!$balanceTableCheck) {
                throw new Exception("Error checking for user_balance table: " . $conn->error);
            }
            
            if ($balanceTableCheck->num_rows === 0) {
                // Create the user_balance table if it doesn't exist
                $createBalanceTable = "CREATE TABLE user_balance (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    user_id INT NOT NULL,
                    balance DECIMAL(15,2) DEFAULT 0,
                    active_deposit DECIMAL(15,2) DEFAULT 0,
                    profit DECIMAL(15,2) DEFAULT 0,
                    bonus DECIMAL(15,2) DEFAULT 0,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    UNIQUE KEY unique_user (user_id)
                )";
                
                if (!$conn->query($createBalanceTable)) {
                    throw new Exception("Error creating user_balance table: " . $conn->error);
                }
                
                error_log("Created user_balance table");
            }
            
            // Update user balance
            $balanceCheck = $conn->prepare("SELECT id FROM user_balance WHERE user_id = ?");
            if (!$balanceCheck) {
                throw new Exception("Error preparing balance check statement: " . $conn->error);
            }
            
            $balanceCheck->bind_param("i", $userId);
            if (!$balanceCheck->execute()) {
                throw new Exception("Error executing balance check query: " . $balanceCheck->error);
            }
            
            $balanceResult = $balanceCheck->get_result();
            
            if ($balanceResult->num_rows > 0) {
                // Update existing balance
                $balanceUpdate = $conn->prepare("UPDATE user_balance SET active_deposit = active_deposit + ? WHERE user_id = ?");
                if (!$balanceUpdate) {
                    throw new Exception("Error preparing balance update statement: " . $conn->error);
                }
                
                $balanceUpdate->bind_param("di", $amount, $userId);
                if (!$balanceUpdate->execute()) {
                    throw new Exception("Error updating balance: " . $balanceUpdate->error);
                }
            } else {
                // Create new balance record
                $balanceInsert = $conn->prepare("INSERT INTO user_balance (user_id, balance, active_deposit, profit, bonus) VALUES (?, 0, ?, 0, 0)");
                if (!$balanceInsert) {
                    throw new Exception("Error preparing balance insert statement: " . $conn->error);
                }
                
                $balanceInsert->bind_param("id", $userId, $amount);
                if (!$balanceInsert->execute()) {
                    throw new Exception("Error inserting balance: " . $balanceInsert->error);
                }
            }
        }
        
        // Commit transaction
        if (!$conn->commit()) {
            throw new Exception("Error committing transaction: " . $conn->error);
        }
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Investment added successfully'
        ]);
    } catch (Exception $e) {
        // Rollback transaction on error
        if (isset($conn) && !$conn->connect_error) {
            $conn->rollback();
        }
        
        error_log("Investment error: " . $e->getMessage());
        
        echo json_encode([
            'status' => 'error',
            'message' => 'Error adding investment: ' . (DEBUG_MODE ? $e->getMessage() : 'Please try again later')
        ]);
    }
    
    exit;
}

// If accessed directly, redirect to user management page
header('Location: user.php');
exit; 