<?php
require_once 'admin_auth.php';
require_once '../config/database.php';

// Check if wallet_settings table exists
$tableExists = $conn->query("SHOW TABLES LIKE 'wallet_settings'")->num_rows > 0;

// Create the table if it doesn't exist
if (!$tableExists) {
    $createTable = "CREATE TABLE IF NOT EXISTS wallet_settings (
        id INT PRIMARY KEY AUTO_INCREMENT,
        crypto_name VARCHAR(50) NOT NULL,
        crypto_symbol VARCHAR(10) NOT NULL,
        wallet_address VARCHAR(255) NOT NULL,
        network_type VARCHAR(50) NOT NULL,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->query($createTable);
    
    // Insert default wallet addresses
    $defaultWallets = [
        ['Bitcoin', 'BTC', '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa', 'Bitcoin'],
        ['Ethereum', 'ETH', '0x742d35Cc6634C0532925a3b844Bc454e4438f44e', 'Ethereum'],
        ['USDT', 'USDT', '0x742d35Cc6634C0532925a3b844Bc454e4438f44e', 'TRC20'],
        ['USDT', 'USDT', '0x742d35Cc6634C0532925a3b844Bc454e4438f44e', 'ERC20']
    ];
    
    $stmt = $conn->prepare("INSERT INTO wallet_settings (crypto_name, crypto_symbol, wallet_address, network_type) VALUES (?, ?, ?, ?)");
    foreach ($defaultWallets as $wallet) {
        $stmt->bind_param("ssss", $wallet[0], $wallet[1], $wallet[2], $wallet[3]);
        $stmt->execute();
    }
}

// Handle form submission
$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            // Add new wallet
            $cryptoName = $_POST['crypto_name'] ?? '';
            $cryptoSymbol = $_POST['crypto_symbol'] ?? '';
            $walletAddress = $_POST['wallet_address'] ?? '';
            $networkType = $_POST['network_type'] ?? '';
            
            try {
                $stmt = $conn->prepare("INSERT INTO wallet_settings (crypto_name, crypto_symbol, wallet_address, network_type) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $cryptoName, $cryptoSymbol, $walletAddress, $networkType);
                $stmt->execute();
                $success = "Wallet address added successfully!";
            } catch (Exception $e) {
                $error = "Error adding wallet: " . $e->getMessage();
            }
        } elseif ($_POST['action'] === 'update') {
            // Update existing wallet
            $id = $_POST['wallet_id'] ?? '';
            $cryptoName = $_POST['crypto_name'] ?? '';
            $cryptoSymbol = $_POST['crypto_symbol'] ?? '';
            $walletAddress = $_POST['wallet_address'] ?? '';
            $networkType = $_POST['network_type'] ?? '';
            $isActive = isset($_POST['is_active']) ? 1 : 0;
            
            try {
                $stmt = $conn->prepare("UPDATE wallet_settings SET crypto_name = ?, crypto_symbol = ?, wallet_address = ?, network_type = ?, is_active = ? WHERE id = ?");
                $stmt->bind_param("ssssii", $cryptoName, $cryptoSymbol, $walletAddress, $networkType, $isActive, $id);
                $stmt->execute();
                $success = "Wallet address updated successfully!";
            } catch (Exception $e) {
                $error = "Error updating wallet: " . $e->getMessage();
            }
        } elseif ($_POST['action'] === 'delete') {
            // Delete wallet
            $id = $_POST['wallet_id'] ?? '';
            
            try {
                $stmt = $conn->prepare("DELETE FROM wallet_settings WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $success = "Wallet address deleted successfully!";
            } catch (Exception $e) {
                $error = "Error deleting wallet: " . $e->getMessage();
            }
        }
    }
}

// Fetch all wallet settings
$wallets = $conn->query("SELECT * FROM wallet_settings ORDER BY crypto_name, network_type")->fetch_all(MYSQLI_ASSOC);

// Initialize variables
$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate and sanitize input
        $min_deposit = filter_input(INPUT_POST, 'min_deposit', FILTER_VALIDATE_FLOAT);
        $min_withdrawal = filter_input(INPUT_POST, 'min_withdrawal', FILTER_VALIDATE_FLOAT);
        $withdrawal_fee = filter_input(INPUT_POST, 'withdrawal_fee', FILTER_VALIDATE_FLOAT);
        $btc_wallet = filter_input(INPUT_POST, 'btc_wallet', FILTER_SANITIZE_STRING);
        $eth_wallet = filter_input(INPUT_POST, 'eth_wallet', FILTER_SANITIZE_STRING);
        $usdt_wallet = filter_input(INPUT_POST, 'usdt_wallet', FILTER_SANITIZE_STRING);

        // Create settings table if it doesn't exist
        $sql = "CREATE TABLE IF NOT EXISTS wallet_settings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            setting_key VARCHAR(50) NOT NULL UNIQUE,
            setting_value TEXT NOT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $conn->query($sql);

        // Update or insert settings
        $settings = [
            'min_deposit' => $min_deposit,
            'min_withdrawal' => $min_withdrawal,
            'withdrawal_fee' => $withdrawal_fee,
            'btc_wallet' => $btc_wallet,
            'eth_wallet' => $eth_wallet,
            'usdt_wallet' => $usdt_wallet
        ];

        foreach ($settings as $key => $value) {
            $sql = "INSERT INTO wallet_settings (setting_key, setting_value) 
                    VALUES (?, ?) 
                    ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $key, $value);
            $stmt->execute();
        }

        $success_message = "Wallet settings updated successfully!";
    } catch (Exception $e) {
        error_log("Wallet Settings Error: " . $e->getMessage());
        $error_message = "Error updating wallet settings. Please try again.";
    }
}

// Fetch current settings
try {
    $settings = [];
    $result = $conn->query("SELECT setting_key, setting_value FROM wallet_settings");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
} catch (Exception $e) {
    error_log("Error fetching wallet settings: " . $e->getMessage());
    $error_message = "Error loading wallet settings.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet Settings - AxisBot Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <?php require_once __DIR__."/includes/styles.php"?>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <?php require_once __DIR__."/includes/admin_sidebar.php" ?>

        <!-- Main Content -->
        <div class="main-content">
            <?php
            $title = "Wallet Settings";
            require_once __DIR__."/includes/header.php"; ?>

            <div class="content-container">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <h2><i class="fas fa-wallet me-2"></i> Add New Wallet</h2>
                <form method="POST" class="mb-4">
                    <input type="hidden" name="action" value="add">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Cryptocurrency Name</label>
                                <input type="text" class="form-control" name="crypto_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Cryptocurrency Symbol</label>
                                <input type="text" class="form-control" name="crypto_symbol" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Wallet Address</label>
                        <input type="text" class="form-control" name="wallet_address" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Network Type</label>
                        <input type="text" class="form-control" name="network_type" required>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="submit-btn">Add Wallet</button>
                    </div>
                </form>

                <h2 class="mt-5"><i class="fas fa-list me-2"></i> Existing Wallets</h2>
                <div class="row">
                    <?php foreach ($wallets as $wallet): ?>
                        <div class="col-md-6">
                            <div class="wallet-card">
                                <div class="crypto-icon">
                                    <i class="fab fa-bitcoin"></i>
                                </div>
                                <h4><?php echo htmlspecialchars($wallet['crypto_name']); ?> (<?php echo htmlspecialchars($wallet['crypto_symbol']); ?>)</h4>
                                <div class="wallet-address">
                                    <?php echo htmlspecialchars($wallet['wallet_address']); ?>
                                </div>
                                <div>
                                    <span class="network-badge"><?php echo htmlspecialchars($wallet['network_type']); ?></span>
                                    <span class="status-badge <?php echo $wallet['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $wallet['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                                <div class="actions">
                                    <button type="button" class="edit-btn" onclick="editWallet(<?php echo htmlspecialchars(json_encode($wallet)); ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="wallet_id" value="<?php echo $wallet['id']; ?>">
                                        <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this wallet?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Wallet Modal -->
    <div class="modal fade" id="editWalletModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Wallet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="wallet_id" id="edit_wallet_id">
                        
                        <div class="form-group">
                            <label class="form-label">Cryptocurrency Name</label>
                            <input type="text" class="form-control" name="crypto_name" id="edit_crypto_name" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Cryptocurrency Symbol</label>
                            <input type="text" class="form-control" name="crypto_symbol" id="edit_crypto_symbol" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Wallet Address</label>
                            <input type="text" class="form-control" name="wallet_address" id="edit_wallet_address" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Network Type</label>
                            <input type="text" class="form-control" name="network_type" id="edit_network_type" required>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" id="edit_is_active">
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="submit-btn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editWallet(wallet) {
            document.getElementById('edit_wallet_id').value = wallet.id;
            document.getElementById('edit_crypto_name').value = wallet.crypto_name;
            document.getElementById('edit_crypto_symbol').value = wallet.crypto_symbol;
            document.getElementById('edit_wallet_address').value = wallet.wallet_address;
            document.getElementById('edit_network_type').value = wallet.network_type;
            document.getElementById('edit_is_active').checked = wallet.is_active == 1;
            
            new bootstrap.Modal(document.getElementById('editWalletModal')).show();
        }
    </script>
</body>
</html> 