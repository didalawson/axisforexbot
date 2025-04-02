<?php
session_start();
require_once '../../config/database.php';
require_once '../../controllers/AuthController.php';
require_once '../../controllers/ContactController.php';

$auth = new AuthController($conn);
$contact = new ContactController($conn);

// Check if user is logged in and is admin
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header('Location: ../login.php');
    exit();
}

// Get contact messages
$messages = $contact->getMessages();
$newMessages = $contact->getMessages('new');
$readMessages = $contact->getMessages('read');
$repliedMessages = $contact->getMessages('replied');

// Get users
$users = $conn->query("SELECT id, first_name, last_name, email, role, created_at FROM users ORDER BY created_at DESC");

$userId = $_SESSION['user_id'];
$firstName = $_SESSION['first_name'] ?? '';
$lastName = $_SESSION['last_name'] ?? '';
$email = $_SESSION['email'] ?? '';
$username = $firstName;

// Initialize settings array
$settings = [];

// Check if user_settings table exists
$tableExists = $conn->query("SHOW TABLES LIKE 'user_settings'")->num_rows > 0;

if ($tableExists) {
    // Fetch existing user settings
    $stmt = $conn->prepare("SELECT * FROM user_settings WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $settings = $result->fetch_assoc();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $country = $_POST['country'] ?? '';
    $state = $_POST['state'] ?? '';
    $city = $_POST['city'] ?? '';
    $address = $_POST['address'] ?? '';

    // Handle avatar upload
    $avatarPath = $settings['avatar_path'] ?? null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['avatar'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            $error = "Invalid file type. Please upload a JPG or PNG file.";
        } elseif ($file['size'] > $maxSize) {
            $error = "File too large. Maximum size is 5MB.";
        } else {
            $fileName = time() . '_' . $userId . '_' . $file['name'];
            $uploadPath = '../../uploads/avatars/' . $fileName;

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $avatarPath = $fileName;
            } else {
                $error = "Error uploading avatar.";
            }
        }
    }

    if (!isset($error)) {
        try {
            // Start transaction
            $conn->begin_transaction();

            if ($tableExists) {
                // Check if user settings exist
                if ($settings) {
                    // Update existing settings
                    $stmt = $conn->prepare("UPDATE user_settings SET first_name = ?, last_name = ?, phone = ?, country = ?, state = ?, city = ?, address = ?, avatar_path = ? WHERE user_id = ?");
                    if ($stmt) {
                        $stmt->bind_param("ssssssssi", $firstName, $lastName, $phone, $country, $state, $city, $address, $avatarPath, $userId);
                    }
                } else {
                    // Insert new settings
                    $stmt = $conn->prepare("INSERT INTO user_settings (user_id, first_name, last_name, phone, country, state, city, address, avatar_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    if ($stmt) {
                        $stmt->bind_param("issssssss", $userId, $firstName, $lastName, $phone, $country, $state, $city, $address, $avatarPath);
                    }
                }

                if ($stmt && $stmt->execute()) {
                    // Update session variables
                    $_SESSION['first_name'] = $firstName;
                    $_SESSION['last_name'] = $lastName;

                    // Commit transaction
                    $conn->commit();
                    $success = "Settings updated successfully!";
                    
                    // Refresh settings data
                    $stmt = $conn->prepare("SELECT * FROM user_settings WHERE user_id = ?");
                    if ($stmt) {
                        $stmt->bind_param("i", $userId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $settings = $result->fetch_assoc();
                    }
                } else {
                    throw new Exception("Error saving settings.");
                }
            } else {
                throw new Exception("Database table not found. Please contact support.");
            }
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $error = "An error occurred while saving your settings. Please try again.";
        }
    }
}

// Get current avatar path
$avatarPath = $settings['avatar_path'] ?? '../../assets/images/avatar.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AxisBot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">AxisBot Admin Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="users.php">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="messages.php">Messages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kyc_management.php" style="font-weight: bold; color: #fff;">KYC Management</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <span class="navbar-text me-3">
                        Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!
                    </span>
                    <a href="../logout.php" class="btn btn-outline-light">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12 mb-4">
                <a href="kyc_management.php" class="btn btn-danger btn-lg btn-block py-3">
                    <i class="bi bi-shield-lock-fill me-2"></i>
                    KYC VERIFICATION MANAGEMENT - Click Here
                </a>
            </div>
        </div>
        
        <div class="row">
            <!-- Statistics Cards -->
            <div class="col-md-3 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">New Messages</h5>
                        <h2 class="card-text"><?php echo $newMessages['data']['total']; ?></h2>
                        <a href="messages.php?status=new" class="text-white">View Details →</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <h2 class="card-text"><?php echo $users->num_rows; ?></h2>
                        <a href="users.php" class="text-white">View Details →</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Read Messages</h5>
                        <h2 class="card-text"><?php echo $readMessages['data']['total']; ?></h2>
                        <a href="messages.php?status=read" class="text-white">View Details →</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Replied Messages</h5>
                        <h2 class="card-text"><?php echo $repliedMessages['data']['total']; ?></h2>
                        <a href="messages.php?status=replied" class="text-white">View Details →</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- KYC Management Card -->
            <div class="col-12 mb-4">
                <div class="card bg-danger text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">KYC Verification Management</h4>
                            <p class="card-text">Manage user verification documents and approve or reject KYC submissions</p>
                        </div>
                        <a href="kyc_management.php" class="btn btn-light btn-lg">Go to KYC Management →</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Messages -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Messages</h5>
                        <a href="messages.php" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>From</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($messages['data']['messages'] as $message): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($message['name']); ?></td>
                                        <td><?php echo htmlspecialchars($message['subject']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $message['status'] === 'new' ? 'primary' : 
                                                    ($message['status'] === 'read' ? 'warning' : 'success'); 
                                            ?>">
                                                <?php echo ucfirst($message['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($message['created_at'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Users</h5>
                        <a href="users.php" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($user = $users->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'danger' : 'primary'; ?>">
                                                <?php echo ucfirst($user['role']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 