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
    $dateOfBirth = $_POST['date_of_birth'] ?? null;

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

            if (!is_dir('../../uploads/avatars/')) {
                mkdir('../../uploads/avatars/', 0777, true);
            }

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

            // Create user_settings table if it doesn't exist
            if (!$tableExists) {
                $createTable = "CREATE TABLE IF NOT EXISTS user_settings (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    user_id INT NOT NULL,
                    first_name VARCHAR(50),
                    last_name VARCHAR(50),
                    phone VARCHAR(20),
                    country VARCHAR(100),
                    state VARCHAR(100),
                    city VARCHAR(100),
                    address TEXT,
                    avatar_path VARCHAR(255),
                    date_of_birth DATE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )";
                $conn->query($createTable);
                $tableExists = true;
            }

            // Check if user settings exist
            if ($settings) {
                // Update existing settings
                $stmt = $conn->prepare("UPDATE user_settings SET first_name = ?, last_name = ?, phone = ?, country = ?, state = ?, city = ?, address = ?, avatar_path = ?, date_of_birth = ? WHERE user_id = ?");
                if ($stmt) {
                    $stmt->bind_param("sssssssssi", $firstName, $lastName, $phone, $country, $state, $city, $address, $avatarPath, $dateOfBirth, $userId);
                } else {
                    throw new Exception("Error preparing update statement.");
                }
            } else {
                // Insert new settings
                $stmt = $conn->prepare("INSERT INTO user_settings (user_id, first_name, last_name, phone, country, state, city, address, avatar_path, date_of_birth) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("isssssssss", $userId, $firstName, $lastName, $phone, $country, $state, $city, $address, $avatarPath, $dateOfBirth);
                } else {
                    throw new Exception("Error preparing insert statement.");
                }
            }

            if ($stmt->execute()) {
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
                throw new Exception("Error executing statement.");
            }
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $error = "An error occurred while saving your settings: " . $e->getMessage();
        }
    }
}

// Get current avatar path
$avatarPath = isset($settings['avatar_path']) && !empty($settings['avatar_path']) 
    ? '../../uploads/avatars/' . $settings['avatar_path'] 
    : '../../assets/images/avatar.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings - AxisBot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?= require_once __DIR__."/includes/styles.php"; ?>
</head>
<body>
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <?= require_once __DIR__ . "/sidebar.php";  ?>

    <div class="main-content">

        <?php
        $title = "Account Settings";
        require_once  __DIR__."/includes/nav.php"; ?>

        <div class="container-fluid">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="avatar-upload">
                    <img src="<?php echo htmlspecialchars($avatarPath); ?>" alt="User Avatar" id="avatar-preview" style="width: 100%; height: 100%; object-fit: cover;">
                    <label class="upload-overlay" for="avatar-input">
                        <i class="fas fa-camera"></i>
                    </label>
                    <input type="file" id="avatar-input" name="avatar" accept="image/*">
                </div>
                <h4 class="mb-2"><?php echo htmlspecialchars($firstName . ' ' . $lastName); ?></h4>
                <p class="mb-0">Manage your account settings and preferences</p>
            </div>

            <!-- General Information -->
            <div class="settings-container">
                <div class="settings-header">
                    <h5 class="mb-0">General Information</h5>
                </div>
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($settings['first_name'] ?? $firstName); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($settings['last_name'] ?? $lastName); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" disabled>
                            <small class="text-muted">Email cannot be changed</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="date_of_birth" value="<?php echo htmlspecialchars($settings['date_of_birth'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Country</label>
                            <input type="text" class="form-control" name="country" value="<?php echo htmlspecialchars($settings['country'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">State/Province</label>
                            <input type="text" class="form-control" name="state" value="<?php echo htmlspecialchars($settings['state'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($settings['city'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="3"><?php echo htmlspecialchars($settings['address'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <h6 class="mt-4 mb-3">Location</h6>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">ZIP</label>
                            <input type="text" class="form-control" name="zip" placeholder="0">
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" name="save_general" class="save-btn">SAVE ALL</button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="settings-container">
                <div class="settings-header">
                    <h5 class="mb-0">Change Password</h5>
                </div>
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Old Password</label>
                            <input type="password" class="form-control" name="old_password" placeholder="Enter Old Password">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="new_password" placeholder="Enter Password">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                        </div>
                    </div>
                    <div class="text-start mt-4">
                        <button type="submit" name="change_password" class="save-btn">CHANGE PASSWORD</button>
                    </div>
                </form>
            </div>
        </div>

        <footer class="text-center text-muted py-4">
            <small>Copyright © 2015-2025. All Rights Reserved</small>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Avatar preview and upload functionality
        document.getElementById('avatar-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                    
                    // Create FormData and upload file
                    const formData = new FormData();
                    formData.append('avatar', file);
                    
                    // Send AJAX request
                    fetch('account_settings.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        alert('Avatar uploaded successfully!');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error uploading avatar. Please try again.');
                    });
                }
                reader.readAsDataURL(file);
            }
        });

        // Mobile Menu Toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        }

        mobileMenuToggle.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !mobileMenuToggle.contains(event.target) && 
                sidebar.classList.contains('active')) {
                toggleSidebar();
            }
        });

        // Close sidebar on window resize if open
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768 && sidebar.classList.contains('active')) {
                toggleSidebar();
            }
        });
    </script>
</body>
</html> 