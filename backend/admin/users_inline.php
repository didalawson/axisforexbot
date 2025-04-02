<?php
require_once 'admin_auth.php';
require_once '../config/database.php';

// Process user update
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_user') {
    $userId = $_POST['user_id'] ?? 0;
    $column = $_POST['column'] ?? '';
    $value = $_POST['value'] ?? 0;
    
    if (empty($userId) || empty($column)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
        exit;
    }
    
    // Validate column name to prevent SQL injection
    $allowedColumns = ['balance', 'active_deposit', 'profit', 'bonus'];
    if (!in_array($column, $allowedColumns)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid column name']);
        exit;
    }
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // First check if record exists
        $checkStmt = $conn->prepare("SELECT id FROM user_balance WHERE user_id = ?");
        $checkStmt->bind_param("i", $userId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows > 0) {
            // Update existing record
            $stmt = $conn->prepare("UPDATE user_balance SET $column = ? WHERE user_id = ?");
            $stmt->bind_param("di", $value, $userId);
        } else {
            // Insert new record with defaults
            $insertData = [
                'balance' => 0,
                'active_deposit' => 0,
                'profit' => 0,
                'bonus' => 0
            ];
            $insertData[$column] = $value;
            
            $stmt = $conn->prepare("INSERT INTO user_balance (user_id, balance, active_deposit, profit, bonus) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("idddd", $userId, $insertData['balance'], $insertData['active_deposit'], $insertData['profit'], $insertData['bonus']);
        }
        
        if ($stmt->execute()) {
            $conn->commit();
            echo json_encode(['status' => 'success', 'message' => 'Data updated successfully', 'value' => number_format($value, 2)]);
        } else {
            throw new Exception("Error executing query");
        }
        
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Error updating data: ' . $e->getMessage()]);
        exit;
    }
}

// Check if user_balance table exists, create if not
$checkTable = $conn->query("SHOW TABLES LIKE 'user_balance'");
if ($checkTable->num_rows === 0) {
    $createTable = "CREATE TABLE user_balance (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        balance DECIMAL(15,2) DEFAULT 0,
        active_deposit DECIMAL(15,2) DEFAULT 0,
        profit DECIMAL(15,2) DEFAULT 0,
        bonus DECIMAL(15,2) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_user (user_id)
    )";
    $conn->query($createTable);
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;
$offset = ($page - 1) * $perPage;

// Search functionality
$search = $_GET['search'] ?? '';
$searchCondition = '';
if (!empty($search)) {
    $searchCondition = " WHERE 
        u.first_name LIKE '%$search%' OR 
        u.last_name LIKE '%$search%' OR 
        u.email LIKE '%$search%' OR 
        u.username LIKE '%$search%'";
}

// Get total users for pagination
$totalQuery = "SELECT COUNT(*) as total FROM users u" . $searchCondition;
$totalResult = $conn->query($totalQuery);
$totalUsers = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalUsers / $perPage);

// Get users with balance data
$query = "SELECT 
    u.id, 
    u.username, 
    u.first_name, 
    u.last_name, 
    u.email, 
    u.referral_id,
    u.created_at,
    COALESCE(ub.balance, 0) as balance,
    COALESCE(ub.active_deposit, 0) as active_deposit,
    COALESCE(ub.profit, 0) as profit,
    COALESCE(ub.bonus, 0) as bonus
FROM 
    users u
LEFT JOIN 
    user_balance ub ON u.id = ub.user_id
$searchCondition
ORDER BY 
    u.id ASC
LIMIT 
    $offset, $perPage";

$result = $conn->query($query);

// Get investment data
$checkInvestmentTable = $conn->query("SHOW TABLES LIKE 'investments'");
$investments = [];

if ($checkInvestmentTable && $checkInvestmentTable->num_rows > 0) {
    $investmentQuery = "SELECT user_id, MAX(created_at) as last_investment, status 
                      FROM investments 
                      GROUP BY user_id";
    $investmentResult = $conn->query($investmentQuery);
    
    if ($investmentResult) {
        while ($row = $investmentResult->fetch_assoc()) {
            $investments[$row['user_id']] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - AxisBot Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
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
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #dee2e6;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .currency {
            font-family: monospace;
        }
        .action-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            margin-right: 5px;
        }
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .btn-primary {
            background: linear-gradient(to right, #3a7bd5, #00d2ff);
            border: none;
        }
        .editable {
            position: relative;
            cursor: pointer;
        }
        .editable:hover {
            background-color: #f8f9fa;
        }
        .editable.editing {
            padding: 0;
        }
        .edit-input {
            width: 100%;
            height: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .save-feedback {
            position: absolute;
            top: 0;
            right: 0;
            padding: 2px 5px;
            font-size: 12px;
            border-radius: 3px;
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
                    <a href="users.php" class="active">
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
                    <a href="email_users.php">
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
                <h1>User Account Management</h1>
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">All Users</h3>
                    <div class="alert-container"></div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <span class="me-2">Show</span>
                            <select class="form-select form-select-sm" style="width: 80px;" id="entriesSelect">
                                <option value="10" <?php echo $perPage == 10 ? 'selected' : ''; ?>>10</option>
                                <option value="25" <?php echo $perPage == 25 ? 'selected' : ''; ?>>25</option>
                                <option value="50" <?php echo $perPage == 50 ? 'selected' : ''; ?>>50</option>
                                <option value="100" <?php echo $perPage == 100 ? 'selected' : ''; ?>>100</option>
                            </select>
                            <span class="ms-2">entries</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <form method="GET" action="">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search..." name="search" value="<?php echo htmlspecialchars($search); ?>">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Client ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Balance</th>
                                <th>Active Deposit</th>
                                <th>Profit</th>
                                <th>Bonus</th>
                                <th>Referral ID</th>
                                <th>Date Created</th>
                                <th>Last Investment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if ($result->num_rows > 0) {
                                $count = $offset + 1;
                                while ($user = $result->fetch_assoc()) {
                                    $lastInvestment = isset($investments[$user['id']]) ? 
                                        date('Y-m-d H:i:s', strtotime($investments[$user['id']]['last_investment'])) : 
                                        'No investments';
                                    $investmentStatus = isset($investments[$user['id']]) ? 
                                        $investments[$user['id']]['status'] : 
                                        'not yet funded';
                            ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="currency editable" data-column="balance" data-id="<?php echo $user['id']; ?>">
                                        $<span class="display-value"><?php echo number_format($user['balance'], 2); ?></span>
                                        <input type="number" class="edit-input form-control" value="<?php echo $user['balance']; ?>" style="display: none;" min="0" step="0.01">
                                    </td>
                                    <td class="currency editable" data-column="active_deposit" data-id="<?php echo $user['id']; ?>">
                                        $<span class="display-value"><?php echo number_format($user['active_deposit'], 2); ?></span>
                                        <input type="number" class="edit-input form-control" value="<?php echo $user['active_deposit']; ?>" style="display: none;" min="0" step="0.01">
                                    </td>
                                    <td class="currency editable" data-column="profit" data-id="<?php echo $user['id']; ?>">
                                        $<span class="display-value"><?php echo number_format($user['profit'], 2); ?></span>
                                        <input type="number" class="edit-input form-control" value="<?php echo $user['profit']; ?>" style="display: none;" min="0" step="0.01">
                                    </td>
                                    <td class="currency editable" data-column="bonus" data-id="<?php echo $user['id']; ?>">
                                        $<span class="display-value"><?php echo number_format($user['bonus'], 2); ?></span>
                                        <input type="number" class="edit-input form-control" value="<?php echo $user['bonus']; ?>" style="display: none;" min="0" step="0.01">
                                    </td>
                                    <td><?php echo htmlspecialchars($user['referral_id'] ?? 'None'); ?></td>
                                    <td><?php echo date('Y-m-d H:i:s', strtotime($user['created_at'])); ?></td>
                                    <td><?php echo $lastInvestment; ?></td>
                                </tr>
                            <?php 
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="12" class="text-center">No users found</td>
                                </tr>
                            <?php 
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="pagination-container">
                    <div>
                        Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $perPage, $totalUsers); ?> of <?php echo $totalUsers; ?> entries
                    </div>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&entries=<?php echo $perPage; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
                            </li>
                            
                            <?php for ($i = max(1, $page - 2); $i <= min($page + 2, $totalPages); $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&entries=<?php echo $perPage; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&entries=<?php echo $perPage; ?>&search=<?php echo urlencode($search); ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Show global success/error notification
            function showNotification(message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const alert = $(`<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`);
                
                $('.alert-container').append(alert);
                
                // Auto-dismiss after 3 seconds
                setTimeout(function() {
                    alert.alert('close');
                }, 3000);
            }
            
            // Make cell editable on click
            $('.editable').click(function() {
                const cell = $(this);
                
                // Only make editable if not already editing
                if (!cell.hasClass('editing')) {
                    // Add editing class to cell
                    cell.addClass('editing');
                    
                    // Hide display value and show input
                    cell.find('.display-value').hide();
                    cell.find('.edit-input').show().focus();
                }
            });
            
            // Save on blur
            $('.edit-input').blur(function() {
                const input = $(this);
                const cell = input.closest('.editable');
                const value = input.val();
                const column = cell.data('column');
                const userId = cell.data('id');
                
                saveData(cell, userId, column, value);
            });
            
            // Save on enter key
            $('.edit-input').keypress(function(e) {
                if (e.which === 13) {
                    const input = $(this);
                    const cell = input.closest('.editable');
                    const value = input.val();
                    const column = cell.data('column');
                    const userId = cell.data('id');
                    
                    saveData(cell, userId, column, value);
                }
            });
            
            // Function to save data to server
            function saveData(cell, userId, column, value) {
                // Show loading indicator
                const displayValue = cell.find('.display-value');
                const input = cell.find('.edit-input');
                
                $.ajax({
                    url: window.location.href,
                    method: 'POST',
                    data: {
                        action: 'update_user',
                        user_id: userId,
                        column: column,
                        value: value
                    },
                    success: function(response) {
                        try {
                            const data = JSON.parse(response);
                            if (data.status === 'success') {
                                // Show success indicator
                                displayValue.text(data.value);
                                
                                // Show temporary success message
                                const feedback = $('<span class="save-feedback bg-success text-white">Saved</span>');
                                cell.append(feedback);
                                setTimeout(function() {
                                    feedback.fadeOut(function() {
                                        $(this).remove();
                                    });
                                }, 1000);
                            } else {
                                // Show error indicator
                                const feedback = $('<span class="save-feedback bg-danger text-white">Error</span>');
                                cell.append(feedback);
                                setTimeout(function() {
                                    feedback.fadeOut(function() {
                                        $(this).remove();
                                    });
                                }, 1000);
                                showNotification(data.message, 'error');
                            }
                        } catch (e) {
                            console.error('Invalid JSON response', e);
                            showNotification('Server returned an invalid response', 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX error', xhr);
                        // Show error indicator
                        const feedback = $('<span class="save-feedback bg-danger text-white">Error</span>');
                        cell.append(feedback);
                        setTimeout(function() {
                            feedback.fadeOut(function() {
                                $(this).remove();
                            });
                        }, 1000);
                        showNotification('Connection error occurred', 'error');
                    },
                    complete: function() {
                        // End edit mode and reset display
                        cell.removeClass('editing');
                        displayValue.show();
                        input.hide();
                    }
                });
            }
            
            // Entries select change
            $('#entriesSelect').change(function() {
                const entries = $(this).val();
                window.location.href = '?page=1&entries=' + entries + '&search=<?php echo urlencode($search); ?>';
            });
        });
    </script>
</body>
</html> 