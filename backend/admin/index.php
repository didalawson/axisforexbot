<?php
session_start();
require_once '../config/database.php';

// Redirect to dashboard if already logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Check if the admin exists
        $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $admin['password'])) {
                // Set session variables
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                
                // Redirect to dashboard
                header('Location: dashboard.php');
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    }
}

// Check if admins table exists and create default admin if not
$adminTableExists = $conn->query("SHOW TABLES LIKE 'admins'")->num_rows > 0;

if (!$adminTableExists) {
    // Create admins table
    $createAdminTable = "CREATE TABLE IF NOT EXISTS admins (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->query($createAdminTable);
    
    // Create default admin (username: administrator, password: email@admin)
    $defaultUsername = 'administrator';
    $defaultPassword = password_hash('email@admin', PASSWORD_DEFAULT);
    $insertDefaultAdmin = "INSERT INTO admins (username, password) VALUES ('$defaultUsername', '$defaultPassword')";
    $conn->query($insertDefaultAdmin);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management - AxisBot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3a7bd5;
            --secondary-color: #00d2ff;
        }
        body {
            background: linear-gradient(135deg, #3a7bd5, #00d2ff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            width: 400px;
            padding: 40px;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-logo h2 {
            font-weight: 700;
            color: #333;
            margin: 0;
        }
        .login-logo span {
            color: var(--secondary-color);
        }
        .login-logo p {
            color: #777;
            margin-top: 5px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            height: 50px;
            border-radius: 5px;
            border: 1px solid #ddd;
            padding-left: 15px;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(58, 123, 213, 0.25);
        }
        .login-btn {
            background: linear-gradient(to right, #3a7bd5, #00d2ff);
            color: white;
            border: none;
            height: 50px;
            border-radius: 5px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            width: 100%;
        }
        .login-btn:hover {
            background: linear-gradient(to right, #3a7bd5, #3a7bd5);
        }
        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-right: none;
        }
        .form-credential-note {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #777;
        }
        .back-to-website {
            text-align: center;
            margin-top: 20px;
        }
        .back-to-website a {
            color: var(--primary-color);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <h2>AXIS<span>BOT</span></h2>
            <p>Admin Management Portal</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                </div>
            </div>
            
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
            </div>
            
            <button type="submit" class="login-btn">Login</button>
        </form>
        
        <div class="back-to-website">
            <a href="../index.php"><i class="fas fa-arrow-left me-1"></i> Back to Website</a>
        </div>
    </div>
</body>
</html> 