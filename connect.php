<?php
// Start session (only once)
session_start();
ini_set("error_log", "error.log");
error_reporting(E_ALL);


require_once "constants.php";
/**
 * HTML to PHP Connector
 * This file serves as a bridge between the HTML frontend and PHP backend
 */





// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

// Include necessary backend files with correct paths
require_once  'backend/controllers/AuthController.php';

require_once  'backend/controllers/ContactController.php';

require_once  'backend/helpers/EmailHelper.php';


// Initialize controllers
$auth = new AuthController($conn);
$contact = new ContactController($conn);
$emailHelper = new EmailHelper();

/**
 * Function to handle login from HTML forms
 */
function handleLogin($email, $password)
{
    global $auth;
    $result = $auth->login($email, $password);

    if ($result['success']) {
        // Redirect based on role
        header('Location: ' . ($result['role'] === 'admin' ? 'backend/views/admin/dashboard.php' : 'backend/views/user/dashboard.php'));
        exit();
    } else {
        return $result['message'];
    }
}

/**
 * Function to handle registration from HTML forms
 */
function handleRegistration($firstName, $lastName, $email, $password, $referralId = null)
{
    global $auth, $emailHelper;

    $result = $auth->register($firstName, $lastName, $email, $password, $referralId);

    if ($result['success']) {
        // Check if verification token is returned
        if (isset($result['verification_token'])) {
            $verificationToken = $result['verification_token'];

            // Send verification email with the token
            if ($emailHelper->sendVerificationEmail($email, $result['verification_token'])) {
                return [
                    'success' => true,
                    'message' => 'Registration successful! A verification link has been sent to your email.',
                    'role' => $result['role']
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Error sending email',
                ];
            }
        } else {
            // No verification needed or verification not supported
            return [
                'success' => true,
                'message' => 'Registration successful! You can now log in to your account.',
                'role' => $result['role']
            ];
        }
    } else {
        return [
            'success' => false,
            'message' => $result['message']
        ];
    }
}

/**
 * Function to handle contact form submissions
 */
function handleContactForm($name, $email, $subject, $message)
{
    global $contact;

    $result = $contact->submitMessage($name, $email, $subject, $message);

    if ($result['success']) {
        return [
            'success' => true,
            'message' => 'Thank you for your message. We will get back to you soon!'
        ];
    } else {
        return [
            'success' => false,
            'message' => $result['message']
        ];
    }
}

/**
 * Function to check if user is logged in
 */
function isLoggedIn()
{
    global $auth;
    return $auth->isLoggedIn();
}

/**
 * Function to check if user is admin
 */
function isAdmin()
{
    global $auth;
    return $auth->isAdmin();
}

/**
 * Function to get current user data
 */
function getCurrentUser()
{
    global $conn;

    if (isset($_SESSION['user_id'])) {
        $stmt = $conn->prepare("SELECT id, first_name, last_name, email, role FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
    }

    return null;
}
