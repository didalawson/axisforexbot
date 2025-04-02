<?php require_once __DIR__."/includes/transactions.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - AxisBot</title>
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
        $title = "Transaction History";
        require_once  __DIR__."/includes/nav.php"; ?>

        <div class="container-fluid">
            <div class="transactions-container">
                <?php if (empty($transactions)): ?>
                    <div class="empty-state">
                        <i class="fas fa-history"></i>
                        <h5>No transactions yet</h5>
                        <p>Your investment history will appear here</p>
                        <a href="make_investment.php" class="btn btn-primary mt-3">Make an Investment</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($transactions as $transaction): ?>
                        <div class="transaction-card">
                            <div class="transaction-header">
                                <div class="transaction-amount">
                                    $<?php echo number_format($transaction['amount'], 2); ?>
                                </div>
                                <div class="transaction-date">
                                    <?php echo date('M d, Y H:i', strtotime($transaction['created_at'])); ?>
                                </div>
                            </div>
                            <div class="transaction-details">
                                <div class="transaction-plan">
                                    <?php echo ucfirst($transaction['plan']); ?> Plan
                                </div>
                                <div class="status-badge status-<?php echo strtolower($transaction['status']); ?>">
                                    <?php echo ucfirst($transaction['status']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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