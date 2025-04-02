<?php require_once __DIR__."/includes/withdraw_funds.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw Funds - AxisBot</title>
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
        $title = "Withdraw Funds";
        require_once  __DIR__."/includes/nav.php"; ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-7">
                    <div class="history-container">
                        <h5 class="section-header">Withdrawal History</h5>
                        <?php if (empty($withdrawals)): ?>
                            <div class="empty-state">
                                you have not made any transactions yet
                            </div>
                        <?php else: ?>
                            <?php foreach ($withdrawals as $withdrawal): ?>
                                <div class="transaction-item">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="transaction-date">
                                                <?php echo date('d M Y', strtotime($withdrawal['created_at'])); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="transaction-amount">
                                                $<?php echo number_format($withdrawal['amount'], 2); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="wallet-address">
                                                <?php echo substr($withdrawal['wallet_address'], 0, 10) . '...'; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="status-badge status-<?php echo $withdrawal['status']; ?>">
                                                <?php echo ucfirst($withdrawal['status']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="withdraw-container">
                        <h5 class="section-header">Withdraw Here</h5>
                        
                        <div class="user-info">
                            <img src="<?= ASSET_URL?>/assets/avatar-1.png" alt="User Avatar" style="width: 28px; height: 28px; border-radius: 50%; margin-right: 8px; object-fit: cover;">
                            <span>Hi, <?php echo htmlspecialchars($username); ?></span>
                        </div>
                        
                        <div class="balance-display">
                            <span class="balance-label">Available Balance:</span>
                            <span>$<?php echo number_format($balance, 2); ?></span>
                        </div>
                        
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($balance <= 0): ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                You don't have any funds available to withdraw. Your balance can only be updated by an admin once your investment has been confirmed.
                            </div>
                        <?php else: ?>
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label class="form-label">Amount</label>
                                    <input type="number" class="form-control" name="amount" placeholder="0.00" required step="0.01" max="<?php echo $balance; ?>">
                                    <small class="text-muted">Maximum withdrawal amount: $<?php echo number_format($balance, 2); ?></small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Plan</label>
                                    <select class="form-select" name="currency">
                                        <option value="USDT">USDT</option>
                                        <option value="BTC">BTC</option>
                                        <option value="ETH">ETH</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Wallet Address</label>
                                    <input type="text" class="form-control" name="wallet_address" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" readonly>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="submit-btn">WITHDRAW</button>
                                </div>
                            </form>
                        <?php endif; ?>
                        
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                All withdrawal requests require admin approval. Processing time is typically 24-48 hours.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="text-center text-muted py-4">
            <small>Copyright © 2015-2025. All Rights Reserved</small>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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