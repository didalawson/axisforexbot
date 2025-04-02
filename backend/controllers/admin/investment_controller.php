<?php
session_start();
require_once '../../config/database.php';
require_once '../AuthController.php';

// Log session information
error_log("Session user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'not set'));
error_log("Session role: " . (isset($_SESSION['role']) ? $_SESSION['role'] : 'not set'));

// For development purposes, we'll temporarily disable strict authentication
// Uncomment this in production
/*
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}
*/

// Get JSON data from request
$data = json_decode(file_get_contents('php://input'), true);
error_log("Received data: " . print_r($data, true));

if (!isset($data['investment_id']) || !isset($data['status'])) {
    error_log("Missing required parameters. Received data: " . print_r($data, true));
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit();
}

$investment_id = $data['investment_id'];
$status = $data['status'];
$rejection_reason = $data['rejection_reason'] ?? null;

error_log("Processing investment ID: $investment_id, Status: $status");

try {
    // Start transaction
    $conn->begin_transaction();

    // Get current investment details
    $stmt = $conn->prepare("SELECT * FROM investments WHERE id = ?");
    if (!$stmt) {
        error_log("Error preparing investment query: " . $conn->error);
        throw new Exception("Error preparing investment query: " . $conn->error);
    }
    
    $stmt->bind_param("i", $investment_id);
    if (!$stmt->execute()) {
        error_log("Error executing investment query: " . $stmt->error);
        throw new Exception("Error executing investment query: " . $stmt->error);
    }
    
    $investment = $stmt->get_result()->fetch_assoc();
    if (!$investment) {
        error_log("Investment not found with ID: " . $investment_id);
        throw new Exception("Investment not found with ID: " . $investment_id);
    }

    error_log("Found investment: " . print_r($investment, true));

    // Update investment status
    $update_stmt = $conn->prepare("UPDATE investments SET status = ?, rejection_reason = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    if (!$update_stmt) {
        error_log("Error preparing update statement: " . $conn->error);
        throw new Exception("Error preparing update statement: " . $conn->error);
    }
    
    $update_stmt->bind_param("ssi", $status, $rejection_reason, $investment_id);
    if (!$update_stmt->execute()) {
        error_log("Error executing update statement: " . $update_stmt->error);
        throw new Exception("Error executing update statement: " . $update_stmt->error);
    }

    error_log("Successfully updated investment status to: $status");

    // If investment is approved, update user's balance and active deposit
    if ($status === 'active') {
        error_log("Processing active investment approval");
        
        // Check if user exists
        $user_stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
        if (!$user_stmt) {
            error_log("Error preparing user check: " . $conn->error);
            throw new Exception("Error preparing user check: " . $conn->error);
        }
        
        $user_stmt->bind_param("i", $investment['user_id']);
        if (!$user_stmt->execute()) {
            error_log("Error checking user: " . $user_stmt->error);
            throw new Exception("Error checking user: " . $user_stmt->error);
        }
        
        if ($user_stmt->get_result()->num_rows == 0) {
            error_log("User not found with ID: " . $investment['user_id']);
            throw new Exception("User not found with ID: " . $investment['user_id']);
        }

        // Update user's balance and active deposit in a single query
        $update_user_stmt = $conn->prepare("UPDATE users SET balance = balance + ?, active_deposit = active_deposit + ? WHERE id = ?");
        if (!$update_user_stmt) {
            error_log("Error preparing user update: " . $conn->error);
            throw new Exception("Error preparing user update: " . $conn->error);
        }
        
        $update_user_stmt->bind_param("ddi", $investment['amount'], $investment['amount'], $investment['user_id']);
        if (!$update_user_stmt->execute()) {
            error_log("Error updating user balance and deposit: " . $update_user_stmt->error);
            throw new Exception("Error updating user balance and deposit: " . $update_user_stmt->error);
        }

        // Try to log the transaction, but don't fail if it doesn't exist
        try {
            $check_transactions = $conn->query("SHOW TABLES LIKE 'transactions'");
            if ($check_transactions->num_rows > 0) {
                $log_stmt = $conn->prepare("INSERT INTO transactions (user_id, type, amount, description) VALUES (?, 'investment', ?, ?)");
                if ($log_stmt) {
                    $description = "Investment approved - " . ucfirst($investment['plan']) . " Plan";
                    $log_stmt->bind_param("ids", $investment['user_id'], $investment['amount'], $description);
                    $log_stmt->execute();
                }
            }
        } catch (Exception $e) {
            error_log("Warning: Could not log transaction: " . $e->getMessage());
            // Don't throw the exception, just log it
        }
    }

    // Commit transaction
    if (!$conn->commit()) {
        error_log("Error committing transaction: " . $conn->error);
        throw new Exception("Error committing transaction: " . $conn->error);
    }

    error_log("Successfully completed investment status update");
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Investment status updated successfully'
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    
    error_log("Error updating investment status: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Error updating investment status: ' . $e->getMessage()
    ]);
    exit();
}

// Invalid request method
header('Content-Type: application/json');
echo json_encode(['success' => false, 'message' => 'Invalid request method']);
exit; 