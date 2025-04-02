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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['make_investment'])) {
        $amount = floatval($_POST['amount']);
        $plan = $_POST['plan'];
        $userEmail = $_POST['email'];

        // Validate amount based on plan
        $error = null;
        switch($plan) {
            case 'basic':
                if ($amount < 100 || $amount > 1000) {
                    $error = "Basic plan requires an investment between $100 and $1,000.";
                }
                break;
            case 'premium':
                if ($amount < 1001 || $amount > 5000) {
                    $error = "Premium plan requires an investment between $1,001 and $5,000.";
                }
                break;
            case 'vip':
                if ($amount < 5001) {
                    $error = "VIP plan requires a minimum investment of $5,001.";
                }
                break;
            default:
                $error = "Please select a valid investment plan.";
        }

        if (!$error) {
            try {
                // Start transaction
                $conn->begin_transaction();

                // Insert investment record
                $stmt = $conn->prepare("INSERT INTO investments (user_id, amount, plan, status, created_at) VALUES (?, ?, ?, 'pending', NOW())");
                $stmt->bind_param("ids", $userId, $amount, $plan);
                $stmt->execute();

                // Commit transaction
                $conn->commit();

                // Store investment details in session
                $_SESSION['investment_details'] = [
                    'amount' => $amount,
                    'plan' => $plan
                ];

                // Redirect to invoice page
                header('Location: invoice.php');
                exit();

            } catch (Exception $e) {
                // Rollback transaction on error
                $conn->rollback();
                $error = "An error occurred while processing your investment. Please try again.";
            }
        }
    }
}
?>