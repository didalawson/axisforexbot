<?php
include_once 'connect.php';
include_once __DIR__ . '/backend/helpers/EmailHelper.php';
$emailHelper = new EmailHelper();
// Check if user is already logged in
if (isLoggedIn()) {
    header('Location: ' . (isAdmin() ? 'backend/views/admin/dashboard.php' : 'backend/views/user/dashboard.php'));
    exit();
}

$error = '';
$success = '';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $referralId = $_POST['referral_id'] ?? '';

    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'Please fill in all required fields.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } else {
        $result = handleRegistration($firstName, $lastName, $email, $password, $referralId);

        if ($result['success']) {
            // Show success message instead of immediate redirect

            // if ($emailHelper->sendVerificationEmail($recipientEmail, $result['verification_token'])) {
            $success = 'Registration successful! A verification link has been sent to your email.';
            // } else {
            //     $error = 'Failed to send Verification email.';
            // }
            // We'll handle the redirect with JavaScript after showing the message
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AxisBot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0a1f44 0%, #1a365d 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header styling */
        header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: transparent;
            z-index: 1000;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            height: 40px;
            margin-right: 10px;
            transition: transform 0.3s;
        }

        .logo:hover img {
            transform: scale(1.05);
        }

        .logo a {
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
            font-size: 24px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }

        .logo a:hover {
            color: #f0f0f0;
            transform: scale(1.05);
        }

        /* Registration section styling */
        .register-section {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 50px 20px;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 600px;
            margin-top: 20px;
        }

        .register-header {
            background: linear-gradient(to right, #0a1f44, #1a365d);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .register-form {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #0a1f44;
            box-shadow: 0 0 0 0.2rem rgba(10, 31, 68, 0.25);
        }

        .btn-register {
            background: linear-gradient(to right, #0a1f44, #1a365d);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(10, 31, 68, 0.3);
        }

        .login-link {
            margin-top: 15px;
            text-align: center;
        }

        .alert {
            margin-bottom: 20px;
            padding: 10px 15px;
            border-radius: 8px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .name-row {
            display: flex;
            gap: 15px;
        }

        @media (max-width: 576px) {
            .name-row {
                flex-direction: column;
                gap: 0;
            }
        }

        /* Added styles for the success popup */
        .success-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            padding: 30px;
            text-align: center;
            z-index: 2000;
            max-width: 400px;
            width: 90%;
        }

        .success-popup h3 {
            color: #155724;
            margin-bottom: 20px;
        }

        .success-popup p {
            margin-bottom: 25px;
        }

        .popup-icon {
            font-size: 60px;
            color: #28a745;
            margin-bottom: 20px;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1999;
        }

        .success-btn {
            background: linear-gradient(to right, #28a745, #218838);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .success-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        /* Referral ID field styling */
        .referral-group {
            margin-bottom: 20px;
            border: 1px dashed #1a365d;
            padding: 15px;
            border-radius: 8px;
            background-color: rgba(10, 31, 68, 0.05);
        }

        .referral-group label {
            color: #1a365d;
            font-weight: 600;
        }

        .referral-info {
            font-size: 0.85rem;
            color: #555;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">
            <a href="index.html">
                <img src="images/logos/logo1.png" alt="AxisBot Logo">
                <span>AxisBot</span>
            </a>
        </div>
    </header>

    <?php if (!empty($success)): ?>
        <!-- Success popup overlay -->
        <div class="overlay"></div>

        <!-- Success popup content -->
        <div class="success-popup">
            <div class="popup-icon">✓</div>
            <h3>Registration Successful!</h3>
            <p>Your account has been created successfully!</p>
            <p>A verification link has been sent to your email address. Please check your inbox and verify your account.</p>
            <button class="success-btn" id="redirectBtn">Continue to Login</button>
        </div>
    <?php endif; ?>

    <section class="register-section">
        <div class="register-container">
            <div class="register-header">
                <h2>Create Your Account</h2>
            </div>
            <div class="register-form">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if (empty($success)): ?>
                    <form method="POST" action="">
                        <div class="name-row">
                            <div class="form-group flex-fill">
                                <label for="first_name">First Name</label>
                                <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Enter your first name" required>
                            </div>

                            <div class="form-group flex-fill">
                                <label for="last_name">Last Name</label>
                                <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Enter your last name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Create a password" required>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm your password" required>
                        </div>

                        <div class="referral-group">
                            <label for="referral_id">Referral ID</label>
                            <input type="text" id="referral_id" name="referral_id" class="form-control" placeholder="Enter referral ID if you have one">
                            <div class="referral-info">
                                <i>Optional: Enter a referral ID if someone invited you to join AxisBot</i>
                            </div>
                        </div>

                        <button type="submit" class="btn-register">Create Account</button>

                        <div class="login-link">
                            <p>Already have an account? <a href="login.php">Login</a></p>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php if (!empty($success)): ?>
        <script>
            // Redirect to login page after button click or automatically after 5 seconds
            document.getElementById('redirectBtn').addEventListener('click', function() {
                window.location.href = 'login.php';
            });

            // Auto redirect after 5 seconds
            setTimeout(function() {
                window.location.href = 'login.php';
            }, 5000);
        </script>
    <?php endif; ?>
</body>

</html>