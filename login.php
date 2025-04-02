<?php
echo 'jus testing shit';
include_once 'connect.php';
echo 'jus testing shit 2';
// Check if user is already logged in
if (isLoggedIn()) {
    header('Location: ' . (isAdmin() ? 'backend/views/admin/dashboard.php' : 'backend/views/user/dashboard.php'));
    exit();
}

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        $error = handleLogin($email, $password);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AxisBot</title>
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

        /* Login section styling */
        .login-section {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 50px 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            margin-top: 20px;
        }

        .login-header {
            background: linear-gradient(to right, #0a1f44, #1a365d);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .login-form {
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

        .btn-login {
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

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(10, 31, 68, 0.3);
        }

        .login-footer {
            text-align: center;
            padding: 20px 0;
            border-top: 1px solid #eee;
        }

        .social-login {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }

        .social-btn {
            margin: 0 10px;
            padding: 10px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }

        .social-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-google {
            background-color: #DB4437;
        }

        .btn-facebook {
            background-color: #4267B2;
        }

        .register-link {
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
    </style>
</head>

<body>
    <header>
        <div class="logo">
            <a href="index.html">
                <img src="images/logos/logoo.jpg" alt="AxisForexBot Logo">
                <span>AxisForexBot</span>
            </a>
        </div>
    </header>

    <section class="login-section">
        <div class="login-container">
            <div class="login-header">
                <h2>Login to Your Account</h2>
            </div>
            <div class="login-form">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>

                    <div class="form-group">
                        <a href="backend/views/forgot-password.php" class="text-decoration-none">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn-login">Login</button>

                    <div class="register-link">
                        <p>Don't have an account? <a href="register.php">Register Now</a></p>
                    </div>
                </form>
            </div>

            <div class="login-footer">
                <p>Or login with:</p>
                <div class="social-login">
                    <a href="#" class="social-btn btn-google">Google</a>
                    <a href="#" class="social-btn btn-facebook">Facebook</a>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>