<?php
require_once '../../connect.php';

$message = '';
$status = '';

// Check if token is provided
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];

    // Find user with this token
    $stmt = $conn->prepare("SELECT id, email, email_verified FROM users WHERE verification_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Check if already verified
        if ($user['email_verified'] == 1) {
            $message = 'Your email is already verified. You can log in to your account.';
            $status = 'info';
        } else {
            // Update user as verified
            $stmt = $conn->prepare("UPDATE users SET email_verified = 1, verification_token = NULL WHERE id = ?");
            $stmt->bind_param("i", $user['id']);

            if ($stmt->execute()) {
                $message = 'Your email has been verified successfully! You can now log in to your account.';
                $status = 'success';
            } else {
                $message = 'Error verifying your email. Please try again later.';
                $status = 'danger';
            }
        }
    } else {
        $message = 'Invalid verification token. Please check your email or contact support.';
        $status = 'danger';
    }
} else {
    $message = 'Verification token is missing.';
    $status = 'danger';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - AxisBot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0a1f44 0%, #1a365d 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .verify-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 40px;
            text-align: center;
            max-width: 600px;
            width: 90%;
        }

        .verify-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }

        .success .verify-icon {
            color: #28a745;
        }

        .danger .verify-icon {
            color: #dc3545;
        }

        .info .verify-icon {
            color: #17a2b8;
        }

        h2 {
            margin-bottom: 20px;
            color: #0a1f44;
        }

        p {
            margin-bottom: 30px;
            font-size: 18px;
            color: #333;
        }

        .btn-login {
            background: linear-gradient(to right, #0a1f44, #1a365d);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(10, 31, 68, 0.3);
        }

        .logo {
            margin-bottom: 30px;
        }

        .logo img {
            height: 50px;
        }
    </style>
</head>

<body>
    <div class="verify-container <?php echo $status; ?>">
        <div class="logo">
            <img src="../../images/logos/logo1.png" alt="AxisBot Logo">
        </div>

        <div class="verify-icon">
            <?php if ($status === 'success'): ?>
                ✓
            <?php elseif ($status === 'danger'): ?>
                ✗
            <?php else: ?>
                ℹ
            <?php endif; ?>
        </div>

        <h2>Email Verification</h2>
        <p><?php echo $message; ?></p>

        <a href="../../login.php" class="btn-login">Go to Login</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>