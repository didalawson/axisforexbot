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

// Get investment details from session
if (!isset($_SESSION['investment_details'])) {
    header('Location: make_investment.php');
    exit();
}

$investment = $_SESSION['investment_details'];
$amount = $investment['amount'];
$plan = $investment['plan'];

// Check if crypto_addresses table exists
$tableExists = $conn->query("SHOW TABLES LIKE 'crypto_addresses'")->num_rows > 0;

// Create the table if it doesn't exist
if (!$tableExists) {
    $createTable = "CREATE TABLE IF NOT EXISTS crypto_addresses (
        id INT PRIMARY KEY AUTO_INCREMENT,
        crypto_type ENUM('BTC', 'ETH', 'TRC') NOT NULL,
        address VARCHAR(255) NOT NULL,
        active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->query($createTable);
    
    // Insert sample addresses
    $insertAddresses = "INSERT INTO crypto_addresses (crypto_type, address) VALUES
        ('BTC', '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa'),
        ('ETH', '0x742d35Cc6634C0532925a3b844Bc454e4438f44e'),
        ('TRC', 'TXhZ41Z48qV9tN9tqXqXqXqXqXqXqXqXqX')";
    $conn->query($insertAddresses);
}

// Initialize cryptoAddresses array
$cryptoAddresses = [];

// Fetch crypto addresses from database
$stmt = $conn->prepare("SELECT * FROM crypto_addresses WHERE active = 1");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    $cryptoAddresses = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // If prepare fails, use a fallback with hardcoded addresses
    $cryptoAddresses = [
        ['crypto_type' => 'BTC', 'address' => '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa'],
        ['crypto_type' => 'ETH', 'address' => '0x742d35Cc6634C0532925a3b844Bc454e4438f44e'],
        ['crypto_type' => 'TRC', 'address' => 'TXhZ41Z48qV9tN9tqXqXqXqXqXqXqXqXqX']
    ];
}

// Create uploads/receipts directory if it doesn't exist
if (!is_dir('../../uploads/receipts/')) {
    mkdir('../../uploads/receipts/', 0777, true);
}

// Handle receipt upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['payment_receipt'])) {
    $file = $_FILES['payment_receipt'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    if (!in_array($file['type'], $allowedTypes)) {
        $error = "Invalid file type. Please upload a JPG, PNG, or PDF file.";
    } elseif ($file['size'] > $maxSize) {
        $error = "File too large. Maximum size is 5MB.";
    } else {
        $fileName = time() . '_' . $userId . '_' . $file['name'];
        $uploadPath = '../../uploads/receipts/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Check if investments table exists
            $investmentsTableExists = $conn->query("SHOW TABLES LIKE 'investments'")->num_rows > 0;
            
            if ($investmentsTableExists) {
                // Update investment record with receipt
                $stmt = $conn->prepare("UPDATE investments SET receipt_path = ?, status = 'pending_approval' WHERE user_id = ? AND amount = ? AND plan = ? AND status = 'pending' ORDER BY created_at DESC LIMIT 1");
                if ($stmt) {
                    $stmt->bind_param("sids", $fileName, $userId, $amount, $plan);
                    
                    if ($stmt->execute()) {
                        $success = "Payment receipt uploaded successfully. Waiting for admin approval.";
                        // Clear investment details from session
                        unset($_SESSION['investment_details']);
                    } else {
                        $error = "Error updating investment record.";
                    }
                } else {
                    $error = "Error preparing update statement.";
                }
            } else {
                $error = "Investments table doesn't exist. Please contact support.";
            }
        } else {
            $error = "Error uploading file.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Invoice - AxisBot</title>
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
        $title = "Payment Invoice";
        require_once  __DIR__."/includes/nav.php"; ?>

        <div class="container-fluid">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <div class="invoice-container">
                <div class="invoice-header">
                    <h5 class="mb-0">Payment Details</h5>
                </div>

                <div class="investment-details">
                    <h5>Investment Summary</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Amount:</strong> $<?php echo number_format($amount, 2); ?></p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Plan:</strong> <?php echo ucfirst($plan); ?> Plan</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Date:</strong> <?php echo date('M d, Y H:i'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="crypto-address">
                    <h5>Payment Addresses</h5>
                    <?php foreach ($cryptoAddresses as $address): ?>
                        <div class="mb-3">
                            <label class="form-label"><?php echo strtoupper($address['crypto_type']); ?> Address:</label>
                            <div class="address-box">
                                <?php echo htmlspecialchars($address['address']); ?>
                            </div>
                            <button class="copy-btn" onclick="copyAddress('<?php echo htmlspecialchars($address['address']); ?>')">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="receipt-upload">
                    <h5>Upload Payment Receipt</h5>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Payment Receipt (JPG, PNG, or PDF)</label>
                            <input type="file" class="form-control" name="payment_receipt" accept=".jpg,.jpeg,.png,.pdf" required>
                            <small class="text-muted">Maximum file size: 5MB</small>
                        </div>
                        <button type="submit" class="upload-btn">
                            <i class="fas fa-upload"></i> Upload Receipt
                        </button>
                    </form>
                </div>
            </div>

            <div class="user-info">
                <img src="<?= ASSET_URL?>/assets/avatar-1.png" alt="User Avatar" style="width: 28px; height: 28px; border-radius: 50%; margin-right: 8px; object-fit: cover;">
                <span>Hi, <?php echo htmlspecialchars($username); ?></span>
            </div>
        </div>

        <footer class="text-center text-muted py-4">
            <small>Copyright © 2015-2025. All Rights Reserved</small>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyAddress(address) {
            navigator.clipboard.writeText(address).then(() => {
                alert('Address copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        }

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