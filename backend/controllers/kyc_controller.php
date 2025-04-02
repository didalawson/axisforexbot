<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/KYC.php';

header('Content-Type: application/json');

// Check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$kyc = new KYC();

// Handle KYC submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate input
        $required_fields = ['full_name', 'date_of_birth', 'address', 'phone_number', 'document_type', 'document_number'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Please fill in all required fields");
            }
        }

        // Validate file uploads
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
        $max_size = 5 * 1024 * 1024; // 5MB

        $files = ['document_front', 'document_back', 'selfie'];
        $uploaded_files = [];

        foreach ($files as $file) {
            if (!isset($_FILES[$file]) || $_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Please upload all required documents");
            }

            if (!in_array($_FILES[$file]['type'], $allowed_types)) {
                throw new Exception("Invalid file type. Only JPG, PNG, and PDF files are allowed");
            }

            if ($_FILES[$file]['size'] > $max_size) {
                throw new Exception("File size too large. Maximum size is 5MB");
            }

            // Generate unique filename
            $extension = pathinfo($_FILES[$file]['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '_' . $file . '.' . $extension;
            $upload_path = __DIR__ . '/../uploads/kyc/' . $filename;

            // Create directory if it doesn't exist
            if (!file_exists(__DIR__ . '/../uploads/kyc/')) {
                mkdir(__DIR__ . '/../uploads/kyc/', 0777, true);
            }

            // Move uploaded file
            if (!move_uploaded_file($_FILES[$file]['tmp_name'], $upload_path)) {
                throw new Exception("Error uploading file");
            }

            $uploaded_files[$file] = $filename;
        }

        // Save KYC submission
        $kyc_data = [
            'user_id' => $user_id,
            'full_name' => $_POST['full_name'],
            'date_of_birth' => $_POST['date_of_birth'],
            'address' => $_POST['address'],
            'phone_number' => $_POST['phone_number'],
            'document_type' => $_POST['document_type'],
            'document_number' => $_POST['document_number'],
            'document_front' => $uploaded_files['document_front'],
            'document_back' => $uploaded_files['document_back'],
            'selfie' => $uploaded_files['selfie'],
            'status' => 'pending'
        ];

        $kyc->submitKYC($kyc_data);
        echo json_encode(['success' => true, 'message' => 'KYC submitted successfully']);

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// Handle KYC status check
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $kyc_status = $kyc->getKYCStatus($user_id);
        echo json_encode(['success' => true, 'status' => $kyc_status]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
} 