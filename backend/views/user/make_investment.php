<?php
require_once __DIR__."/includes/make_investment.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make an Investment - AxisBot</title>
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
        $title = "Make an Investment";
        require_once  __DIR__."/includes/nav.php"; ?>

        <div class="container-fluid">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <div class="investment-container">
                <div class="investment-header">
                    <h5 class="mb-0">Select Investment Plan</h5>
                </div>
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Investment Amount ($)</label>
                            <input type="number" class="form-control" name="amount" id="investment-amount" placeholder="Enter amount" required min="1" step="0.01">
                            <small class="text-muted" id="amount-hint">Select a plan to see the required investment range</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="plan-card" onclick="selectPlan('basic')">
                                <h5>Basic Plan</h5>
                                <ul class="features">
                                    <li><i class="fas fa-check"></i> 5% Daily Returns</li>
                                    <li><i class="fas fa-check"></i> 24/7 Support</li>
                                    <li><i class="fas fa-check"></i> Instant Withdrawal</li>
                                </ul>
                                <input type="radio" name="plan" value="basic" style="display: none;" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="plan-card" onclick="selectPlan('premium')">
                                <h5>Premium Plan</h5>
                                <ul class="features">
                                    <li><i class="fas fa-check"></i> 8% Daily Returns</li>
                                    <li><i class="fas fa-check"></i> Priority Support</li>
                                    <li><i class="fas fa-check"></i> Instant Withdrawal</li>
                                </ul>
                                <input type="radio" name="plan" value="premium" style="display: none;" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="plan-card" onclick="selectPlan('vip')">
                                <h5>VIP Plan</h5>
                                <ul class="features">
                                    <li><i class="fas fa-check"></i> 12% Daily Returns</li>
                                    <li><i class="fas fa-check"></i> VIP Support</li>
                                    <li><i class="fas fa-check"></i> Instant Withdrawal</li>
                                </ul>
                                <input type="radio" name="plan" value="vip" style="display: none;" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="make_investment" class="invest-btn">MAKE INVESTMENT</button>
                </form>
            </div>

            <!-- Recent Transactions Section -->
            <div class="transactions-section mt-5">
                <h4 class="mb-4">Recent Transactions</h4>
                <div class="transactions-container">
                    <?php
                    // Fetch user's recent transactions
                    $stmt = $conn->prepare("SELECT * FROM investments WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
                    $stmt->bind_param("i", $userId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $transactions = $result->fetch_all(MYSQLI_ASSOC);

                    if (empty($transactions)): ?>
                        <div class="empty-state">
                            <i class="fas fa-history"></i>
                            <h5>No transactions yet</h5>
                            <p>Your investment history will appear here</p>
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
        </div>

        <footer class="text-center text-muted py-4">
            <small>Copyright © 2015-2025. All Rights Reserved</small>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectPlan(plan) {
            // Remove selected class from all cards
            document.querySelectorAll('.plan-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class to clicked card
            event.currentTarget.classList.add('selected');
            
            // Check the radio button
            document.querySelector(`input[value="${plan}"]`).checked = true;

            // Update amount input constraints and hint
            const amountInput = document.getElementById('investment-amount');
            const amountHint = document.getElementById('amount-hint');
            
            switch(plan) {
                case 'basic':
                    amountInput.min = 100;
                    amountInput.max = 1000;
                    amountHint.textContent = "Investment range: $100 - $1,000";
                    break;
                case 'premium':
                    amountInput.min = 1001;
                    amountInput.max = 5000;
                    amountHint.textContent = "Investment range: $1,001 - $5,000";
                    break;
                case 'vip':
                    amountInput.min = 5001;
                    amountInput.max = 1000000; // Set a reasonable maximum
                    amountHint.textContent = "Minimum investment: $5,001";
                    break;
            }
        }

        // Form validation before submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const selectedPlan = document.querySelector('input[name="plan"]:checked');
            if (!selectedPlan) {
                e.preventDefault();
                alert('Please select an investment plan');
                return;
            }

            const amount = parseFloat(document.getElementById('investment-amount').value);
            const plan = selectedPlan.value;
            
            let isValid = true;
            let errorMessage = '';

            switch(plan) {
                case 'basic':
                    if (amount < 100 || amount > 1000) {
                        isValid = false;
                        errorMessage = 'Basic plan requires an investment between $100 and $1,000';
                    }
                    break;
                case 'premium':
                    if (amount < 1001 || amount > 5000) {
                        isValid = false;
                        errorMessage = 'Premium plan requires an investment between $1,001 and $5,000';
                    }
                    break;
                case 'vip':
                    if (amount < 5001) {
                        isValid = false;
                        errorMessage = 'VIP plan requires a minimum investment of $5,001';
                    }
                    break;
            }

            if (!isValid) {
                e.preventDefault();
                alert(errorMessage);
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