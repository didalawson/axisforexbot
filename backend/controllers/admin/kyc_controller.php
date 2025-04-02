<?php
require_once '../../config/database.php';
require_once '../../models/KYC.php';

// Check if user is logged in and is admin
session_start();
error_log("KYC Controller - User ID: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'not set'));
error_log("KYC Controller - User Role: " . (isset($_SESSION['role']) ? $_SESSION['role'] : 'not set'));

// Temporarily disable authentication check for development
// Comment this section back in for production
/*
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}
*/

// Process the POST request (KYC approval/rejection)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['kyc_id']) || !isset($data['status'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
        exit;
    }
    
    $kyc = new KYC();
    $rejection_reason = isset($data['rejection_reason']) ? $data['rejection_reason'] : null;
    
    $result = $kyc->updateKYCStatus($data['kyc_id'], $data['status'], $rejection_reason);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'KYC status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating KYC status']);
    }
    
    exit;
}

// Process the GET request (fetch KYC data)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');
    
    $kyc = new KYC();
    $status = isset($_GET['status']) ? $_GET['status'] : null;
    
    $kyc_submissions = $kyc->getAllKYCSubmissions($status);
    
    echo json_encode(['success' => true, 'data' => $kyc_submissions]);
    
    exit;
}

// Invalid request method
header('Content-Type: application/json');
echo json_encode(['success' => false, 'message' => 'Invalid request method']);
exit; 