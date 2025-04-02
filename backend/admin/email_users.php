<?php
require_once 'admin_auth.php';
require_once '../config/database.php';

// Handle form submission
$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    $recipients = $_POST['recipients'] ?? [];
    
    if (empty($subject) || empty($message) || empty($recipients)) {
        $error = "Please fill in all fields and select at least one recipient.";
    } else {
        // Get company email from settings
        $companyEmail = $conn->query("SELECT setting_value FROM company_settings WHERE setting_name = 'company_email'")->fetch_assoc()['setting_value'] ?? 'noreply@axisbot.com';
        
        // Get selected users' emails
        $userEmails = [];
        if (in_array('all', $recipients)) {
            $result = $conn->query("SELECT email FROM users");
            while ($row = $result->fetch_assoc()) {
                $userEmails[] = $row['email'];
            }
        } else {
            $placeholders = str_repeat('?,', count($recipients) - 1) . '?';
            $stmt = $conn->prepare("SELECT email FROM users WHERE id IN ($placeholders)");
            $stmt->bind_param(str_repeat('i', count($recipients)), ...$recipients);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $userEmails[] = $row['email'];
            }
        }
        
        // Send emails
        $headers = "From: $companyEmail\r\n";
        $headers .= "Reply-To: $companyEmail\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        $successCount = 0;
        $failCount = 0;
        
        foreach ($userEmails as $email) {
            if (mail($email, $subject, $message, $headers)) {
                $successCount++;
            } else {
                $failCount++;
            }
        }
        
        if ($successCount > 0) {
            $success = "Successfully sent $successCount email(s)";
            if ($failCount > 0) {
                $success .= ". Failed to send $failCount email(s).";
            }
        } else {
            $error = "Failed to send any emails. Please check your server's mail configuration.";
        }
    }
}

// Fetch all users for the recipient list
$users = $conn->query("SELECT id, username, email FROM users ORDER BY username")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Users - AxisBot Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #3a7bd5;
            --secondary-color: #00d2ff;
            --dark-color: #343a40;
        }
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: var(--sidebar-width);
            background: var(--dark-color);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1000;
        }
        .sidebar .logo {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        .sidebar .logo h3 {
            color: white;
            margin: 0;
            font-size: 20px;
        }
        .sidebar .logo span {
            color: var(--secondary-color);
        }
        .sidebar .menu-items {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar .menu-items li {
            margin-bottom: 5px;
        }
        .sidebar .menu-items a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            transition: all 0.3s;
        }
        .sidebar .menu-items a:hover,
        .sidebar .menu-items a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left: 3px solid var(--secondary-color);
        }
        .sidebar .menu-items a i {
            margin-right: 10px;
            font-size: 18px;
            width: 25px;
            text-align: center;
        }
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header .user-info {
            display: flex;
            align-items: center;
        }
        .header .user-info .dropdown-toggle {
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }
        .header .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .content-container {
            background: white;
            border-radius: 5px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .content-container h2 {
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            height: 45px;
            border-radius: 5px;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(58, 123, 213, 0.25);
        }
        .submit-btn {
            background: linear-gradient(to right, #3a7bd5, #00d2ff);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .submit-btn:hover {
            background: linear-gradient(to right, #3a7bd5, #3a7bd5);
            color: white;
        }
        .recipients-list {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
        }
        .recipient-item {
            display: flex;
            align-items: center;
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .recipient-item:last-child {
            border-bottom: none;
        }
        .recipient-item input[type="checkbox"] {
            margin-right: 10px;
        }
        .recipient-item .user-info {
            flex: 1;
        }
        .recipient-item .user-email {
            font-size: 14px;
            color: #666;
        }
        .select-all-container {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 10px;
        }
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            .sidebar.active {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            .main-content.active {
                margin-left: 250px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h3>AXIS<span>BOT</span> Admin</h3>
            </div>
            <ul class="menu-items">
                <li>
                    <a href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="company_settings.php">
                        <i class="fas fa-building"></i> Company Settings
                    </a>
                </li>
                <li>
                    <a href="manage_users.php">
                        <i class="fas fa-users"></i> Manage Users
                    </a>
                </li>
                <li>
                    <a href="deposits.php">
                        <i class="fas fa-money-bill-wave"></i> Deposits
                    </a>
                </li>
                <li>
                    <a href="withdrawals.php">
                        <i class="fas fa-hand-holding-usd"></i> Withdrawals
                    </a>
                </li>
                <li>
                    <a href="wallet_settings.php">
                        <i class="fas fa-wallet"></i> Wallet Settings
                    </a>
                </li>
                <li>
                    <a href="email_users.php" class="active">
                        <i class="fas fa-envelope"></i> Email Users
                    </a>
                </li>
                <li>
                    <a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Email Users</h1>
                <div class="user-info">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="../assets/images/admin-avatar.png" alt="Admin Avatar">
                            <span><?php echo htmlspecialchars($adminUsername); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="change_password.php"><i class="fas fa-key me-2"></i> Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="content-container">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <h2><i class="fas fa-envelope me-2"></i> Send Email to Users</h2>
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Subject</label>
                        <input type="text" class="form-control" name="subject" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" name="message" rows="10" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Recipients</label>
                        <div class="recipients-list">
                            <div class="select-all-container">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                    <label class="form-check-label" for="selectAll">Select All Users</label>
                                </div>
                            </div>
                            <?php foreach ($users as $user): ?>
                                <div class="recipient-item">
                                    <input type="checkbox" class="form-check-input" name="recipients[]" value="<?php echo $user['id']; ?>" id="user_<?php echo $user['id']; ?>">
                                    <div class="user-info">
                                        <div><?php echo htmlspecialchars($user['username']); ?></div>
                                        <div class="user-email"><?php echo htmlspecialchars($user['email']); ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="submit-btn">Send Email</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="recipients[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
</body>
</html> 