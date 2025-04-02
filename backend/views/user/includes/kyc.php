<?php
// Use absolute paths to avoid directory resolution issues
require_once 'C:/xampp/htdocs/public_html/backend/config/config.php';
require_once 'C:/xampp/htdocs/public_html/backend/models/User.php';
require_once 'C:/xampp/htdocs/public_html/backend/models/KYC.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Get KYC status
    $kyc = new KYC();
    $kyc_result = $kyc->getKYCStatus($user_id);
    
    // Fix: Extract string status from array
    if ($kyc_result && is_array($kyc_result)) {
        $kyc_status = $kyc_result['status'];
        if (isset($kyc_result['rejection_reason'])) {
            $rejection_reason = $kyc_result['rejection_reason'];
        }
    } elseif ($kyc_result) {
        // In case the result is already a string
        $kyc_status = $kyc_result;
    } else {
        // No KYC submission yet
        $kyc_status = null;
    }
} 