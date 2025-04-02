<?php
require_once 'admin_auth.php';

try {
    // Initialize stats array with default values
    $stats = [
        'users' => 0,
        'investments' => 0,
        'deposits' => 0,
        'withdrawals' => 0,
        'total_balance' => 0,
        'pending_withdrawals' => 0,
        'pending_deposits' => 0
    ];

    // Fetch statistics one by one with error handling
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    if ($result) {
        $stats['users'] = $result->fetch_assoc()['count'];
    }

    $result = $conn->query("SELECT COUNT(*) as count FROM investments");
    if ($result) {
        $stats['investments'] = $result->fetch_assoc()['count'];
    }

    $result = $conn->query("SELECT COUNT(*) as count FROM deposits");
    if ($result) {
        $stats['deposits'] = $result->fetch_assoc()['count'];
    }

    $result = $conn->query("SELECT COUNT(*) as count FROM withdrawals");
    if ($result) {
        $stats['withdrawals'] = $result->fetch_assoc()['count'];
    }

    $result = $conn->query("SELECT SUM(balance) as total FROM user_balance");
    if ($result) {
        $stats['total_balance'] = $result->fetch_assoc()['total'] ?? 0;
    }

    $result = $conn->query("SELECT COUNT(*) as count FROM withdrawals WHERE status = 'pending'");
    if ($result) {
        $stats['pending_withdrawals'] = $result->fetch_assoc()['count'];
    }

    $result = $conn->query("SELECT COUNT(*) as count FROM deposits WHERE status = 'pending'");
    if ($result) {
        $stats['pending_deposits'] = $result->fetch_assoc()['count'];
    }

    // Fetch recent users
    $recentUsers = [];
    $result = $conn->query("SELECT id, email, created_at FROM users ORDER BY created_at DESC LIMIT 5");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $recentUsers[] = $row;
        }
    }

    // Fetch recent deposits
    $recentDeposits = [];
    $sql = "SELECT d.amount, d.status, d.created_at, u.email 
            FROM deposits d 
            JOIN users u ON d.user_id = u.id 
            ORDER BY d.created_at DESC LIMIT 3";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['type'] = 'deposit';
            $recentDeposits[] = $row;
        }
    }

    // Fetch recent withdrawals
    $recentWithdrawals = [];
    $sql = "SELECT w.amount, w.status, w.created_at, u.email 
            FROM withdrawals w 
            JOIN users u ON w.user_id = u.id 
            ORDER BY w.created_at DESC LIMIT 3";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['type'] = 'withdrawal';
            $recentWithdrawals[] = $row;
        }
    }

    // Combine and sort recent transactions
    $recentTransactions = array_merge($recentDeposits, $recentWithdrawals);
    usort($recentTransactions, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
    $recentTransactions = array_slice($recentTransactions, 0, 5);

} catch (Exception $e) {
    error_log("Dashboard Error: " . $e->getMessage());
    $error = "Error loading dashboard data. Please try again later.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AxisBot</title>
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
            <!-- Top Bar -->
            <?php 
            $title = "Dashboard Overview";
            require_once __DIR__."/includes/header.php"; ?>

            <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stats-card">
                    <div class="icon" style="background: var(--primary-color)">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3><?php echo number_format($stats['users']); ?></h3>
                    <p>Total Users</p>
                </div>

                <div class="stats-card">
                    <div class="icon" style="background: var(--success-color)">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3><?php echo number_format($stats['investments']); ?></h3>
                    <p>Total Investments</p>
                </div>

                <div class="stats-card">
                    <div class="icon" style="background: var(--warning-color)">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h3><?php echo number_format($stats['deposits']); ?></h3>
                    <p>Total Deposits</p>
                </div>

                <div class="stats-card">
                    <div class="icon" style="background: var(--danger-color)">
                        <i class="fas fa-money-bill-transfer"></i>
                    </div>
                    <h3><?php echo number_format($stats['withdrawals']); ?></h3>
                    <p>Total Withdrawals</p>
                </div>
            </div>

            <!-- Activity Grid -->
            <div class="activity-grid">
                <!-- Recent Users -->
                <div class="activity-card">
                    <h2>Recent Users</h2>
                    <?php if (!empty($recentUsers)): ?>
                        <?php foreach ($recentUsers as $user): ?>
                        <div class="activity-item">
                            <div>
                                <strong><?php echo htmlspecialchars($user['email']); ?></strong>
                                <div class="time"><?php echo date('M d, Y H:i', strtotime($user['created_at'])); ?></div>
                            </div>
                            <span class="badge bg-success">New</span>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">No recent users</p>
                    <?php endif; ?>
                </div>

                <!-- Recent Transactions -->
                <div class="activity-card">
                    <h2>Recent Transactions</h2>
                    <?php if (!empty($recentTransactions)): ?>
                        <?php foreach ($recentTransactions as $transaction): ?>
                        <div class="activity-item">
                            <div>
                                <strong><?php echo htmlspecialchars($transaction['email']); ?></strong>
                                <div class="time">
                                    <?php echo ucfirst($transaction['type']); ?> - 
                                    $<?php echo number_format($transaction['amount'], 2); ?>
                                </div>
                            </div>
                            <span class="badge bg-<?php echo $transaction['status'] === 'pending' ? 'warning' : 'success'; ?>">
                                <?php echo ucfirst($transaction['status']); ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">No recent transactions</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Add this card somewhere in the dashboard content area -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-id-card me-2"></i> KYC Verification</h5>
                </div>
                <div class="card-body">
                    <p>Manage user verification documents and identity verification requests.</p>
                    <a href="/public_html/backend/admin/kyc_management.php" class="btn btn-primary">View KYC Management</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            const toggleBtn = document.querySelector('.btn-toggle-menu');

            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                mainContent.classList.toggle('active');
            });
        });
    </script>
</body>
</html> 