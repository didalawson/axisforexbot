<?php
session_start();
require_once '../config/database.php';
require_once '../controllers/AuthController.php';
require_once '../helpers/EmailHelper.php';

$auth = new AuthController($conn);
$emailHelper = new EmailHelper();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    if (empty($email)) {
        $error = 'Please enter your email address.';
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Store reset token
            $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?");
            $stmt->bind_param("sss", $token, $expires, $email);
            $stmt->execute();
            
            // Send reset email
            if ($emailHelper->sendPasswordResetEmail($email, $token)) {
                $success = 'Password reset instructions have been sent to your email.';
            } else {
                $error = 'Failed to send reset email. Please try again later.';
            }
        } else {
            // Don't reveal if email exists
            $success = 'If an account exists with this email, you will receive password reset instructions.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - AxisBot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0a1f44 0%, #1a365d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .forgot-password-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #0a1f44;
            box-shadow: 0 0 0 0.2rem rgba(10, 31, 68, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #0a1f44 0%, #1a365d 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(10, 31, 68, 0.3);
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <h2 class="text-center mb-4">Forgot Password</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Send Reset Link</button>
            </div>
        </form>
        
        <div class="text-center mt-3">
            <a href="login.php" class="text-decoration-none">Back to Login</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 